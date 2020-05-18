<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This plugin sends users a welcome message after logging in
 * and notify a moderator a new user has been added
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage autoemail
 * @copyright  Sangita
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_user_cr_email;

defined('MOODLE_INTERNAL') || die();
class observer {

    const AUTOMAIL_COMPONENT_NAME ='local_user_cr_email';

    public static function send_labour_net_notification(\core\event\user_created $event) {
        global $CFG, $SITE,$DB,$USER,$PAGE,$OUTPUT;
        $eventdata = $event->get_data();

        $user = \core_user::get_user($eventdata['objectid']);

        $sender = get_admin();
        if (!empty($user->email)) {
            //sangita...
            $sitename = $SITE->fullname;
            $sitelink = \html_writer::link(new \moodle_url('/'), $SITE->fullname);
            $username =  $user->username;
            $fullname = fullname($user);
            $a = new \stdClass();
            $a->fullname = $fullname;
            $a->sitename = $sitename;
            $a->sitelink = $sitelink;
            $a->username = $username;
            $userid = $user->id;
            //subsect id
            $fieldshortname  = 'subsector';
            $sql = 'SELECT uid.data FROM {user_info_data} AS uid JOIN {user_info_field} AS uif
                    ON uif.id = uid.fieldid where uid.userid = ? AND uif.shortname = ?';
            $subsectordetails = $DB->get_record_sql($sql,array($userid,$fieldshortname));
            $emailsubject = '';
            $emailbody = '';
            $a->preassessment_link_d = '';

            $subsectdata = $subsectordetails->data;
            //Now find out the mod id for pre gmp_test for respective subsect

            $getprename = get_string('preassess','local_user_cr_email');
             if(!empty($subsectdata)){
                  $sqlquery = "SELECT cc.idnumber from {course_categories} AS cc where idnumber LIKE '%$subsectdata%'";
                  $datainfo = $DB->get_record_sql($sqlquery);
                  $preassmod1 = '';
                  if(!empty($datainfo)){
                       $preassessmentinfo = $datainfo->idnumber;
                       $preassarray = explode('_',$preassessmentinfo);
                       $preassmod1 = $preassarray[1];
                       $a->preassessment_link_d =  \html_writer::link(new \moodle_url('/mod/quiz/view.php?id='.$preassmod1), $getprename);
                  }

                switch ($subsectdata) {
                    case "Dell":
                        // find out the preassessment test link for dell here
                        $emailsubject = get_string('email_subject_PCB','local_user_cr_email',$a);
                        $emailbody = get_string('email_body_PCB','local_user_cr_email',$a);
                        break;

                    case "Sapient":
                    //we find all data depends on string $preassmod2
                        // find out the preassessment test link for sapient  here
                        $emailsubject = get_string('email_subject_IT','local_user_cr_email',$a);
                        $emailbody = get_string('email_body_IT','local_user_cr_email',$a);
                        break;

                    default:
                        $a->preassessment_link_d = '';
                        $emailsubject = get_string('email_subject','local_user_cr_email',$a);
                        $emailbody = get_string('email_body','local_user_cr_email',$a);
                        break;

                }
                $relativefilepath = '';
                if (!empty($user) && !empty($sender->email) && !empty($emailsubject) && !empty($emailbody)) {
                    email_to_user($user, $sender, $emailsubject, html_to_text($emailbody), $emailbody,$relativefilepath=null,null);
                }

            } else { // there is no subsector usuallly for trainer
              $relativefilepath = '';
              $emailsubject = get_string('email_subject','local_user_cr_email',$a);
              $emailbody = get_string('email_body','local_user_cr_email',$a);
              if (!empty($user) && !empty($sender->email) && !empty($emailsubject) && !empty($emailbody)) {
                  email_to_user($user, $sender, $emailsubject, html_to_text($emailbody), $emailbody,$relativefilepath=null,null);
              }
            }
        }

    }
}
