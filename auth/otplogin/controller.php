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
require ('textlocal.class.php');
require_once($CFG->libdir.'/authlib.php');
require_once($CFG->libdir.'/ldaplib.php');
require_once($CFG->dirroot.'/user/lib.php');
global $CFG,$DB;
    $otp = required_param('otp',PARAM_INT);
    $username = required_param('username',PARAM_RAW);
    if(empty($SESSION->wantsurl)){
                $SESSION->wantsurl = $CFG->wwwroot.'/my';
            }
    if(!empty($otp)){
        if ($otp == $_SESSION['session_otp']) {
            unset($_SESSION['session_otp']);
            $userobject = $DB->get_record('user',array('username'=>$username));
            complete_user_login($userobject);
            $redirecturl = $SESSION->wantsurl;
            redirect($redirecturl);
        }
         else {
            $redirecturl = $CFG->wwwroot.'/auth/otplogin/login.php';
            redirect($redirecturl,get_string('verificationfailed','auth_otplogin'));
        }
    } else {
            $redirecturl = $CFG->wwwroot.'/auth/otplogin/login.php';
            redirect($redirecturl,get_string('verificationfailed','auth_otplogin'));
        }