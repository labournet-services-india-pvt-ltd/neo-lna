<?php
/**
 * Fumble with Moodle's global navigation by leveraging Moodle's *_extend_navigation() hook.
 *
 * @param global_navigation $navigation
 */
function local_customws_extend_navigation(global_navigation $navigation) {
    global $CFG, $PAGE, $COURSE;
		$systemcontext = context_system::instance();
		$showreport = has_capability('moodle/site:configview', $systemcontext);
    // Check if admin wanted us to remove the myhome node from Boost's nav drawer.
    // We have to check explicitely if the configurations are set because this function will already be
    // called at installation time and would then throw PHP notices otherwise.
    if ($showreport) {
        // If yes, do it.
        // Hide myhome node (which is basically the $navigation global_navigation node).
				$abc = $navigation->add(get_string('allcompletionrpt','local_customws'),
					$CFG->wwwroot.'/local/customws/allrpt.php');
				$abc->showinflatnavigation = true;
    }
}

/* this is the main Lib page for LN web services

*/
function get_ws_user_info($starttime,$endtime){
	global $DB;
	$returnjsonarray = [];
	$lastcompletedsessions = get_modules_completion($starttime,$endtime);

	if(!empty($lastcompletedsessions)){
		foreach ($lastcompletedsessions as $lastcompletedsession) {
			//we will pass $lastcompletedsession
			$returnjsonarray[] = get_course_module_info($lastcompletedsession);
		}
	}

	return $returnjsonarray;
}
function get_modules_completion($starttime,$endtime){
	global $DB;
	$cmcompletions = '';
	$quizmoduleid = $DB->get_record('modules',array('name'=>'quiz'));
	$forummoduleid = $DB->get_record('modules',array('name'=>'forum'));
	$labelmoduleid = $DB->get_record('modules',array('name'=>'label'));
	$andcondition = " AND cm.module != $quizmoduleid->id AND cm.module != $forummoduleid->id AND cm.module != $labelmoduleid->id ";

	if(!empty($starttime) && !empty($endtime)){
		$sqlquery = 'SELECT * from (
						SELECT cmc.id,cmc.coursemoduleid as cmid ,cmc.userid as userid ,cmc.timemodified,
						cm.course as course FROM {course_modules_completion} as cmc
						JOIN {course_modules} as cm ON cm.id = cmc.coursemoduleid
						WHERE cmc.timemodified >= ? AND cmc.timemodified <= ? AND
						(cmc.completionstate != 0)'
						.$andcondition.
						'ORDER BY cmc.timemodified desc) AS SUB group by userid,course';
		$cmcompletions = $DB->get_records_sql($sqlquery,array($starttime,$endtime));

	}else if(!empty($starttime) && empty($endtime)){
		$sqlquery = 'SELECT * from (
						SELECT cmc.id,cmc.coursemoduleid as cmid ,cmc.userid as userid ,cmc.timemodified,
						cm.course as course FROM {course_modules_completion} as cmc
						JOIN {course_modules} as cm ON cm.id = cmc.coursemoduleid
						WHERE cmc.timemodified >= ? AND
						(cmc.completionstate != 0)'
						.$andcondition.
						'ORDER BY cmc.timemodified desc) AS SUB group by userid,course';
		$cmcompletions = $DB->get_records_sql($sqlquery,array($starttime));

	} else if(empty($starttime) && empty($endtime)){
		// the correct query is cmc.id also needed Mihir/Manju 25 March 2020
		$sqlquery = 'SELECT * from (
						SELECT cmc.id,cmc.coursemoduleid as cmid ,cmc.userid as userid ,cmc.timemodified,
						cm.course as course FROM {course_modules_completion} as cmc
						JOIN {course_modules} as cm ON cm.id = cmc.coursemoduleid
						WHERE cmc.completionstate != 0'
						.$andcondition.
						'ORDER BY cmc.timemodified desc) AS SUB group by userid,course';

		$cmcompletions = $DB->get_records_sql($sqlquery);

	}
return $cmcompletions;

}

