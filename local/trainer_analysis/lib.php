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
//manju: this function will return total number of trainers in moodle.
function total_trainer(){
	global $DB,$CFG;
	$sql = "SELECT DISTINCT userid FROM {role_assignments} WHERE roleid = 3";
	$trainers = count($DB->get_records_sql($sql));
	if(!empty($trainers)){
		return $trainers;
	}else{
		return "-";
	}
}

//manju: this function will returns course with trainers and courses without trainers.
function  course_trainers(){
	global $DB,$CFG;
	$cwithtrainer =0;
	$cwithottrainer =0;
	$sql="SELECT c.fullname AS Course
	,(SELECT COUNT( ra.userid ) AS Users FROM {role_assignments} AS ra
	JOIN {context} AS ctx ON ra.contextid = ctx.id
	WHERE ra.roleid = 3 AND ctx.instanceid = c.id) AS Teachers
	FROM {course} AS c
	ORDER BY Teachers ASC";
	$trainers = $DB->get_records_sql($sql);
	foreach ($trainers as  $trainer) {
		if($trainer->teachers > 0){
			$cwithtrainer++;
		}else{
			$cwithottrainer++;
		}
	}
	$returnarray = array('coursewithtrainer'=>$cwithtrainer,
		'coursewithottrainer'=>$cwithottrainer);
	return $returnarray;
}

//manju:this function will returns top 5 loggedin trainers.
function top_loggedin_trainers(){
	global $DB,$CFG;
	$returnarray=[];
	$trainers = $DB->get_records('role_assignments',array('roleid'=>3));
	foreach ($trainers as $trainer) {
		$loggincount = count($DB->get_records('logstore_standard_log',array('action'=>'loggedin','userid'=>$trainer->userid)));
		$returnarray[$trainer->userid]=$loggincount;
	}
	arsort($returnarray);
	$returnarray = array_slice($returnarray, 0, 5, true);
	return $returnarray;
}

//manju:this function will returns top 5 trainers based on the courses they teaching.
function top_trainers(){
	global $DB,$CFG;
	$returnarray =[];
	$trainers = $DB->get_records('role_assignments',array('roleid'=>3));
	foreach ($trainers as $trainer) {
		$userid = $trainer->userid;
		$sql="SELECT ra.id as assignid,c.id AS CourseID, c.fullname AS Course, ra.roleid AS RoleID, u.firstname AS Teacher,u.id as userid
		FROM {course} AS c
		JOIN {context} AS ctx ON c.id = ctx.instanceid AND ctx.contextlevel = 50
		JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
		JOIN {user} AS u ON u.id = ra.userid
		JOIN {course_categories} AS cc ON cc.id = c.category
		WHERE  ra.roleid = 3 AND ra.userid = $userid";
		$coursecount = count($DB->get_records_sql($sql));
		$returnarray[$userid]=$coursecount;
	}
	arsort($returnarray);
	$returnarray = array_slice($returnarray, 0, 5, true);
	return $returnarray;
}

