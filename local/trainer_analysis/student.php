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

$syncdate = optional_param('date', '', PARAM_RAW); // Course_module ID.
$session = optional_param('session', '', PARAM_RAW); // Course_module ID.
$groupid = optional_param('group', 0, PARAM_INT); // Course_module ID.


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$myreport = has_capability('local/trainer_analysis:myreport',$context);
$PAGE->set_url($CFG->wwwroot . '/local/trainer_analysis/student.php');
$title = 'Student Attendance Report';
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
include_once('jslink.php');
echo $OUTPUT->header();
$jslink='';
if($myreport){


//	$syncdate = strtotime("today-1day");
	$syncdate = strtotime($syncdate);


	$todayurl = new moodle_url($CFG->wwwwroot.'/local/trainer_analysis/index.php?date='.$syncdate);
	$button = '<a class="btn btn-primary" href="'.$todayurl.'">'.'Click to View report to Today'.'</a>';
	//echo $button;
//	echo '<hr>';
//	echo '<h3> Select a date to see the Trainer Report. </h3>';

$cformaction = $CFG->wwwwroot.'/local/trainer_analysis/index.php';
$cform = '<form action="'.$cformaction.'">
  <label for="date">Select Date:</label>
  <input type="date" id="date" name="date">
  <input type="submit" value="Submit">
</form>';

//	echo $cform;
	echo '<hr>';

//		get_string('countstudent','local_trainer_analysis'),
	//for datatable.
	$table  = new \html_table();
	$table->id = 'usertable';
	$table->head = array(
		'Si.',
		'Center',
		'Client',
		get_string('courses', 'local_trainer_analysis'),
		get_string('batch','local_trainer_analysis'),
		'Name',
		'Enrolment no',

	//	get_string('email'),

	//	get_string('shortname'),
	//	get_string('role'),

		'Login Time',
		'Logout Time',
		'Duration'

	);
	$dateformat = get_string('strftimedatetime', 'langconfig');
	$si = 0;

// first get just one student for this session
	$onesql = "SELECT * FROM {local_trainer_liveclasstime} WHERE sessionid = '$session' AND userrole = 'Student' LIMIT 0,1";
	$onestudent = $DB->get_record_sql($onesql);

	if (!empty($onestudent)) {
		$oneuserid = $onestudent->userid;
		$getgroup = $DB->get_record('groups_members', array('userid'=>$oneuserid));
		$getallgroupmembers = $DB->get_records('groups_members', array ('groupid' => $getgroup->groupid)); // this will give course id

		$groupcourse = $DB->get_record('groups', array ('id' => $getgroup->groupid)); // this will give course id
		$groupname = $groupcourse->name.'-'.$groupid;

		foreach ($getallgroupmembers as $actualgroupusers) {
			$si = $si +1;

			$student = $DB->get_record('local_trainer_liveclasstime', array ('sessionid' => $session , 'userrole' => 'Student', 'userid' => $actualgroupusers->userid));


			if (!empty($student)) {
				$userid =$student->userid;
				$user = $DB->get_record('user',array('id'=>$userid));
				$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
				$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
				$useremail = $user->email;

				$userenrolmentno = '-'; //need to get govt enrolment id

				$trainerrole = $user->icq;
				$traintimevalue = 0;


						$cmhere = $DB->get_record('course_modules', array('id'=>$student->cmid));

						$congrea = $DB->get_record('congrea', array('id' => $cmhere->instance), '*', MUST_EXIST);
						$cmname = $congrea->name;
						$cname = $DB->get_field('course', 'fullname', array('id'=>$cmhere->course));

						$coursecontext = context_course::instance($cmhere->course);
						$countcoursemembers = count(get_enrolled_users($coursecontext));

						$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$cmhere->course);
						$clink = '<a href="'.$courselink.'">'.$cname.'</a>';
						$traintimevalue = $student->duration;

						// $starttime = $getval->starttime;
						// $endttime = $getval->endtime;
						// $duration = $getval->duration;

						$trainlastsessiondate = userdate($student->starttime,$dateformat);
						$trainlastsessionenddate = userdate($student->endtime,$dateformat);

						$traintimevalue = ($student->endtime - $student->starttime) / 60 ;
						$traintimevalue = round($traintimevalue);

									//$countcoursemembers,
								if ($traintimevalue > 2)  { // minimum 2 minutes

									$table->data[] = array(
										$si,
										'-',
										'-',
										$clink,
										$groupname,
										$username,
										$userenrolmentno,
						//				$useremail,
						//				$trainerrole,

									$trainlastsessiondate,
									$trainlastsessionenddate,
									$traintimevalue,

									);
								}

			}  else { // if clause

				$userid =$actualgroupusers->userid;
				$user = $DB->get_record('user',array('id'=>$userid));
				$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
				$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
				$useremail = $user->email;

				$userenrolmentno = '-'; //need to get govt enrolment id

				$table->data[] = array(
					$si,
					'-',
					'-',
					$clink,
					$groupname,
					$username,
					$userenrolmentno,
	//				$useremail,
	//				$trainerrole,

				'-',
				'-',
				'Absent',

				);

			}

		}
	}


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