function get_course_module_info($lastcompletedsession){
	global $CFG,$DB;

	$cid = $lastcompletedsession->course;
	$completecmid = $lastcompletedsession->cmid;
	//just get the name of cm Mihir 03 March 2020
	$modinfo = get_fast_modinfo($cid);
	$cmcl = $modinfo->get_cm($completecmid);
	$lastcompleted_name = $cmcl->name;

	$userid = $lastcompletedsession->userid;

	$user_mobile = '';
	$testlink = '';
	$get_test_name  = '';

	// get user
	$get_user = $DB->get_record('user', array('id'=>$userid));
	//get mobile //
	$get_mobile_sql = $DB->get_record_sql("SELECT uid.data FROM {user_info_field} as uif
	JOIN {user_info_data} as uid ON uif.id=uid.fieldid where uif.shortname= 'mobilephone'
	AND uid.userid = $userid");
	if (!empty($get_mobile_sql)) {
			$user_mobile1 = $get_mobile_sql->data;

			$mobillength = strlen($user_mobile1);
			if ($mobillength >=10) {
				$startsplit = $mobillength - 10; //10 because mobile is 10 digit
				$user_mobile = '91'.substr($user_mobile1,$startsplit);
			} else {
				$user_mobile = $user_mobile1;
			}

	}

	// get doubt clarification link
	//find get the module id
	$forumlink = '';
	$forummodid = $DB->get_record('modules', array('name'=>'forum'));
	$get_doubt_forum = $DB->get_record('course_modules', array('course'=>$cid, 'module'=>$forummodid->id, 'deletioninprogress'=>0, 'visible'=>1));
	if (!empty($get_doubt_forum)) {
		$forumlink = $CFG->wwwroot.'/mod/forum/view.php?id='.$get_doubt_forum->id;
	}

	//find completion of this course and keep it eio_nready
	$course = $DB->get_record('course', array('id'=>$cid));
	$completion = new completion_info($course);

	$arraysession = get_all_session($cid);

	//to get count of sessions
	$get_total_details = get_count_session($cid, $userid);

	// we will call a function to find out NEXT NOT-COMPLETED CMID starting from this KEY ,
	//we will pass current cmid and array session

	$nextnotcmplid = get_next_notcompleted($completecmid,$arraysession, $cid,$userid) ;
	$compcmidkey = array_search($completecmid, $arraysession); // $key = 2;
	// find out the related TEST LINK using that idnumber relation
	$getquizmodid = $DB->get_record('modules', array('name'=>'quiz'));
	$gettestsql = "SELECT id,instance from {course_modules} WHERE module = $getquizmodid->id AND idnumber LIKE '%$completecmid%'";

	$gettestsqlresult = $DB->get_record_sql($gettestsql);

	if (!empty($gettestsqlresult)) {

		//now get the test name
		$get_test_name_sql = $DB->get_record('quiz', array('id'=>$gettestsqlresult->instance));
		$get_test_name = $get_test_name_sql->name;
		// now find out if the related TEST CM is completed or not
			// find completion for this test cm id //check competion for a cm
			$getmodinfo = get_fast_modinfo($cid);
			$get_cm_first = $getmodinfo->get_cm($gettestsqlresult->id);
		$get_cm_data_test = $completion->get_data($get_cm_first,false,$userid);
		if ($get_cm_data_test->completionstate !=0) {
			//completed
			$testlink = 'Already Completed';
		} else {
			//not completed yet so send mysqli_get_links_stats
			$testlink = $CFG->wwwroot.'/mod/quiz/view.php?id='.$gettestsqlresult->id;

		}

	}

	$returnarray = array(
		'user_id' => $userid,
		'user_name' => $get_user->username,
		'class_id' => $cid,
		'class_name' => $course->fullname,
		'mobile_no' => $user_mobile,
		'percentage_completed' => $get_total_details['percentage_completed'],
		'count_total' => $get_total_details['count_total'],
		'count_total_completed' => $get_total_details['count_total_completed'],
		'last_sess_completed' => $completecmid,
		'last_sess_completed_name' => $lastcompleted_name,
		'test_link_lassess_completed' => $testlink,
		'test_name_lassess_completed' => $get_test_name,
		'next_session_link' => $nextnotcmplid['next_session_link'],
		'next_session_no' => $nextnotcmplid['next_session_no'],
		'next_session_name' => $nextnotcmplid['next_session_name'],
		'doubt_clarification_link' => $forumlink,
	);

	return $returnarray;

}


// next not completed cmid find fann_get_cascade_activation_functions
function get_next_notcompleted($completecmid,$arraysession,$cid,$userid){
	//search for the particular cm to find the key
	global $CFG, $DB;
	$compcmidkey = array_search($completecmid, $arraysession); // $key = 2;
	$getmodinfo = get_fast_modinfo($cid);
	$course = $DB->get_record('course', array('id'=>$cid));
	$completion = new completion_info($course);

	//check competion for the next //
	$nextsesscmid = $arraysession[$compcmidkey +1];

	if (!empty($nextsesscmid)) {

		$get_cm_now = $getmodinfo->get_cm($nextsesscmid);
		$get_cm_data_next = $completion->get_data($get_cm_now ,false,$userid);

		if ($get_cm_data_next->completionstate !=0) { // completed
			// it looks like this next session is also completed so we find again NEXT of this
			return get_next_notcompleted($nextsesscmid,$arraysession,$cid,$userid);

		} else {
			// this is the TRUE next session which is not completed
			// return the next key- value of the array session as that is what is needed to completed by userid

			$getcmdata = $getmodinfo->get_cm($nextsesscmid);
			$getcmurl = $CFG->wwwroot.'/mod/'.$getcmdata->modname.'/view.php?id='.$getcmdata->id;
			$returnarray = array(
				'next_session_link' => $getcmurl ,
				'next_session_no' => $getcmdata->id,
				'next_session_name' => $getcmdata->name,
			);
			return $returnarray;
		}

	} else {
		return false;
	}

}

// this will return a sequence of activitieis which are not forum or quiz
function get_all_session($courseid){
	global $DB;
	$modinfo = get_array_of_activities($courseid);

	$newarrayseq = array();
	foreach($modinfo as $key => $value) {

		// if ($value->mod != 'quiz' AND $value->mod != 'forum' AND $value->mod != 'label' AND $value->deletioninprogress != 1) {
		// 	$newarrayseq[] = $value->cm;
		// }

		//if ($value->mod != 'quiz' AND $value->mod != 'forum' AND $value->mod != 'label' ) {
    if ($value->mod == 'page') {
			if(isset($value->deletioninprogress) && ($value->deletioninprogress != 1)){
				$newarrayseq[] = $value->cm;
			}else{
				$newarrayseq[] = $value->cm;

			}
		}
	}

	return $newarrayseq;

}

//find the total session and how many COMPLETED without ASSESSMENT and FORUM, LABEL
// this will return a sequence of activitieis which are not forum or quiz
function get_count_session($courseid, $userid){
	global $DB;
	$totalsessioncount = 0;
	$count_completed = 0;
	$percentage_completed = 0;
	$modinfo = get_array_of_activities($courseid);

	$course = $DB->get_record('course', array('id'=>$courseid));

	$getmodinfo = get_fast_modinfo($courseid);


	$completion = new completion_info($course);

	foreach($modinfo as $key => $value) {
		// if ($value->mod != 'quiz' AND $value->mod != 'forum' AND $value->mod != 'label' AND $value->deletioninprogress != 1) {
		// 	$totalsessioncount++;

		// 	$get_cm_first = $getmodinfo->get_cm($value->cm);
		// 	$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);


		// 	if ($get_cm_data_next->completionstate !=0) {
		// 		//COMPLETED
		// 		$count_completed++;
		// 	}
		// }


		//if ($value->mod != 'quiz' AND $value->mod != 'forum' AND $value->mod != 'label' AND $value->mod != 'resource' ) {
		if ($value->mod == 'page') {

			if(isset($value->deletioninprogress) && ($value->deletioninprogress != 1)){
				$totalsessioncount++;
				$get_cm_first = $getmodinfo->get_cm($value->cm);
				$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);
				if ($get_cm_data_next->completionstate !=0) {
					//COMPLETED
					$count_completed++;
				}
			}else{
				$totalsessioncount++;
				$get_cm_first = $getmodinfo->get_cm($value->cm);
				$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);
				if ($get_cm_data_next->completionstate !=0) {
					//COMPLETED
					$count_completed++;
				}

			}
		}
	}
	if ($totalsessioncount != 0) {
			$percentage_completed = $count_completed/$totalsessioncount * 100;
	}

	$total_array = array(
		'percentage_completed' => number_format($percentage_completed,2),
		'count_total' => $totalsessioncount,
		'count_total_completed' => $count_completed,
	);
	return $total_array;

}