//manju:this function will returns the courses of a trainer.
function get_trainer_courses($userid){
	global $DB,$CFG;
	$returnarray=[];
	$sql="SELECT ra.id as assignid,c.id AS courseid, c.fullname AS Course, c.shortname AS cshortname, ra.roleid AS RoleID, u.firstname AS Teacher,u.id as userid
	FROM {course} AS c
	JOIN {context} AS ctx ON c.id = ctx.instanceid AND ctx.contextlevel = 50
	JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
	JOIN {user} AS u ON u.id = ra.userid
	JOIN {course_categories} AS cc ON cc.id = c.category
	WHERE  ra.roleid = 3 AND ra.userid = $userid";
	$trainercourse = $DB->get_records_sql($sql);
	foreach ($trainercourse as $course) {
		$returnarray[]=$course->courseid;
	}
	return $returnarray;
}
//manju:this function will returns the courses of a trainer.
function get_stuent_courses($userid){
	global $DB,$CFG;
	$returnarray=[];
	$sql="SELECT c.id AS courseid, c.fullname, c.shortname
	FROM {course} AS c
	JOIN {context} AS ctx ON c.id = ctx.instanceid AND ctx.contextlevel = 50
	JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
	JOIN {user} AS u ON u.id = ra.userid
	WHERE  ra.roleid = 5 AND ra.userid = $userid
	AND c.visible = 1 AND c.id !=34";
	$trainercourse = $DB->get_record_sql($sql);
	// foreach ($trainercourse as $course) {
	// 	$returnarray=$course->fullname;
	// }
if ($trainercourse) {
		$returnarrayfullname=$trainercourse->fullname;
		$returnarraycode=$trainercourse->shortname;
return $returnarray = array('fullname' => $returnarrayfullname, 'cscode' => $returnarraycode);
}
return '';

}
/** get students in a course */
/* input is course id  returns just the count */
function get_studentlist_course($courseid){
	global $DB,$CFG;
	$participantsql = "SELECT c.id, u.id
FROM {course} c
JOIN {context} ct ON c.id = ct.instanceid
JOIN {role_assignments} ra ON ra.contextid = ct.id
JOIN {user} u ON u.id = ra.userid
JOIN {role} r ON r.id = ra.roleid
where ra.roleid = 5 AND c.id = ?";
	$result = $DB->get_records_sql($participantsql, array($courseid));
	if(!empty($result)) {
		return count($result);
	}
	return false;
}

/* this to just to get the cm in which we have availability restriction of the group */
function get_cm_availability_group($groupid, $course) {
	global $DB;
	$returncm = array();
	$modinfo = get_fast_modinfo($course);
	foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
	//	$cmname = $cm->name;
		$someJSON = $cm->availability;
		$someArray = json_decode($someJSON, true);
		if (!empty($someArray)) {
			foreach ($someArray['c'] as $condition) {
				if ($condition['type'] == 'group' AND $condition['id'] == $groupid) {
					$returncm[] = $cm;
				}
			}
		} // someArray
		// we want to repeat the row for each cm
	} //foreach cm
	return $returncm;
}

