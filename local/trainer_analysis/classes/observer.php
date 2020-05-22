<?php

namespace trainer_analysis\task;
defined('MOODLE_INTERNAL') || die();

class observer {

    public static function send_sesstion_todb(\core\event\course_viewed $event) {

      global $DB, $CFG, $PAGE, $SITE,$USER;
      require_once($CFG->dirroot.'/user/lib.php');
      require_once($CFG->dirroot.'/course/lib.php');
      require_once($CFG->dirroot . '/mod/congrea/lib.php');
      require_once($CFG->dirroot . '/mod/congrea/locallib.php');

      $eventdata = $event->get_data();
      $courseid = $eventdata['courseid'];
      $course = $DB->get_record('course', array('id'=>$courseid));

      //check if the user is trainer for trainer role id is 3
      $eventuserid = $eventdata['userid'];

      $sql = "SELECT id FROM {role_assignments} WHERE roleid = 3 and userid = $eventuserid";
      $trainers = $DB->get_records_sql($sql);
      if (!empty($trainers)) {

        $key = get_config('mod_congrea', 'cgapi');
        $secret = get_config('mod_congrea', 'cgsecretpassword');

        $modinfo = get_fast_modinfo($course);
        	foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
            $room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;

            $result = curl_request("https://api.congrea.net/backend/recordings", $postdata, $key, $secret);
            $data = attendence_curl_request('https://api.congrea.net/data/analytics/attendance',
            $session, $key, $authpassword, $authusername, $room, $USER->id);
            $attendencestatus = json_decode($data);
            if (!empty($result)) {
                $recdata = json_decode($result);
                $recording = json_decode($recdata->data);
            }

            foreach ($recording->Items as $record) {
              // write code to insert record in db
              global $DB,$CFG;
              $sessionurl = $CFG->wwwroot.'/local/trainer_analysis/sessionsync.php?cmid='.$cm->id.'&session='.$record->session.'&psession='.true;
              $checkrecord = $DB->get_record('liveclass_session', array('cm'=>$cm->id, 'session' =>$record->session ));
              if (!empty($checkrecord)) { // record is already present

              } else {
                $recordinsert = new stdClass();
                $recordinsert->course = $cm->course;
                $recordinsert->cm = $cm->id;
                $recordinsert->session = $record->session;
                $recordinsert->sessiondate = $record->time; // this should studentstatus
                $recordinsert->sessionurl = $sessionurl; // this should studentstatus
                $recordinsert->syncdate = ''; //// this should studentstatus
                $recordinsert->syncflag = 0;
                $insert = $DB->insert_record('liveclass_session', $recordinsert);
              }

            }

        	}

      } // if clause

    } //function

//loggedin event

    public static function send_sesstion_todb_loggedin(\core\event\user_loggedin $event) {

      global $DB, $CFG, $PAGE, $SITE,$USER;
      require_once($CFG->dirroot.'/user/lib.php');
      require_once($CFG->dirroot.'/course/lib.php');
      require_once($CFG->dirroot . '/mod/congrea/lib.php');
      require_once($CFG->dirroot . '/mod/congrea/locallib.php');

      $eventdata = $event->get_data();
      $courseid = $eventdata['courseid'];
      $course = $DB->get_record('course', array('id'=>$courseid));

      //check if the user is trainer for trainer role id is 3
      $eventuserid = $eventdata['userid'];

      $sql = "SELECT id FROM {role_assignments} WHERE roleid = 3 and userid = $eventuserid";
      $trainers = $DB->get_records_sql($sql);
      if (!empty($trainers)) {

        $key = get_config('mod_congrea', 'cgapi');
        $secret = get_config('mod_congrea', 'cgsecretpassword');

        $modinfo = get_fast_modinfo($course);
          foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
            $room = !empty($course->id) && !empty($cm->id) ? $course->id . '_' . $cm->id : 0;

            $result = curl_request("https://api.congrea.net/backend/recordings", $postdata, $key, $secret);
            $data = attendence_curl_request('https://api.congrea.net/data/analytics/attendance',
            $session, $key, $authpassword, $authusername, $room, $USER->id);
            $attendencestatus = json_decode($data);
            if (!empty($result)) {
                $recdata = json_decode($result);
                $recording = json_decode($recdata->data);
            }

            foreach ($recording->Items as $record) {
              // write code to insert record in db
              global $DB,$CFG;
              $sessionurl = $CFG->wwwroot.'/local/trainer_analysis/sessionsync.php?cmid='.$cm->id.'&session='.$record->session.'&psession='.true;
              $checkrecord = $DB->get_record('liveclass_session', array('cm'=>$cm->id, 'session' =>$record->session ));
              if (!empty($checkrecord)) { // record is already present

              } else {
                $recordinsert = new stdClass();
                $recordinsert->course = $cm->course;
                $recordinsert->cm = $cm->id;
                $recordinsert->session = $record->session;
                $recordinsert->sessiondate = $record->time; // this should studentstatus
                $recordinsert->sessionurl = $sessionurl; // this should studentstatus
                $recordinsert->syncdate = ''; //// this should studentstatus
                $recordinsert->syncflag = 0;
                $insert = $DB->insert_record('liveclass_session', $recordinsert); // this is the table 
              }

            }

          }

      } // if clause

    } //function

} //class observer