//find the total session and how many COMPLETED without ASSESSMENT and FORUM, LABEL
// this will return a sequence of activitieis which are not forum or quiz
function get_exact_session_fifty($courseid, $userid){
	global $DB;
	$totalsessioncount = 0;
	$count_completed = 0;
	$count_completed_one = 0;
	$percentage_completed = 0;
	$percentage_completed1 = 0;
  $fiftydate = '-';
  $hundreddate = '-';

	$modinfo = get_array_of_activities($courseid);

	$course = $DB->get_record('course', array('id'=>$courseid));

	$getmodinfo = get_fast_modinfo($courseid);

	$completion = new completion_info($course);

  $totalsessioncount = count(get_all_session($courseid));
//  if ($courseid == 29 and $userid == 342) {

    foreach($modinfo as $key => $value) {
      if ($value->mod == 'page') {

                $fiftydate = '-';
        				$get_cm_first = $getmodinfo->get_cm($value->cm);
        				$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);
        				if ($get_cm_data_next->completionstate !=0) {
        					//COMPLETED
        					$count_completed++;
        				}
                $percentage_completed = $count_completed/$totalsessioncount * 100;
                if ($percentage_completed >=50 and $percentage_completed < 60) {
                    if ($get_cm_data_next->timemodified !=0) {
                      $fiftydate = date('d-m-Y',$get_cm_data_next->timemodified);
                    }
                    break;
                }


      }
    }


  //   //we need to find the cm for approx 40-50% range
  //   if(($s = sizeof($arraypage)) % 2 == 0) {
  //     //echo "Middle values are: " . $arraypage[$s / 2] . " and " . $arraypage[$s / 2 - 1];
  //     $fiftycm = $arraypage[$s / 2];
  //   } else {
  //     $fiftycm = $arraypage[floor($s / 2)];
  //     //echo "Middle value: " . $arraypage[floor($s / 2)];
  //   }
  //
  // // for fifty
  // $get_cm_50 = $getmodinfo->get_cm($fiftycm);
  // $get_cm_50_data = $completion->get_data($get_cm_50 ,false,$userid);
  //   if ($get_cm_50_data->completionstate !=0 AND $get_cm_50_data->timemodified >0) {
  //     $fiftydate = date('d-m-Y',$get_cm_50_data->timemodified);
  //     //$fiftydate = $get_cm_50_data->timemodified;
  //   } else {
  //     $fiftydate = '-';
  //   }

    $total_array_new = array(
  		'fifty_date' => $fiftydate,
  	);
  	return $total_array_new;

  //100% date