/* to get congrea details based on cm */
function get_congrea_details_trainer($course,$cm, $trainerid) {
	global $CFG,$DB, $USER;
	require_once($CFG->dirroot.'/mod/congrea/lib.php');
	require_once($CFG->dirroot.'/mod/congrea/locallib.php');
	//require_once($CFG->dirroot.'/mod/congrea/auth.php');

	// we will send cm name from here..
	$cmname = $cm->name;

	$congrea = $DB->get_record('congrea', array('id' => $cm->instance), '*', MUST_EXIST);
	$key = get_config('mod_congrea', 'cgapi');
	$secret = get_config('mod_congrea', 'cgsecretpassword');
	$cgapi = get_config('mod_congrea', 'cgapi');
	$cgsecret = get_config('mod_congrea', 'cgsecretpassword');
	$recordingstatus = true;
	$role = 't'; // this is very important Mihir
	 if (strlen($cgsecret) >= 64 && strlen($cgapi) > 32) {
	 		require_once($CFG->dirroot.'/mod/congrea/auth.php');
	 }
	 $fromcms = true; // Identify congrea is from cms.
	// Get congrea api key and Secret key from congrea setting.

	$context = context_module::instance($cm->id);
	has_capability('mod/congrea:attendance', $context);

	$room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;
	$postdata = json_encode(array('room' => $room));
	$result = curl_request("https://api.congrea.net/backend/recordings", $postdata, $key, $secret);
	if (!empty($result)) {
	    $data1 = json_decode($result);
	    $recording = json_decode($data1->data);
	}
	$timespent = 0; // initialising
	rsort($recording->Items);
	$sessiondate = [];
	foreach ($recording->Items as $record) {


		$vcsid = $record->key_room;
		$session = $record->session;
	//	echo $lastsessiontime;
		$apiurl = 'https://api.congrea.net/t/analytics/attendance';
    $data = attendence_curl_request($apiurl, $session, $key, $authpassword, $authusername, $room,false); // TODO.
    $attendencestatus = json_decode($data);

		//for better code will try later
		// $uid = 423;
		// $apiurl = 'https://api.congrea.net/data/analytics/attendance';
		// $data2 = attendence_curl_request($apiurl, $record->session, $key, $authpassword, $authusername, $room, 423);
		// $attendencestatus2 = json_decode($data2);
		// print_object($attendencestatus2);

		$sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.

		if (!empty($attendencestatus) and ! empty($sessionstatus)) {
			// session date
				$sessiondate[] = userdate($record->time / 1000); // Todo. this is an array and we should return only the last date so far.

        foreach ($attendencestatus->attendance as $sattendence) {
				//	$studentname = $DB->get_record('user', array('id' => $sattendence->uid));
					if ($sattendence->uid == $trainerid) {
						$connect = json_decode($sattendence->connect);
						$disconnect = json_decode($sattendence->disconnect);
						$studentsstatus = calctime($connect, $disconnect, $sessionstatus->sessionstarttime, $sessionstatus->sessionendtime);
						if (!empty($studentsstatus->totalspenttime) and
										$sessionstatus->totalsessiontime >= $studentsstatus->totalspenttime) {
							echo $userid.'-'.$studentsstatus->totalspenttime.'-'.userdate($record->time / 1000);
							echo '<br>';
							$timespent =  $studentsstatus->totalspenttime + $timespent;
						}
					}

					// if (!empty($studentsstatus->totalspenttime) and
					// 				$sessionstatus->totalsessiontime >= $studentsstatus->totalspenttime) {
					// 		$presence = ($studentsstatus->totalspenttime * 100) / $sessionstatus->totalsessiontime;
					// } else if ($studentsstatus->totalspenttime > $sessionstatus->totalsessiontime) {
					// 		$presence = 100; // Special case handle.
					// } else {
					// 		$presence = '-';
					// }


				} // foreach
			} // end of if attendaencestatus

	}
	$returnarray = array(
		'timespent' => $timespent,
		'lastsessiondate' => $sessiondate[0],
		'cmname' => $cmname,
	);
	return $returnarray;
	//return $timespent;

}

