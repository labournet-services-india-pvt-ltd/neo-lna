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
global $DB,$CFG;

if(isloggedin()) {
    redirect($CFG->wwwroot.'/my');
}
$context = context_system::instance();
$PAGE->set_url("$CFG->httpswwwroot/login/index.php");
$PAGE->set_context($context);
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('plugin', 'auth_otplogin'));
$PAGE->set_heading(get_string('plugin', 'auth_otplogin'));
$PAGE->requires->jquery();
$PAGE->requires->css('/auth/otplogin/style.css');
$PAGE->requires->js(new moodle_url($CFG->wwwroot . "/auth/otplogin/js/verification.js"));
echo $OUTPUT->header();
$username = optional_param('username','',PARAM_RAW);
?>
<!--manju: form for otp verification -->
<div class="row">
    <div class="col-md-6">
        <div class="error"></div>
        <div class="success"></div>
        <form method="GET" action="controller.php" >
            <div class="form-group ">
                <label for="exampleInputEmail1"><?php echo get_string('enterotp', 'auth_otplogin'); ?></label>
                <input type="number" name="otp"  id="mobileOtp" class="form-control" placeholder="Enter the OTP">
                 <input type="hidden" name="username" value="<?php echo $username;?>"  id="username">       
            </div>
            
                <input type="submit" class="btn btn-primary text-center">       
        </form>
    </div>
</div>
<?php
echo $OUTPUT->footer();