/*
  $get_cm_100= $getmodinfo->get_cm($lastcm);
  $get_cm_100_data = $completion->get_data($get_cm_100 ,false,$userid);
    if ($get_cm_100_data->timemodified >0) {
      $hundreddate = date('d-m-Y',$get_cm_100_data->timemodified);
      $hundreddate = $get_cm_100_data->timemodified;
    } else {
      $hundreddate = '-';
    }

    $total_array_new = array(
  		'fifty_date' => $fiftydate,
  		'hundred_Date' => $hundreddate,
  	);
  	return $total_array_new;
*/
//  }

// 	foreach($modinfo as $key => $value) {
// 		//if ($value->mod != 'quiz' AND $value->mod != 'forum' AND $value->mod != 'label' AND $value->mod != 'resource' ) {
// 		if ($value->mod == 'page') {
//
// 			if(isset($value->deletioninprogress) && ($value->deletioninprogress != 1)){
// 				$totalsessioncount++;
// 				$get_cm_first = $getmodinfo->get_cm($value->cm);
// 				$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);
// 				if ($get_cm_data_next->completionstate !=0) {
// 					//COMPLETED
// 					$count_completed++;
// 				}
// 			}else{
// 				$totalsessioncount++;
// 				$get_cm_first = $getmodinfo->get_cm($value->cm);
// 				$get_cm_data_next = $completion->get_data($get_cm_first ,false,$userid);
// 				if ($get_cm_data_next->completionstate !=0) {
// 					//COMPLETED
// 					$count_completed++;
// 				}
//
// 			}
//
// // calculate the percentage complete RIGHT HERE to find out if it is 50% or 100%
//       if ($totalsessioncount != 0) {
//
//           $percentage_completed = $count_completed/$totalsessioncount * 100;
//           if ($percentage_completed >=50 and $percentage_completed <= 60) {
//             if ($get_cm_data_next->timemodified !=0) {
//               $fiftydate = date('d-m-Y',$get_cm_data_next->timemodified);
//             } else {
//               $fiftydate = '-';
//             }
//           }
//
//           if ($percentage_completed == 100) {
//             if($get_cm_data_next->timemodified !=0) {
//               $hundreddate = date('d-m-Y',$get_cm_data_next->timemodified);
//             } else {
//               $hundreddate = '-';
//             }
//           }
//
//       }
//
//
// 		}
// 	}



}

//find the total session and how many COMPLETED without ASSESSMENT and FORUM, LABEL
// this will return a sequence of activitieis which are not forum or quiz
function get_exact_session_hundred($courseid, $userid){
	global $DB;

  $hundreddate = '-';

	$modinfo = get_array_of_activities($courseid);
	$course = $DB->get_record('course', array('id'=>$courseid));
	$getmodinfo = get_fast_modinfo($courseid);
	$completion = new completion_info($course);
//  if ($courseid == 29 and $userid == 342) {

  $hundredquery = $DB->get_record('course_completions', array('userid'=>$userid, 'course'=>$courseid));

  if (!empty($hundredquery)) {
    if ($hundredquery->timecompleted !=0) {
      $hundreddate = date('d-m-Y',$hundredquery->timecompleted);
    }
  }

    $total_array_new = array(
  		'hundred_Date' => $hundreddate,
  	);
  	return $total_array_new;


}
