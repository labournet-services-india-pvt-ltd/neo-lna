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
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$myreport = has_capability('local/trainer_analysis:myreport',$context);
$PAGE->set_url($CFG->wwwroot . '/local/trainer_analysis/dailyrpt.php');
$title = get_string('title', 'local_trainer_analysis');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
include_once('jslink.php');
echo $OUTPUT->header();
$jslink='';
$type = optional_param('type', '', PARAM_TEXT);
$centername = '';
$batchcode = '';
//student sql last two days
//https://skills.labournet.in/local/trainer_analysis/dailyrpt.php?type=student
echo $type;

$html = site_statistics_content();
echo $html;

if ($type == 'student') {

	$sql1 = "SELECT u.id,username,email,firstname,lastname,ui.data as zone, ml.action as action,
	FROM_UNIXTIME(ml.timecreated) AS days
	FROM {logstore_standard_log} as ml
	JOIN {user} as u ON u.id = ml.userid
	JOIN {user_info_data} AS ui ON ml.userid = ui.userid
	WHERE DATEDIFF( NOW(),FROM_UNIXTIME(ml.timecreated) ) < 2
	AND ui.fieldid = 16
	AND ui.data != ''
	AND (action = 'loggedin' OR action = 'loggedout')
	ORDER BY username,days";

	$sql = "SELECT id,username,email,firstname,lastname,icq
		FROM {user}
		WHERE
    icq = 'Student'
		AND suspended != 1
		ORDER BY username";

	$result = $DB->get_records_sql($sql);
	//for datatable.
	$table  = new \html_table();
	$table->id = 'studenttable';
	$table->head = array(get_string('username'),
		get_string('firstname'),
		get_string('lastname'),
		get_string('email'),
		'Zone',
		get_string('role'),
		'Center Name',
		'Batch Code',
    'Govt id',
		'Course Name',
		'Course code'
	);
	$result = $DB->get_records_sql($sql);
	foreach ($result as $row) {

		require_once($CFG->dirroot.'/user/profile/lib.php');
		$rowuser = $DB->get_record('user', array('id' => $row->id));

		profile_load_data($rowuser);
		$zone = $rowuser->profile_field_zone;
		$centername = $rowuser->profile_field_centername;
		$batchcode = $rowuser->profile_field_batchcode;
		$govtenrolmentid = $rowuser->profile_field_govtenrolmentid;

		$fullname = '';
		$cscode = '';
		$stdcs = get_stuent_courses($row->id);

			$fullname = $stdcs['fullname'];
			$cscode = $stdcs['cscode'];


		$usernamelink = "<a href=$CFG->wwwroot/user/profile.php?id=$row->id> $row->username</a>";
		//find the centername

		//find the bactch code

		$table->data[] = array($usernamelink,$row->firstname,$row->lastname,$row->email,$zone,$row->icq,$centername,$batchcode,$govtenrolmentid,$fullname, $cscode);
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

} else if ($type == 'trainer') {
	//teachers
$sql = "SELECT u.id,username,email,firstname,lastname,u.icq, ml.action as action,
	FROM_UNIXTIME(ml.timecreated) AS days
	FROM {logstore_standard_log} as ml
	JOIN {user} as u ON u.id = ml.userid
	WHERE DATEDIFF( NOW(),FROM_UNIXTIME(ml.timecreated) ) < 2
	AND u.icq = 'Trainer'
	AND u.suspended != 1
	AND (action = 'loggedin' OR action = 'loggedout')
	ORDER BY username,days";

	//for datatable.
	$table  = new \html_table();
	$table->id = 'trainertable';
	$table->head = array(get_string('username'),
		get_string('firstname'),
		get_string('lastname'),
		get_string('email'),
		'Zone',
		get_string('role'),
		get_string('action'),
		get_string('days'),
		'Center Name',
		'Batch Code'
	);
	$result = $DB->get_records_sql($sql);
	foreach ($result as $row) {

		require_once($CFG->dirroot.'/user/profile/lib.php');
		$rowuser = $DB->get_record('user', array('id' => $row->id));

		profile_load_data($rowuser);
		$zone = $rowuser->profile_field_zone;
		$centername = $rowuser->profile_field_centername;
		$batchcode = $rowuser->profile_field_batchcode;
		$usernamelink = "<a href=$CFG->wwwroot/user/profile.php?id=$row->id> $row->username</a>";

		$table->data[] = array($usernamelink,$row->firstname,$row->lastname,$row->email,$zone,$row->icq,$row->action,$row->days,$centername, $batchcode);
		//array($row->username,$row->firstname,$row->lastname,$row->email,$zone,$row->action,$row->days,$centername,$batchcode);
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
}



echo $OUTPUT->footer();
