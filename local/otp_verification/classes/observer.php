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
 * @subpackage cs_reminder
 * @copyright  Manjunath
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_otp_verification;
global $CFG;
include_once($CFG->dirroot.'/user/profile/lib.php');
defined('MOODLE_INTERNAL') || die();
class observer {

    public static function verify_user_otp(\core\event\user_created $event) {
        global $CFG, $SITE,$DB,$USER;
        require_once($CFG->dirroot.'/auth/otplogin/textlocal.class.php');
        //check if the user is admin or not
        if(is_siteadmin()) {
            //if admin do nothing
        } else {
            $eventdata = $event->get_data();
            $user = \core_user::get_user($eventdata['objectid']);
            if($user->confirmed == 0){
                $DB->set_field("user", "confirmed", 1, array("id"=>$user->id));
            }
            profile_load_data($user);

            if(!empty($user->profile_field_mobilephone)){
                $mobile_number = $user->profile_field_mobilephone;
            }
            if(!empty($mobile_number)){
                $config = get_config('auth_otplogin');
                $apiKey = $config->otplogin_apikey; 
                $sender = $config->otplogin_sender;
                $Textlocal = new \Textlocal(false, false, $apiKey);
                $numbers = array($mobile_number);
                $otp = rand(100000, 999999);
                $_SESSION['session_otp'] = $otp;
                $message = get_string('youronetimepasswordis','local_otp_verification'). $otp;
                // $response = $Textlocal->sendSms($numbers, $message, $sender);
                  $response = true;

                if($response){
                    $redirecturl = $CFG->wwwroot.'/local/otp_verification/index.php?username='.$user->username.'&type=create';
                    redirect($redirecturl,get_string('otpsentsuccessfully','local_otp_verification'));
                }

            }else{
             $redirecturl = $CFG->wwwroot.'/local/otp_verification/update.php?username='.$user->username;
             redirect($redirecturl,get_string('pleaseupdatemobilenumber','local_otp_verification'));

         }
     }

 }
 public static function verify_updated_profile(\core\event\user_updated $event) {
    global $CFG, $SITE,$DB,$USER;
    require_once($CFG->dirroot.'/auth/otplogin/textlocal.class.php');
    $eventdata = $event->get_data();
    
    // find who is creating the user
    $createdby = \core_user::get_user($eventdata['userid']);

    if(is_siteadmin()) {

    } else {

        $user = \core_user::get_user($eventdata['objectid']);

        profile_load_data($user);
        if(!empty($user->profile_field_mobilephone)){
            $mobile_number = $user->profile_field_mobilephone;
        }
        if(!empty($mobile_number)){
            $config = get_config('auth_otplogin');
            $apiKey = $config->otplogin_apikey; 
            $sender = $config->otplogin_sender;
            $Textlocal = new \Textlocal(false, false, $apiKey);
            $numbers = array($mobile_number);
            $otp = rand(100000, 999999);
            $_SESSION['session_otp'] = $otp;
            $message = get_string('youronetimepasswordis','local_otp_verification'). $otp;
            $response = $Textlocal->sendSms($numbers, $message, $sender);
            // $response = true;

            if($response){
                $redirecturl = $CFG->wwwroot.'/local/otp_verification/index.php?username='.$user->username.'&type=update';
                redirect($redirecturl,get_string('otpsentsuccessfully','local_otp_verification'));
            }

        }else{
           $redirecturl = $CFG->wwwroot.'/local/otp_verification/update.php?username='.$user->username;
           redirect($redirecturl,get_string('pleaseupdatemobilenumber','local_otp_verification'));

       }

} // closing of issiteadmin

}

}       