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
require_login(true);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$myreport = has_capability('local/trainer_analysis:myreport',$context);
$PAGE->set_url($CFG->wwwroot . '/local/trainer_analysis/index.php');
$title = get_string('title', 'local_trainer_analysis');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
include_once('jslink.php');
echo $OUTPUT->header();
$jslink='';
if($myreport){

	//for datatable.
	$table  = new \html_table();
	$table->id = 'usertable';
	$table->head = array('Name',
		get_string('email'),
		get_string('courses', 'local_trainer_analysis'),
	//	get_string('shortname'),
		get_string('role'),
		get_string('batch','local_trainer_analysis'),
		get_string('countstudent','local_trainer_analysis')
	);
	$sql = "SELECT id FROM {user} WHERE icq = 'Student' OR icq = 'Trainer'";
	$trainers = $DB->get_records_sql($sql);
	foreach ($trainers as $trainer) {
		$userid =$trainer->id;
		$user = $DB->get_record('user',array('id'=>$userid));
		$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
		$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
		$useremail = $user->email;
		$trainerrole = $user->icq;
		//$cdetails = get_trainer_courses($userid);
		// find the batch ids for this trainer and number of students in that batch
		require_once($CFG->dirroot.'/user/lib.php');
		require_once($CFG->dirroot.'/course/lib.php');

		$courses='';
		$courseshortname='';
		$countgroupmembers = '';
		$groupid = '';
		$groupname  = '';
		$cnt=1;
		//get all groups of the trainer
		$getgroups = $DB->get_records('groups_members', array('userid' => $userid));

		if (!empty($getgroups)) {
		foreach ($getgroups as $trainergroup) {
			// fnd course id of this group
			$groupid = $trainergroup->groupid;
			$groupcourse = $DB->get_record('groups', array ('id' => $trainergroup->groupid)); // this will give course id
			$groupname = $groupcourse->name.'-'.$groupid;
			$coursevalue = $groupcourse->courseid;
			$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$coursevalue);
			$cname = $DB->get_field('course', 'fullname', array('id'=>$coursevalue));
			$cshortname = $DB->get_field('course', 'shortname', array('id'=>$coursevalue));
			$clink = '<a href="'.$courselink.'">'.$cname.'</a>';
			//$roleid = 5;
			//$participanttable = new \core_user\participants_table(82, $groupid, $lastaccess, $roleid, $enrolid, $status,
			//		$searchkeywords, $bulkoperations, $selectall);
			$countgroupmembers = count(groups_get_members($groupid));
			$countgroupmembers = $countgroupmembers - 1;
			// foreach group
			$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$countgroupmembers);
		}
	} else { // when there is no group for the teacher.
		$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$countgroupmembers);
	}

	} // end of for each trainer
	$datatable='';
	$datatable .= html_writer::start_div('container-fluid pt-5');
	$datatable .= html_writer::start_div('row');
	$datatable .= html_writer::start_div('col-md-12 col-sm-12 col-xs-12');
	$datatable .= html_writer::table($table);
	$datatable .= html_writer::end_div();
	$datatable .= html_writer::end_div();//end row
	$datatable .= html_writer::end_div();//end container
	echo $datatable;
}else{
	$error='';
	$error .= html_writer::start_div('container-fluid text-center');
	$error .= html_writer::start_div('row');
	$error .= html_writer::start_div('col-md-12 text-danger');
	$error .= get_string('accessdenied','local_trainer_analysis');
	$error .= html_writer::end_div();
	$error .= html_writer::end_div();//end row
	$error .= html_writer::end_div();//end container
	echo $error;
}
echo $OUTPUT->footer();
