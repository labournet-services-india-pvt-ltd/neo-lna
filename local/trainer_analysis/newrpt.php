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
require_once('statisticspage.php');
global $DB, $CFG, $USER;
require_login(true);

$syncdate1 = optional_param('date', '', PARAM_RAW); // Course_module ID.
//$syncdate1 = optional_param('date1', 0, PARAM_); // Course_module ID.


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
	$totaltrainers = total_trainer();
	$coursetrainers = course_trainers();
	$toploggedintrainer= top_loggedin_trainers();
	$toptrainers =top_trainers();
	$html='';
	$html.='<div class="container-fluid trainer-analysis">
	<div class="row">
	<div class="col-md-4 col-sm-4 col-xs-12 text-center">
	<div class="card text-white bg-success mb-3 rounded">
	<div class="card-body">
	<h1 class="card-title">'.$totaltrainers.'</h1>
	<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
	</div>
	<div class="card-header"><h4>'.get_string('trainerinpltform','local_trainer_analysis').'</h4></div>
	</div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 text-center">
	<div class="card text-white bg-warning mb-3 rounded" >
	<div class="card-body">
	<h1 class="card-title">'.$coursetrainers['coursewithtrainer'].'</h1>
	<i class="fa fa-address-book" aria-hidden="true"></i>
	</div>
	<div class="card-header"><h4>'.get_string('coursewithtrainer','local_trainer_analysis').'</h4></div>
	</div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 text-center">
	<div class="card text-white bg-info mb-3 rounded" >
	<div class="card-body">
	<h1 class="card-title">'.$coursetrainers['coursewithottrainer'].'</h1>
	<i class="fa fa-book" aria-hidden="true"></i>
	</div>
	<div class="card-header"><h4>'.get_string('coursewithouttrainer','local_trainer_analysis').'</h4></div>
	</div>
	</div>
	</div>
	</div>';
	//echo $html;  //this is no longer needed

//for top 5 trainers.
	$toptrainer  = new \html_table();
	$toptrainer->id = 'topfivetrainer';
	$toptrainer->head = array(get_string('slno', 'local_trainer_analysis'),
		get_string('trainername', 'local_trainer_analysis'),
		get_string('coursesteaching', 'local_trainer_analysis')
	);
	$counter=1;
	foreach ($toptrainers as $userid => $coursecount) {
		$user = $DB->get_record('user',array('id'=>$userid));
		$toptrainer->data[] = array($counter,$user->firstname.' '.$user->lastname,$coursecount);
		$counter++;
	}
//for top 5 logged in trainers.
	$toplogtrainer  = new \html_table();
	$toplogtrainer->id = 'toplogtrainer';
	$toplogtrainer->head = array(get_string('slno', 'local_trainer_analysis'),
		get_string('trainername', 'local_trainer_analysis'),
		get_string('nooflogin', 'local_trainer_analysis')
	);
	$count=1;
	foreach ($toploggedintrainer as $userid => $logcount) {
		$user = $DB->get_record('user',array('id'=>$userid));
		$toplogtrainer->data[] = array($count,$user->firstname.' '.$user->lastname,$logcount);
		$count++;
	}
	$data ='';
	$data .= html_writer::start_div('container-fluid pt-5 text-center');
	$data .= html_writer::start_div('row');
	$data .= html_writer::start_div('col-md-6 col-sm-12 col-xs-12 text-center');
	$data .= html_writer::start_tag('h3');
	$data .=get_string('toptrainer','local_trainer_analysis');
	$data .= html_writer::end_tag('h3');
	$data .= html_writer::table($toptrainer);
	$data .= html_writer::end_div();
	$data .= html_writer::start_div('col-md-6 col-sm-12 col-xs-12 text-center');
	$data .= html_writer::start_tag('h3');
	$data .=get_string('maxlogtrainer','local_trainer_analysis');
	$data .= html_writer::end_tag('h3');
	$data .= html_writer::table($toplogtrainer);
	$data .= html_writer::end_div();
	$data .= html_writer::end_div();//end row
	$data .= html_writer::end_div();//end container
	echo $data;


	$html = site_statistics_content();
	echo $html;

// LIVE classes information

//	$syncdate = strtotime("today-1day");
	$syncdate = strtotime($syncdate1);

	echo '<hr>';
	echo '<h3> Select a date to Sync the data first. </h3>';

$cformaction1 = $CFG->wwwwroot.'/local/trainer_analysis/sessioncurl.php';
$cform1 = '<form action="'.$cformaction1.'">
	<label for="date">Select Date:</label>
	<input type="date" id="date" name="date">
	<input type="submit" value="Submit">
</form>';

	echo $cform1;
	echo '<hr>';
	echo '<br>';


	$todayurl = new moodle_url($CFG->wwwwroot.'/local/trainer_analysis/newrpt.php?date='.$syncdate);
	$button = '<a class="btn btn-primary" href="'.$todayurl.'">'.'Click to View report to Today'.'</a>';
	//echo $button;

	echo '<hr>';
	echo '<h3> Select a date to see the Report. </h3>';

$cformaction = $CFG->wwwwroot.'/local/trainer_analysis/newrpt.php';
$cform = '<form action="'.$cformaction.'">
  <label for="date">Select Date:</label>
  <input type="date" id="date" name="date">
  <input type="submit" value="Submit">
</form>';

	echo $cform;
	echo '<hr>';

