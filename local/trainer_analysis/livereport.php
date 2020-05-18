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
$PAGE->set_url($CFG->wwwroot . '/local/trainer_analysis/livereport.php');
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
	$html.='<div class="container trainer-analysis">
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
	echo $html;
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
	$data .= html_writer::start_div('container pt-5 text-center');
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

	//for datatable.
	$table  = new \html_table();
	$table->id = 'usertable';
	$table->head = array(get_string('trainername', 'local_trainer_analysis'),
		get_string('email'),
		get_string('courses', 'local_trainer_analysis'),
	//	get_string('shortname'),
		get_string('role'),
		get_string('batch','local_trainer_analysis'),
		get_string('liveclassname','local_trainer_analysis'),
		get_string('timespent','local_trainer_analysis'),
		get_string('lastsessdate','local_trainer_analysis'),
		get_string('countstudent','local_trainer_analysis')
	);
	//change approach
	require_once($CFG->dirroot.'/user/lib.php');
	require_once($CFG->dirroot.'/course/lib.php');
	global $DB, $CFG;
	// LN academy category is 84
	$lnacad = 84;
	$courses_in_category = get_courses($lnacad, 'c.sortorder ASC', 'c.fullname, c.id');
	foreach ($courses_in_category as $key => $value) {
		$coursevalue = $value->id;
		if ($coursevalue != 82) {
			continue;
		}
		$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$coursevalue);
		$cname = $DB->get_field('course', 'fullname', array('id'=>$coursevalue));
		$cshortname = $DB->get_field('course', 'shortname', array('id'=>$coursevalue));
		$clink = '<a href="'.$courselink.'">'.$cname.'</a>';

		$checkcourse = $DB->get_record('course', array('id' => $coursevalue));
		//get all teachers of this course
		$role = $DB->get_record('role', array('shortname' => 'editingteacher'));
		$coursecontext = context_course::instance($coursevalue);
		$trainers = get_role_users($role->id, $coursecontext);

		if (!empty($trainers)) {
			foreach ($trainers as $trainer) {
				$userid = $trainer->id;
				$user = $DB->get_record('user',array('id'=>$userid));
				$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
				$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
				$useremail = $user->email;
				$trainerrole = $user->icq;
				$trainergroups = groups_get_all_groups($coursevalue,$userid);

				if (!empty($trainergroups)) {
					foreach ($trainergroups as $traingroup) {
						$groupid = $traingroup->id;
						$countgroupmembers = count(groups_get_members($groupid)); //student count
						$countgroupmembers = $countgroupmembers - 1;
						$getgroupcms = get_cm_availability_group($groupid, $checkcourse);
						if (!empty($trainergroups)) {
							foreach($getgroupcms as $cm) {
									$trainercongreatime = get_congrea_details_trainer($checkcourse,$cm,$userid);
									$traintimevalue = $trainercongreatime['timespent'];
									$trainlastsessiondate = $trainercongreatime['lastsessiondate'];
									$cmname = $trainercongreatime['cmname'];
									$cmlinktemp = new moodle_url($CFG->wwwroot.'/mod/congrea/view.php?id='.$cm->id);
									$cmlink = '<a href="'.$cmlinktemp.'">'.$cmname.'</a>';
									$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmlink,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
							}
						} else { //// empty cm
							$cmname = '-';
							$traintimevalue = '-';
							$trainlastsessiondate = '-';
							$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmname,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
						}

					}
				} else { //  empty trainergroup
					$cmname = '-';
					$traintimevalue = '-';
					$trainlastsessiondate = '-';
					$groupname = '-';
					$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmname,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
				}
			}
		} // not empty trainer

	}


/************************************ OLD CODE ************************/