/* to get congrea details based on cm  this stores results in DB */
function get_congrea_details_trainer_todb($course,$cm, $trainerid=null,$syncdate=null) {
	global $CFG,$DB, $USER;
	require_once($CFG->dirroot.'/mod/congrea/lib.php');
	require_once($CFG->dirroot.'/mod/congrea/locallib.php');
	//require_once($CFG->dirroot.'/mod/congrea/auth.php');

	// we will send cm name from here..
	$cmname = $cm->name;

	$congrea = $DB->get_record('congrea', array('id' => $cm->instance), '*', MUST_EXIST);
	$key = get_config('mod_congrea', 'cgapi');
	$secret = get_config('mod_congrea', 'cgsecretpassword');
	$cgapi = get_config('mod_congrea', 'cgapi');
	$cgsecret = get_config('mod_congrea', 'cgsecretpassword');
	$recordingstatus = true;
	$role = 't'; // this is very important Mihir
	 if (strlen($cgsecret) >= 64 && strlen($cgapi) > 32) {
	 		require_once($CFG->dirroot.'/mod/congrea/auth.php');
	 }
	 $fromcms = true; // Identify congrea is from cms.
	// Get congrea api key and Secret key from congrea setting.

	$context = context_module::instance($cm->id);
	has_capability('mod/congrea:attendance', $context);

	$room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;
	$postdata = json_encode(array('room' => $room));
	$result = curl_request("https://api.congrea.net/backend/recordings", $postdata, $key, $secret);
	if (!empty($result)) {
	    $data1 = json_decode($result);
	    $recording = json_decode($data1->data);
	}
	$timespent = 0; // initialising
	rsort($recording->Items);

	foreach ($recording->Items as $record) {

		$lastsession = $record->time;

		if ( $lastsession > $syncdate) {


		print_object($record->time);
		print_object($record->session);

		$vcsid = $record->key_room;
		$session = $record->session;
	//	echo $lastsessiontime;
		$apiurl = 'https://api.congrea.net/t/analytics/attendance';
    $data = attendence_curl_request($apiurl, $session, $key, $authpassword, $authusername, $room,false); // TODO.
    $attendencestatus = json_decode($data);

		$sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.
		if (!empty($attendencestatus) and ! empty($sessionstatus)) {
			// session date
				$sessiondate = userdate($record->time / 1000); // Todo. this is an array and we should return only the last date so far.
        foreach ($attendencestatus->attendance as $sattendence) {

				print_object($sattendence);
				die();
				//	$studentname = $DB->get_record('user', array('id' => $sattendence->uid));
				//echo $syncdate;

/*
				 if ($syncdate >= $sessionstatus->sessionstarttime  ) { // sync only todays sessions
				 	continue;
				 }
*/
						$connect = json_decode($sattendence->connect);
						$disconnect = json_decode($sattendence->disconnect);
						$studentsstatus = calctime($connect, $disconnect, $sessionstatus->sessionstarttime, $sessionstatus->sessionendtime);

					//if ($sattendence->uid == $trainerid) {
					if (!empty($studentsstatus->totalspenttime)) { // this means user was present

						if (!empty($studentsstatus->totalspenttime) and
										$sessionstatus->totalsessiontime >= $studentsstatus->totalspenttime) {
							$timespent =  $studentsstatus->totalspenttime;
							$presence = ($studentsstatus->totalspenttime * 100) / $sessionstatus->totalsessiontime;

						}else if ($studentsstatus->totalspenttime > $sessionstatus->totalsessiontime) {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = 100; // Special case handle.
						} else {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = '0';
						}

						// write code to insert record in db
						global $DB;
						$recordinsert = new stdClass();
						$recordinsert->userid = $sattendence->uid;
						$recordinsert->cmid = $cm->id;
						$recordinsert->sessionid = $session;
						$recordinsert->starttime = $studentsstatus->starttime; // this should studentstatus
						$recordinsert->endtime = $studentsstatus->endtime; //// this should studentstatus
						$recordinsert->duration = $timespent;
						$recordinsert->presentpercent = $presence;
						$recordinsert->timecreated = time();

						$getudata = $DB->get_record('user', array('id'=>$sattendence->uid));

						$recordinsert->userrole = $getudata->icq;

						// if ($sattendence->uid == $trainerid) {
						// 	$recordinsert->userrole = 'Trainer';
						// } else {
						// 	$recordinsert->userrole = 'Student';
						// }

						// first check if the record is present or not
						//$studentsstatus->starttime
						//$studentsstatus->endtime

						$checkrecord = $DB->get_record('local_trainer_liveclasstime',
							array('userid'=>$sattendence->uid, 'cmid'=>$cm->id, 'starttime' =>$studentsstatus->starttime ));
						if (!empty($checkrecord)) { // record is already present
							global $DB;
							$upduser = new stdClass();
							$upduser->id = $checkrecord->id;
							$upduser->cmid = $cm->id;
							$upduser->userid = $sattendence->uid;
							$upduser->sessionid = $session;
							$upduser->starttime = $studentsstatus->starttime;
							$upduser->endtime = $studentsstatus->endtime;
							$upduser->userrole = $recordinsert->userrole;
							$DB->update_record('local_trainer_liveclasstime', $upduser);
							echo 'updated for'.$sattendence->uid.'-'.$cm->id;

						} else {
							$insert = $DB->insert_record('local_trainer_liveclasstime', $recordinsert);
							if($insert) {
								echo 'inserted for'.$sattendence->uid.'-'.$cm->id;
							}
						}

					} // if of present
				} // foreach
			} // end of if attendaencestatus

} // syncdate

	}

	return true;
	//return $timespent;

}

