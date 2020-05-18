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
 * @subpackage edutimeline
 * @copyright  Sangita (Mihir Jana)
 * @license    http://lmsofindia.com GNU GPL v3 or later
 */
namespace local_edutimeline;
defined('MOODLE_INTERNAL') || die();
class observer {
  //Sangita :Edutimeline
    //this observer is used to insert record in mdl_edutimeline table when course module is created.
    public static function edutimeline_course_module_deleted(\core\event\course_module_deleted $event) {
        global $DB;
       
       $eventdata = $event->get_data();  
       $cmid = $eventdata['contextinstanceid'];
       $existsrecord = $DB->record_exists('edutimeline', array('cmid'=>$cmid));
       if($existsrecord){
          $DB->delete_records('edutimeline', array('cmid'=>$cmid));
       }
    }
}       