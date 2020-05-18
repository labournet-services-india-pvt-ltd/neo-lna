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
 * @package    local_user_cr_email
 * @copyright  Manjunath B K<manjunathbk@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/lib/coursecatlib.php');
require_once($CFG->dirroot . '/lib/completionlib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/local/customws/lib.php');
global $DB ;
require_login(true);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/customws/view.php');
$title = get_string('downloadreport', 'local_customws');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/custom.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/jquery.dataTables.js'), true);
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/customws/js/jquery.dataTables.css'), true);

$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/jquery.dataTables.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/dataTables.buttons.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/buttons.flash.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/jszip.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/pdfmake.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/vfs_fonts.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/buttons.html5.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/customws/js/buttons.print.min.js'), true);
echo $OUTPUT->header();
$result = get_ws_user_info(null,null);

//	'Overall 50% Lesson completion date',
//	'Overall 100% Lesson completion date'

$pdftable  = new \html_table();
$pdftable->id = 'excel_download';
$pdftable->head = array('Candidate Name',
	'Candidate ID',
	'Date Candidate was given access',
	'Date Candidate first video access',
	'Date Candidate last logged in',
	'Candidate Email',
	'Candidate Phone number',
	'Candidate WhatsApp number',
	'Course name / ID',
	'Number of Lessons in the course',
	'Number of Lessons completed',
	'ID of Last Lesson completed',
	'Video name of Last Lesson completed',
	'Video name of Last Lesson viewed',
	'Number of assessments in the course',
	'Number of assessments completed in the course',
	'ID of Last assessment completed',
	'SerialNumber of Last assessment attempted',
	'SerialNumber of Next Lesson',
	'SerialNumber of Next assessment',
	'ID of Next Lesson',
	'ID of Next assessment',
	'Overall % of Lessons completed',
	'Overall % of assessments completed',
	'>50% Lesson Completion Date',
	'100% Lesson Completion Date'

);
foreach ($result as $jvalue) {
	$user = $DB->get_record('user',array('id'=>$jvalue['user_id']));
	$userid =$user->id;
	$courseid = $jvalue['class_id'];
	$course = $DB->get_record('course',array('id' =>$courseid));

	//Mihir just check that this report is only for Kalai Braining groom team
	if (($course->id == 30) || ($course->id == 32) || ($course->id == 29)) {

	} else {
		continue;
	}

	$lastlogsql="SELECT * FROM {logstore_standard_log} WHERE userid =$userid AND action LIKE '%loggedin%' ORDER BY timecreated DESC LIMIT 1";
	$last = $DB->get_record_sql($lastlogsql);
	$lastloggedin = date('d-m-Y',$last->timecreated);

	//now find the first module viewed date
	//	\mod_page\event\course_module_viewed
	if ($courseid == 29) { $contextinstanceid = 613;}
	if ($courseid == 30) { $contextinstanceid = 612;}
	if ($courseid == 32) { $contextinstanceid = 746;}

	$firstlessonaccess="SELECT * FROM {logstore_standard_log}
	WHERE userid = $userid
	AND eventname LIKE '%course_module_viewed%'
	AND component = 'mod_page'
	AND action LIKE '%viewed%'
	AND contextinstanceid = $contextinstanceid
	AND courseid = $courseid
	ORDER BY timecreated DESC LIMIT 1";

	$firstlessonaccessres = $DB->get_record_sql($firstlessonaccess);
	if (!empty($firstlessonaccessres)) {
			$firstlessonaccessresult = date('d-m-Y',$firstlessonaccessres->timecreated);
	} else {
			$firstlessonaccessresult = '-';
	}



	//Date Candidate was given access
	$givenacc="SELECT ue.timecreated FROM {user_enrolments} ue
	JOIN {enrol} e on e.id = ue.enrolid
	WHERE ue.userid =$userid AND e.courseid=$courseid";
	$givnaccess = $DB->get_record_sql($givenacc);
	if(!empty($givnaccess)){
		$givenaccess = date('d-m-Y',$givnaccess->timecreated);
	}else{
		$givenaccess='-';
	}
	//video course name.
	$assessmentcount=0;
	$quizmodid=$DB->get_field('modules','id', array('name'=>'quiz'));
	$list = get_array_of_activities($courseid);
	foreach ($list as $acvalue) {
		if($jvalue['last_sess_completed'] == $acvalue->cm){
			$lastvideo=$acvalue->name;
		}
		if($acvalue->module == $quizmodid){
			//Number of assessments in the course.
			$assessmentcount++;
		}
	}
//Number of assessments completed in the course.
	// Get course completion data.
	$info = new completion_info($course);
    // Load criteria to display.
	$completions = $info->get_completions($userid);
    // Check this user is enroled.
	$assessmentscomplete=0;
	$activities=[];
	if ($info->is_tracked_user($userid)) {
    // Loop through course criteria.
		foreach ($completions as $completion) {
			$criteria = $completion->get_criteria();
			$complete = $completion->is_complete();
			if ($criteria->criteriatype == COMPLETION_CRITERIA_TYPE_ACTIVITY && $criteria->module =='quiz') {
				$activities[$criteria->moduleinstance] = $complete;

				if ($complete) {
					$assessmentscomplete++;
				}
			}
		}
	}
	//Last completed assessment.
	$lasstasses="SELECT * FROM {course_modules_completion} cmc
	JOIN {course_modules} cm ON cm.id = cmc.coursemoduleid
	WHERE cmc.userid =$userid AND cm.course=$courseid AND cm.module=$quizmodid
	ORDER BY cmc.timemodified DESC LIMIT 1";
	$lastres = $DB->get_record_sql($lasstasses);
	if(!empty($lastres)){
		$lastassessmentid = $lastres->id;
	}else{
		$lastassessmentid ='-';
	}
	//Id of the next assessment.
	$completecmid=$jvalue['last_sess_completed'];
	$gettestsql = "SELECT id,instance from {course_modules} WHERE module = $quizmodid AND idnumber LIKE '%$completecmid%'";
	$gettestsqlresult = $DB->get_record_sql($gettestsql);
	if(!empty($gettestsqlresult)){
		$nextassessmentid=$gettestsqlresult->id;
	}else{
		$nextassessmentid='-';
	}
	//Percentage assessment completed.
	$perassessmntcmplt='';
	if(!empty($assessmentcount)){
		if(!empty($assessmentscomplete)){
			$perassessmntcmplt=(100 * $assessmentscomplete)/$assessmentcount;
		}else{
			$perassessmntcmplt=0;
		}
	}else{
		$perassessmntcmplt=0;
	}

// get_exact_session_fifty($courseid, $userid){ find the exact date for 50% and 100% completion of session
	$getfifty_hundred_date = get_exact_session_fifty($courseid, $userid);
	$getfifty_hundred_date1 = get_exact_session_hundred($courseid, $userid);

	$fifty_date = $getfifty_hundred_date['fifty_date'];
	$hundred_Date = $getfifty_hundred_date1['hundred_Date'];


	//Table creation starts here.
	$pdftable->data[] = array(
		fullname($user),
		$jvalue['user_id'],
		$givenaccess,
		$firstlessonaccessresult,
		$lastloggedin,
		$user->email,
		$jvalue['mobile_no'],
		$jvalue['mobile_no'],
		$jvalue['class_name'],
		$jvalue['count_total'],
		$jvalue['count_total_completed'],
		$jvalue['last_sess_completed'],
		$lastvideo,
		$lastvideo,
		$assessmentcount,
		$assessmentscomplete,
		$lastassessmentid,
		'-',
		'-',
		'-',
		$jvalue['next_session_no'],
		$nextassessmentid,
		$jvalue['percentage_completed'],
		$perassessmntcmplt,
		$fifty_date,
		$hundred_Date
	);
}

//$fifty_date,
//$hundred_Date

$data='';
$data .= html_writer::start_div('container-fluid');
$data .= html_writer::start_div('row');
$data .= html_writer::start_div('col-md-12');
$data .= html_writer::table($pdftable);
$data .= html_writer::end_div();
$data .= html_writer::end_div();
$data .= html_writer::end_div();
echo $data;
echo $OUTPUT->footer();
