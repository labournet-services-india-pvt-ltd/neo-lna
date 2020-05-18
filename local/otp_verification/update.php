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
require_once('forms/update_number.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/user/externallib.php');
require_once($CFG->dirroot . '/files/externallib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot. '/user/profile/lib.php');
global $DB, $CFG, $USER;  
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$username = optional_param('username','',PARAM_RAW);
$title = get_string('pluginame', 'local_otp_verification');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
$mform = new local_update_mobileno();


$PAGE->set_url($CFG->wwwroot . '/local/otp_verification/update.php',array('username'=>$username));

echo $OUTPUT->header();
$mform->display();
//getting mobile number from form and updte into user profile
if($data = $mform->get_data()) {
	$userobject = $DB->get_record('user',array('id'=>$data->id));
	$userobject->profile_field_mobilephone = $data->mobilephone;
	$data = new stdClass();
	$field = $DB->get_field('user_info_field', 'id', array('shortname'=>'mobilephone'));
	$data->userid  = $userobject->id;
	$data->fieldid = $field;
	$data->data    = $userobject->profile_field_mobilephone;
	
	if ($dataid = $DB->get_field('user_info_data', 'id', array('userid' => $data->userid, 'fieldid' => $data->fieldid))) {
		$data->id = $dataid;
		
		$upstatus = $DB->update_record('user_info_data', $data);
		if ($upstatus) {
			echo get_string('updatedsuccessfully','local_otp_verification');
		}
	} else {
		$instatus = $DB->insert_record('user_info_data', $data);
		if ($instatus) {
			echo get_string('updatedsuccessfully','local_otp_verification');
		}
	}

//trigger user updated event after profile update.
	\core\event\user_updated::create_from_userid($userobject->id)->trigger();

}
echo $OUTPUT->footer();