//		get_string('countstudent','local_trainer_analysis'),
	//for datatable.
	$table  = new \html_table();
	$table->id = 'usertable';
	$table->head = array(
		'Si.',
		'Center',
		'Client',
		'Course Name',
		get_string('batch','local_trainer_analysis'),
		'Name',

	//	get_string('email'),

	//	get_string('shortname'),
		get_string('role'),

		get_string('liveclassname','local_trainer_analysis'),
		'Start Time',
	//	get_string('lastsessdate','local_trainer_analysis'),
		'End time',
		get_string('timespent','local_trainer_analysis'),
		get_string('countstudentingroup','local_trainer_analysis'),
		get_string('recordinglink','local_trainer_analysis'),
		get_string('studentattendancelink','local_trainer_analysis')
	);
	$dateformat = get_string('strftimedatetime', 'langconfig');
	$sql = "SELECT DISTINCT userid FROM {role_assignments} WHERE roleid = 3";

	$si = 0;

	$trainers = $DB->get_records_sql($sql);


		$traintimevalue = 0;
		//get timespend data table local table
		//echo $syncdate;
		if (!empty($syncdate)) {
			//get only todays data
			//$gettimesql = "Select * from {local_trainer_liveclasstime} where userid= $userid and starttime > $syncdate";
			$gettimesql = "Select * from {local_trainer_liveclasstime} where starttime > $syncdate";
			//$gettimesql = "Select * from {local_trainer_liveclasstime} where userid= ? and starttime = ?";
			$gettimedata = $DB->get_records_sql($gettimesql);
			//$gettimedata = $DB->get_records_sql($gettimesql, array($userid,$syncdate));
		} else {
			//$gettimedata = $DB->get_records('local_trainer_liveclasstime', array('userid'=>$userid));
		}


		if (!empty($gettimedata)) {
			foreach ($gettimedata as $getval) {


				$userid =$getval->userid;
				$user = $DB->get_record('user',array('id'=>$userid));
				$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
				$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
				$useremail = $user->email;
				$trainerrole = $user->icq;

				$cmhere = $DB->get_record('course_modules', array('id'=>$getval->cmid));

				$congrea = $DB->get_record('congrea', array('id' => $cmhere->instance), '*', MUST_EXIST);
				$cmname = $congrea->name;
				$cname = $DB->get_field('course', 'fullname', array('id'=>$cmhere->course));
				$coursecontext = context_course::instance($cmhere->course);
				$countcoursemembers = count(get_enrolled_users($coursecontext));

				$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$cmhere->course);
				$clink = '<a href="'.$courselink.'">'.$cname.'</a>';

				$traintimevalue = $getval->duration;

				// $starttime = $getval->starttime;
				// $endttime = $getval->endtime;
				// $duration = $getval->duration;

				$trainlastsessiondate = userdate($getval->starttime,$dateformat);
				$trainlastsessionenddate = userdate($getval->endtime,$dateformat);

				$traintimevalue = ($getval->endtime - $getval->starttime) / 60 ;
				$traintimevalue = round($traintimevalue);

				//https://skills.labournet.in/mod/congrea/view.php?id=1521&psession=133
				$recordinglink = '';

				$studentattendancelink = '';


				$recordinglink1 = new moodle_url($CFG->wwwroot.'/mod/congrea/view.php?id='.$getval->cmid.'&psession='.$congrea->id);
				$recordinglink = '<a href="'.$recordinglink1.'">Recording</a>';

				//https://skills.labournet.in/mod/congrea/view.php?id=949&psession=1&session=6dac7fb9-7898-4b5b-baec-5fd2872973ed
				//$studentattendancelink1 = new moodle_url($CFG->wwwroot.'/mod/congrea/view.php?id='.$getval->cmid.'&psession=1&session='.$getval->sessionid);

				$studentattendancelink1 = new moodle_url($CFG->wwwroot.'/local/trainer_analysis/student.php?session='.$getval->sessionid);
				$studentattendancelink = '<a href="'.$studentattendancelink1.'">Attendance</a>';

				$someJSON = $cmhere->availability;
								$someArray = json_decode($someJSON, true);
								if (!empty($someArray)) {
									foreach ($someArray['c'] as $condition) {
										if ($condition['type'] == 'group') {
											$groupid = $condition['id'];
											$countgroupmembers = count(groups_get_members($groupid));
											$countgroupmembers = $countgroupmembers - 1;

											$groupcourse = $DB->get_record('groups', array ('id' => $groupid)); // this will give course id
											$groupname = $groupcourse->name.'-'.$groupid;

									}
								}
							}
							//$countcoursemembers,
						if ($traintimevalue > 2)  { // minimum 2 minutes
							$si = $si + 1;
							$table->data[] = array(
								$si,
								'-',
								'-',
								$clink,
								$groupname,
								$username,
				//				$useremail,
								$trainerrole,
								$cmname,
							$trainlastsessiondate,
							$trainlastsessionenddate,
							$traintimevalue,
							$countgroupmembers,
							$recordinglink,
							$studentattendancelink
							);
						}
					}
		} else {
					$clink = '';
					$groupname = '';
					$cmname = '';
					$traintimevalue = '';
					$trainlastsessiondate = '';
					$trainlastsessionenddate = '';
					$countgroupmembers = '';
					$si = $si + 1;
					$table->data[] = array(
						$si,
						'-',
						'-',
						$clink,
						$groupname,
						$username,
		//				$useremail,
		//				$trainerrole,
						$cmname,
					$trainlastsessiondate,
					$trainlastsessionenddate,
					$traintimevalue,
					$countgroupmembers,
					$recordinglink,
					$studentattendancelink

				);

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
