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
 * @package    local_trainer_analysis
 * @copyright  Manjunath<manjunath@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('lib.php');
global $DB, $CFG, $USER;
//require_login(true);
// $context = context_system::instance();
// $PAGE->set_context($context);
// $PAGE->set_pagelayout('admin');
// $myreport = has_capability('local/trainer_analysis:myreport',$context);
// $PAGE->set_url($CFG->wwwroot . '/local/trainer_analysis/livereport.php');
// $title = get_string('title', 'local_trainer_analysis');
// $PAGE->navbar->add($title);
// $PAGE->set_title($title);
// $PAGE->set_heading($title);
// $PAGE->requires->jquery();
// include_once('jslink.php');
// echo $OUTPUT->header();
// $jslink='';

$value1 = optional_param('value1', 0, PARAM_INT); // Course_module ID.
$value2 = optional_param('value2', 0, PARAM_INT); // Course_module ID.
$syncdate = optional_param('date', 0, PARAM_INT); // Course_module ID.
	//change approach
	require_once($CFG->dirroot.'/user/lib.php');
	require_once($CFG->dirroot.'/course/lib.php');
	global $DB, $CFG;
	// LN academy category is 84
	$lnacad = 84;
	//$syncdate = strtotime('today');
	if ($syncdate != 0) {
		$syncdate = $syncdate;
	} else {
		$syncdate = strtotime('today');
	}
/************************************ OLD CODE ************************/

	$sql = "SELECT DISTINCT userid FROM {role_assignments} WHERE roleid = 3";
	$trainers = $DB->get_records_sql($sql);

	//get teachers of this course only

/*


	foreach ($trainers as $trainer) {
		$userid =$trainer->userid;
		$user = $DB->get_record('user',array('id'=>$userid));
		$cdetails = get_trainer_courses($userid);

		foreach ($cdetails as $value) {
			echo '</br>';
			echo 'checking for course'.$value.'and trainer'.$trainer->userid;
			echo '</br>';

			if ($value >= $value1 and $value <= $value2) {
				echo 'Syncing the data for -'.$value;
				// echo '</br>';
				$coursevalue = $value;
				$checkcourse = $DB->get_record('course', array('id' => $coursevalue));
				$modinfo = get_fast_modinfo($checkcourse);
					foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
						$someJSON = $cm->availability;
											$someArray = json_decode($someJSON, true);
											if (!empty($someArray)) {
												foreach ($someArray['c'] as $condition) {
													if ($condition['type'] == 'group') {
														$groupid = $condition['id'];
														$getgroups = $DB->get_records('groups_members', array('userid' => $userid, 'groupid' => $groupid));
														if (!empty($getgroups)) {
															 echo $checkcourse->id.'---'.$userid;
															 echo '<br>';
																$trainercongreatime[] = get_congrea_details_trainer_todb($checkcourse,$cm,$userid,$syncdate);
														}
													}
												}
											}

					}
			} //if clasuse


		}


	} // end of for each trainer

// now insert one common record for all the time
// write code to insert record in db

/********************* END OF OLD CODE *********************/

//
echo 'abcd';
$syncdate1 = '2020-05-11';
$syncdate = strtotime($syncdate1);

//$value = 47;
//get all courses of LNACAD
global $DB;
$concourses = $DB->get_records('congrea');
foreach($concourses as $syntocourse) {
	//$coursevalue = $value;
$coursevalue = $syntocourse->course;
$modid = $syntocourse->id;
$modval = 23;

$checkcourse = $DB->get_record('course', array('id' => $coursevalue));
//get cmid
$value = $checkcourse->id;
if ($value >= $value1 and $value <= $value2) {
	$getcmid = $DB->get_record('course_modules', array('course' => $coursevalue, 'module' => $modval, 'instance' => $modid));

	if(!empty($getcmid)) {
		$cm = $getcmid;

		$trainercongreatime[] = get_congrea_details_trainer_todb_again($checkcourse,$cm,'',$syncdate);
	}

}

// $modinfo = get_fast_modinfo($checkcourse);
// 	foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
//
// 		$trainercongreatime[] = get_congrea_details_trainer_todb_again($checkcourse,$cm,'',$syncdate);
//
//
// 	}



}