// 	$sql = "SELECT DISTINCT userid FROM {role_assignments} WHERE roleid = 3";
// 	$trainers = $DB->get_records_sql($sql);
// 	foreach ($trainers as $trainer) {
// 		$userid =$trainer->userid;
// 		$user = $DB->get_record('user',array('id'=>$userid));
// 		$profilelink = new moodle_url($CFG->wwwroot.'/user/profile.php?id='.$userid);
// 		$username = '<a href="'.$profilelink.'">'.$user->firstname.' '.$user->lastname.'</a>';
// 		$useremail = $user->email;
// 		$trainerrole = $user->icq;
// 		$cdetails = get_trainer_courses($userid);
// 		// find the batch ids for this trainer and number of students in that batch
// 		require_once($CFG->dirroot.'/user/lib.php');
// 		require_once($CFG->dirroot.'/course/lib.php');
//
// 		$courses='';
// 		$courseshortname='';
// 		$countgroupmembers = '';
// 		$groupid = '';
// 		$groupname  = '';
// 		$cnt=1;
// 		//get all groups of the trainer
// 		$getgroups = $DB->get_records('groups_members', array('userid' => $userid));
//
// 		if (!empty($getgroups)) {
// 		foreach ($getgroups as $trainergroup) {
// 			// fnd course id of this group
// 			$groupid = $trainergroup->groupid;
// 			$groupcourse = $DB->get_record('groups', array ('id' => $trainergroup->groupid)); // this will give course id
// 			$groupname = $groupcourse->name.'-'.$groupid;
// 			$coursevalue = $groupcourse->courseid;
// 			$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$coursevalue);
// 			$cname = $DB->get_field('course', 'fullname', array('id'=>$coursevalue));
// 			$cshortname = $DB->get_field('course', 'shortname', array('id'=>$coursevalue));
// 			$clink = '<a href="'.$courselink.'">'.$cname.'</a>';
// 			$groupdetailpage = new \core_group\output\group_details($groupid);
// 			//$roleid = 5;
// 			//$participanttable = new \core_user\participants_table(82, $groupid, $lastaccess, $roleid, $enrolid, $status,
// 			//		$searchkeywords, $bulkoperations, $selectall);
// 			$countgroupmembers = count(groups_get_members($groupid));
// 			$countgroupmembers = $countgroupmembers - 1;
//
// 			//now find the activities which are having restriction added as this group
// 			//first find modinfo and then cm
// 			//if ($coursevalue == 82) {
// 			$trainercongreatime = [];
// 			$traintimevalue = '';
// 			$trainlastsessiondate = '';
//
// 				$checkcourse = $DB->get_record('course', array('id' => $coursevalue));
// 				$modinfo = get_fast_modinfo($checkcourse);
// 				foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
// 				//	$cmname = $cm->name;
// 					//$lastsesstime = get_congrea_details_trainer_lastsession($checkcourse,$cm,$userid);
// 					//$ci = new \core_availability\info_module($cm);
// 					//$fullinfo = $ci->get_full_information();
// 					$someJSON = $cm->availability;
// 					$someArray = json_decode($someJSON, true);
// 					if (!empty($someArray)) {
// 						foreach ($someArray['c'] as $condition) {
// 							if ($condition['type'] == 'group' AND $condition['id'] == $groupid) {
// 								// pass course, cm and trainer id , it will return total timespent for this trainer in this live class
// 								// adding all timespent from all sessions
// 						//		$trainercongreatime[] = get_congrea_details_trainer($checkcourse,$cm,$userid);
//
// 							}
// 						}
// 					} // someArray
// 					// we want to repeat the row for each cm
// 				} //foreach cm
//
// 			//} // if 82 temporary
//
// 			if (!empty($trainercongreatime)) {
// 				foreach ($trainercongreatime as $key => $traintime) {
// 					$traintimevalue = $traintime['timespent'];
// 					$trainlastsessiondate = $traintime['lastsessiondate'];
// 					$cmname = $traintime['cmname'];
// 					$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmname,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
// 				}
// 			} else {
// 				//this is needed as at times group set up restriction may not have been added yet.
// 				$traintimevalue = '';
// 				$trainlastsessiondate = '';
// 				$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmname,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
// 			}
//
// 		} // foreach group
// 	} else { // when there is no group for the teacher.
// 		$traintimevalue = '';
// 		$trainlastsessiondate = '';
// 		$table->data[] = array($username,$useremail,$clink,$trainerrole,$groupname,$cmname,$traintimevalue,$trainlastsessiondate,$countgroupmembers);
// 	}
// /*
// 		foreach ($cdetails as $value) {
// 			$courselink = new moodle_url($CFG->wwwroot.'/course/view.php?id='.$value);
// 			//get course name
// 			$cname = $DB->get_field('course', 'fullname', array('id'=>$value));
// 			$cshortname = $DB->get_field('course', 'shortname', array('id'=>$value));
// 			$clink = '<a href="'.$courselink.'">'.$cname.'</a>';
// 			// find the groups of this course
// 			$groupcourses = $DB->get_record('groups', array ('courseid' => $value)); // this will give course id
// 			//$groupcourses = groups_get_user_groups($value,$userid); // this will give all groups of this user for this course
//
// 			foreach( $groupcourses as $groupsingle) {
// 				$groupid = $groupsingle->id;
// 				// check if
// 				if (groups_is_member($groupid,$userid)) {
// 					$countgroupmembers = count(groups_get_members($groupid));
// 				}
// 				$table->data[] = array($username,$clink,$courseshortname,$trainerrole,$countgroupmembers);
// 			}
//
// 		}
// */

//	} // end of for each trainer
/********************* END OF OLD CODE *********************/

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