/* to get congrea details based on cm  this stores results in DB */
function get_congrea_details_trainer_todb_again($course,$cm, $trainerid=null,$syncdate=null) {
	global $CFG,$DB, $USER;
	require_once($CFG->dirroot.'/mod/congrea/lib.php');
	require_once($CFG->dirroot.'/mod/congrea/locallib.php');
	//require_once($CFG->dirroot.'/mod/congrea/auth.php');

	// we will send cm name from here..
	$cmname = $cm->name;

	$congrea = $DB->get_record('congrea', array('id' => $cm->instance), '*', MUST_EXIST);
	$key = get_config('mod_congrea', 'cgapi');
	$secret = get_config('mod_congrea', 'cgsecretpassword');
	$cgapi = get_config('mod_congrea', 'cgapi');
	$cgsecret = get_config('mod_congrea', 'cgsecretpassword');
	$recordingstatus = true;
	$role = 't'; // this is very important Mihir
	 if (strlen($cgsecret) >= 64 && strlen($cgapi) > 32) {
	 		require_once($CFG->dirroot.'/mod/congrea/auth.php');
	 }
	 $fromcms = true; // Identify congrea is from cms.
	// Get congrea api key and Secret key from congrea setting.

	$context = context_module::instance($cm->id);
	has_capability('mod/congrea:attendance', $context);

	$room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;
	$postdata = json_encode(array('room' => $room));
	$result = curl_request("https://api.congrea.net/backend/recordings", $postdata, $key, $secret);
	if (!empty($result)) {
	    $data1 = json_decode($result);
	    $recording = json_decode($data1->data);
	}
	$timespent = 0; // initialising
	rsort($recording->Items);

	foreach ($recording->Items as $record) {

		$lastsession = $record->time;

		if ( $lastsession > $syncdate) {


		$vcsid = $record->key_room;
		$session = $record->session;

		echo $course->id.$session;echo '<br>';
	//	echo $lastsessiontime;

	// $authdata = get_auth_data($cgapi, $cgsecret, $recordingstatus, $course, $cm, $role);
	// $apiurl = 'https://api.congrea.net/t/analytics/attendance';
	// $attendancedata = attendence_curl_request($apiurl, $session, $key, $authdata->authpass, $authdata->authuser, $authdata->room);
	// $attendencestatus = json_decode($attendancedata);
	//
	// $apiurl2 = 'https://api.congrea.net/t/analytics/attendancerecording';
	// $recordingdata = attendence_curl_request($apiurl2, $session, $key, $authdata->authpass, $authdata->authuser, $authdata->room);
	// $recordingattendance = json_decode($recordingdata, true);
	// $sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.


		$apiurl = 'https://api.congrea.net/t/analytics/attendance';
    $data = attendence_curl_request($apiurl, $session, $key, $authpassword, $authusername, $room,false); // TODO.
    $attendencestatus = json_decode($data);

		$sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.
		if (!empty($attendencestatus) and ! empty($sessionstatus)) {
			// session date
				$sessiondate = userdate($record->time / 1000); // Todo. this is an array and we should return only the last date so far.
        foreach ($attendencestatus->attendance as $sattendence) {



				//	$studentname = $DB->get_record('user', array('id' => $sattendence->uid));
				//echo $syncdate;

/*
				 if ($syncdate >= $sessionstatus->sessionstarttime  ) { // sync only todays sessions
				 	continue;
				 }
*/
						$connect = json_decode($sattendence->connect);
						$disconnect = json_decode($sattendence->disconnect);
						$studentsstatus = calctime($connect, $disconnect, $sessionstatus->sessionstarttime, $sessionstatus->sessionendtime);


					//if ($sattendence->uid == $trainerid) {
					if (!empty($studentsstatus->totalspenttime)) { // this means user was present

						if (!empty($studentsstatus->totalspenttime) and $sessionstatus->totalsessiontime >= $studentsstatus->totalspenttime) {
							$timespent =  $studentsstatus->totalspenttime;
							$presence = ($studentsstatus->totalspenttime * 100) / $sessionstatus->totalsessiontime;

						}else if ($studentsstatus->totalspenttime > $sessionstatus->totalsessiontime) {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = 100; // Special case handle.
						} else {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = '0';
						}

						// write code to insert record in db
						global $DB;
						$recordinsert = new stdClass();
						$recordinsert->userid = $sattendence->uid;
						$recordinsert->cmid = $cm->id;
						$recordinsert->sessionid = $session;
						$recordinsert->starttime = $studentsstatus->starttime; // this should studentstatus
						$recordinsert->endtime = $studentsstatus->endtime; //// this should studentstatus
						$recordinsert->duration = $timespent;
						$recordinsert->presentpercent = $presence;
						$recordinsert->timecreated = time();

						$getudata = $DB->get_record('user', array('id'=>$sattendence->uid));

						$recordinsert->userrole = $getudata->icq;

						// if ($sattendence->uid == $trainerid) {
						// 	$recordinsert->userrole = 'Trainer';
						// } else {
						// 	$recordinsert->userrole = 'Student';
						// }

						// first check if the record is present or not
						//$studentsstatus->starttime
						//$studentsstatus->endtime

						$checkrecord = $DB->get_record('local_trainer_liveclasstime',
							array('userid'=>$sattendence->uid, 'cmid'=>$cm->id, 'starttime' =>$studentsstatus->starttime ));
						if (!empty($checkrecord)) { // record is already present
							global $DB;
							$upduser = new stdClass();
							$upduser->id = $checkrecord->id;
							$upduser->cmid = $cm->id;
							$upduser->userid = $sattendence->uid;
							$upduser->sessionid = $session;
							$upduser->starttime = $studentsstatus->starttime;
							$upduser->endtime = $studentsstatus->endtime;
							$upduser->userrole = $recordinsert->userrole;
							$DB->update_record('local_trainer_liveclasstime', $upduser);
							echo 'updated for'.$sattendence->uid.'-'.$cm->id;

						} else {
							$insert = $DB->insert_record('local_trainer_liveclasstime', $recordinsert);
							if($insert) {
								echo 'inserted for'.$sattendence->uid.'-'.$cm->id;
							}
						}

					} // if of present
				} // foreach
			} // end of if attendaencestatus

} // syncdate

	}

	return true;
	//return $timespent;

}



