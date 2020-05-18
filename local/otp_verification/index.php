<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_learningreport
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
global $DB, $CFG, $USER;  
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/otp_verification/index.php');
$title = get_string('pluginame', 'local_otp_verification');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
echo $OUTPUT->header();
$username = optional_param('username','',PARAM_RAW);
$type = optional_param('type','',PARAM_RAW);
?>
<!--manju: form for otp verification -->
<div class="row">
    <div class="col-md-6">
        <div class="error"></div>
        <div class="success"></div>
        <form method="GET" action="controller.php" >
            <div class="form-group ">
                <label for="exampleInputEmail1"><?php echo get_string('enterotp', 'local_otp_verification');  ?></label>
                <input type="number" name="otp"  id="mobileOtp" class="form-control" placeholder="Enter the OTP">
                 <input type="hidden" name="username" value="<?php echo $username;?>"  id="username">
                 <input type="hidden" name="type" value="<?php echo $type;?>"  id="type">       
            </div>
            
                <input type="submit" class="btn btn-primary text-center">       
        </form>
    </div>
</div>
<?php

echo $OUTPUT->footer();