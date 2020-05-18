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
 * Authentication Plugin: OTP Authentication
 *
 * @author Shambhu kumar
 * @package auth_otplogin
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');
require ('textlocal.class.php');
include_once($CFG->dirroot.'/user/profile/lib.php');
/**
 * OTP authentication plugin.
 */
class auth_plugin_otplogin extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function auth_plugin_otplogin() {
        $this->authtype = 'otplogin';
        $this->config = get_config('auth/otplogin');
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG, $DB;
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return validate_internal_user_password($user, $password);
        }
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object  (with system magic quotes)
     * @param  string  $newpassword Plaintext password (with system magic quotes)
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    function can_signup() {
        return true;
    }

    /**
     * Sign up a new user ready for confirmation.
     * Password is passed in plaintext.
     *
     * @param object $user new user object
     * @param boolean $notify print notice with link and terminate
     */
    function user_signup($user, $notify=true) {
        global $CFG, $DB;
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');
        $plainpassword = $user->password;
        $user->password = hash_internal_user_password($user->password);
        if (empty($user->calendartype)) {
            $user->calendartype = $CFG->calendartype;
        }

        $user->id = user_create_user($user, false, false);

        user_add_password_history($user->id, $plainpassword);

        // Save any custom profile field information.
        profile_save_data($user);

        // Trigger event.
        \core\event\user_created::create_from_userid($user->id)->trigger();
        return true;

    }

    /**
     * Returns true if plugin allows confirming of new users.
     *
     * @return bool
     */
    function can_confirm() {
        return true;
    }

    /**
     * Confirm the new user as registered.
     *
     * @param string $username
     * @param string $confirmsecret
     */
    function user_confirm($username, $confirmsecret) {
        global $DB;
        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->auth != $this->authtype) {
                return AUTH_CONFIRM_ERROR;

            } else if ($user->secret == $confirmsecret && $user->confirmed) {
                return AUTH_CONFIRM_ALREADY;

            } else if ($user->secret == $confirmsecret) {   // They have provided the secret key to get in
                $DB->set_field("user", "confirmed", 1, array("id"=>$user->id));
                return AUTH_CONFIRM_OK;
            }
        } else {
            return AUTH_CONFIRM_ERROR;
        }
    }

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null; // use default internal method
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return true;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {

        include "config.html";
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        // set to defaults if undefined
        
        // save settings
        set_config('baseurl', $config->baseurl, 'auth/otplogin');
        set_config('service', $config->service, 'auth/otplogin');
        set_config('workingkey', $config->workingkey, 'auth/otplogin');
        return true;
    }

    /**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @return bool
     */
    function is_captcha_enabled() {
        global $CFG;
        return isset($CFG->recaptchapublickey) && isset($CFG->recaptchaprivatekey) && get_config("auth/{$this->authtype}", 'recaptcha');
    }

    /**
     * Authentication hook - is called every time user hit the login page
     * The code is run only if the param code is mentionned.
     */
    public function user_authenticated_hook(&$user, $username, $password) {
        global $PAGE, $CFG,$DB;
        $apikey ='';
        $sender ='';
        $mobile_number='';
        $user = $DB->get_record('user',array('username'=>$username));
        //checking whether the user is logging in first time or not
        if(empty($user->firstaccess)){
        profile_load_data($user);
        //taking custom mobile number field.
        if(!empty($user->profile_field_mobilephone)){
            $mobile_number = $user->profile_field_mobilephone;
        }
        if(!empty($mobile_number)){
            // print_object($mobile_number);die;
            $config = get_config('auth_otplogin');
            $apiKey = $config->otplogin_apikey; 
            $sender = $config->otplogin_sender;
            $Textlocal = new Textlocal(false, false, $apiKey);
            $numbers = array($mobile_number);
            $otp = rand(100000, 999999);
            $_SESSION['session_otp'] = $otp;
            $message = get_string('youronetimepasswordis','auth_otplogin'). $otp;
            $response = $Textlocal->sendSms($numbers, $message, $sender);
            if($response){
                $redirecturl = $CFG->wwwroot.'/auth/otplogin/login.php?username='.$username;
                redirect($redirecturl,get_string('otpsentsuccessfully','auth_otplogin'));
            }

        }else{
             $redirecturl = $CFG->wwwroot.'/login/index.php';
            redirect($redirecturl,get_string('pleaseupdatemobilenumber','auth_otplogin'));
            
        }
    }else{
    	return true; 
    }
    }
}