/* to get congrea details based on cm  this stores results in DB */
function get_congrea_details_trainer_todb_again_session($course,$cm, $session, $trainerid=null,$syncdate=null) {
	global $CFG,$DB, $USER;
	require_once($CFG->dirroot.'/mod/congrea/lib.php');
	require_once($CFG->dirroot.'/mod/congrea/locallib.php');
	//require_once($CFG->dirroot.'/mod/congrea/auth.php');

$text = ' ';
	// we will send cm name from here..
	$cmname = $cm->name;

	$congrea = $DB->get_record('congrea', array('id' => $cm->instance), '*', MUST_EXIST);
	$key = get_config('mod_congrea', 'cgapi');
	$secret = get_config('mod_congrea', 'cgsecretpassword');
	$cgapi = get_config('mod_congrea', 'cgapi');
	$cgsecret = get_config('mod_congrea', 'cgsecretpassword');
	$recordingstatus = true;
	$role = 't'; // this is very important Mihir
	 if (strlen($cgsecret) >= 64 && strlen($cgapi) > 32) {
	 		require_once($CFG->dirroot.'/mod/congrea/auth.php');
	 }
	 $fromcms = true; // Identify congrea is from cms.
	// Get congrea api key and Secret key from congrea setting.

	$context = context_module::instance($cm->id);
	has_capability('mod/congrea:attendance', $context);

	$room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;

	//	echo $lastsessiontime;

	// $authdata = get_auth_data($cgapi, $cgsecret, $recordingstatus, $course, $cm, $role);
	// $apiurl = 'https://api.congrea.net/t/analytics/attendance';
	// $attendancedata = attendence_curl_request($apiurl, $session, $key, $authdata->authpass, $authdata->authuser, $authdata->room);
	// $attendencestatus = json_decode($attendancedata);
	//
	// $apiurl2 = 'https://api.congrea.net/t/analytics/attendancerecording';
	// $recordingdata = attendence_curl_request($apiurl2, $session, $key, $authdata->authpass, $authdata->authuser, $authdata->room);
	// $recordingattendance = json_decode($recordingdata, true);
	// $sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.


		$apiurl = 'https://api.congrea.net/t/analytics/attendance';
    $data = attendence_curl_request($apiurl, $session, $key, $authpassword, $authusername, $room,false); // TODO.
    $attendencestatus = json_decode($data);



		$sessionstatus = get_total_session_time($attendencestatus->attendance); // Session time.
		if (!empty($attendencestatus) and ! empty($sessionstatus)) {
			// session date
				$sessiondate = userdate($record->time / 1000); // Todo. this is an array and we should return only the last date so far.
        foreach ($attendencestatus->attendance as $sattendence) {



				//	$studentname = $DB->get_record('user', array('id' => $sattendence->uid));
				//echo $syncdate;

/*
				 if ($syncdate >= $sessionstatus->sessionstarttime  ) { // sync only todays sessions
				 	continue;
				 }
*/
						$connect = json_decode($sattendence->connect);
						$disconnect = json_decode($sattendence->disconnect);
						$studentsstatus = calctime($connect, $disconnect, $sessionstatus->sessionstarttime, $sessionstatus->sessionendtime);


					//if ($sattendence->uid == $trainerid) {
					if (!empty($studentsstatus->totalspenttime)) { // this means user was present

						if (!empty($studentsstatus->totalspenttime) and $sessionstatus->totalsessiontime >= $studentsstatus->totalspenttime) {
							$timespent =  $studentsstatus->totalspenttime;
							$presence = ($studentsstatus->totalspenttime * 100) / $sessionstatus->totalsessiontime;

						}else if ($studentsstatus->totalspenttime > $sessionstatus->totalsessiontime) {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = 100; // Special case handle.
						} else {
								$timespent =  $studentsstatus->totalspenttime;
								$presence = '0';
						}

						// write code to insert record in db
						global $DB;
						$recordinsert = new stdClass();
						$recordinsert->userid = $sattendence->uid;
						$recordinsert->cmid = $cm->id;
						$recordinsert->sessionid = $session;
						$recordinsert->starttime = $studentsstatus->starttime; // this should studentstatus
						$recordinsert->endtime = $studentsstatus->endtime; //// this should studentstatus
						$recordinsert->duration = $timespent;
						$recordinsert->presentpercent = $presence;
						$recordinsert->timecreated = time();

						$getudata = $DB->get_record('user', array('id'=>$sattendence->uid));

						$recordinsert->userrole = $getudata->icq;

						// if ($sattendence->uid == $trainerid) {
						// 	$recordinsert->userrole = 'Trainer';
						// } else {
						// 	$recordinsert->userrole = 'Student';
						// }

						// first check if the record is present or not
						//$studentsstatus->starttime
						//$studentsstatus->endtime

						$checkrecord = $DB->get_record('local_trainer_liveclasstime',
							array('userid'=>$sattendence->uid, 'cmid'=>$cm->id, 'starttime' =>$studentsstatus->starttime ));
						if (!empty($checkrecord)) { // record is already present
							global $DB;
							$upduser = new stdClass();
							$upduser->id = $checkrecord->id;
							$upduser->cmid = $cm->id;
							$upduser->userid = $sattendence->uid;
							$upduser->sessionid = $session;
							$upduser->starttime = $studentsstatus->starttime;
							$upduser->endtime = $studentsstatus->endtime;
							$upduser->userrole = $recordinsert->userrole;
							$DB->update_record('local_trainer_liveclasstime', $upduser);
							echo 'updated for'.$sattendence->uid.'-'.$cm->id;
							echo '<br>';
							$text = 'Sync completed successfully';

						} else {
							$insert = $DB->insert_record('local_trainer_liveclasstime', $recordinsert);
							if($insert) {
								echo 'inserted for'.$sattendence->uid.'-'.$cm->id;
								echo '<br>';
								$text = 'Sync completed successfully';
							}
						}

					} // if of present
				} // foreach
			} // end of if attendaencestatus

	return $text;
	//return $timespent;

}
