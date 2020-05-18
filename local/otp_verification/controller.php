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
 * Main login page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/auth/otplogin/textlocal.class.php');
require_once($CFG->libdir.'/authlib.php');
require_once($CFG->libdir.'/ldaplib.php');
require_once($CFG->dirroot.'/user/lib.php');
global $CFG,$DB;
    $otp = required_param('otp',PARAM_INT);
    $username = required_param('username',PARAM_RAW);
    $type = required_param('type',PARAM_RAW);
    if(!empty($otp)){
        if ($otp == $_SESSION['session_otp']) {
            unset($_SESSION['session_otp']);
            $userobject = $DB->get_record('user',array('username'=>$username));
            //if session url is empty redirect to dashboard.
            if(empty($SESSION->wantsurl)){
                $SESSION->wantsurl = $CFG->wwwroot.'/my';
            }
            if($type ==='create'){
                $DB->set_field("user", "confirmed", 1, array("id"=>$userobject->id));
                complete_user_login($userobject);
                $redirecturl = $SESSION->wantsurl;
                redirect($redirecturl);
            }elseif($type ==='update'){
                $DB->set_field("user", "confirmed", 1, array("id"=>$userobject->id));
                $redirecturl = $SESSION->wantsurl;
                redirect($redirecturl);
            }
        }
        // if otp is not matching call new form just to udate mobile no
        else{
            $redirecturl = $CFG->wwwroot.'/local/otp_verification/update.php?username='.$username;
            redirect($redirecturl,get_string('pleaseupdatemobilenumber','local_otp_verification'));
        }
    } 