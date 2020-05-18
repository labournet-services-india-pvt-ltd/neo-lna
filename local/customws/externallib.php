<?php
/* this is the main externla page to give json response for LN requirement

*/
require_once('../../config.php');
require_once($CFG->dirroot . '/lib/coursecatlib.php');
require_once($CFG->dirroot . '/lib/completionlib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot.'/local/customws/lib.php');

class customws_user_info extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.3
     */
    public static function get_user_information_parameters() {
        return new external_function_parameters(
                array(
            	 'startdate' => new external_value(PARAM_TEXT, 'course percent change from given date',VALUE_OPTIONAL, ''),
                'enddate' => new external_value(PARAM_TEXT, 'course percent change to given date', VALUE_OPTIONAL, '')
              )
        );
    }

    /**
     * Get all user information
     *
     * @param array $options It contains an array (list of ids)
     * @return array
     * @since Moodle 2.2
     */
    public static function get_user_information($startdate =null,$enddate =null) {
        global $CFG, $DB;

        //validate parameter
         $params = self::validate_parameters(self::get_user_information_parameters(),
            [
            'startdate' => $startdate,
            'enddate' => $enddate
            ]
        );
         $startdate = $params['startdate'];
         $enddate = $params['enddate'];
         $totalhours = 5*60*60;
         $totalminuts = 30*60;
        $totaltimesubtracted = $totalhours + $totalminuts;
         //Start date
        $finalstarttime = '';

        if(!empty($startdate)){
           	$startdatearray = explode('-',$startdate);
           	$array = array($startdatearray[0],$startdatearray[1],$startdatearray[2]);
           	$array1 = array($startdatearray[3],'00','00');
           	$array2 = implode('-',$array).' '.implode(':', $array1);
      		$startdateunix = strtotime($array2);
            $finalstarttime = $startdateunix - $totaltimesubtracted;
        }
        $finalendtime = '';
        if(!empty($enddate)){
      		$enddatearray = explode('-',$enddate);
           	$array3 = array($enddatearray[0],$enddatearray[1],$enddatearray[2]);
           	$array4 = array($enddatearray[3],'00','00');
           	$array5 = implode('-',$array3).' '.implode(':', $array4);
      		$enddateunix = strtotime($array5);
            //this is start date for find details upto give time
            $finalendtime = $enddateunix - $totaltimesubtracted;
        }

	    $userdetials = get_ws_user_info($finalstarttime,$finalendtime);

		foreach ($userdetials as $userdetial) {

      //Mihir just check that this report is only for Kalai Braining groom team
    	if (($userdetial['class_id'] == 30) || ($userdetial['class_id'] == 32) || ($userdetial['class_id'] == 29)) {

    	} else {
    		continue;
    	}

        $userinfoarry = array();
        $userinfoarry['user_id'] = $userdetial['user_id'];
        $userinfoarry['user_name'] = $userdetial['user_name'];
        $userinfoarry['class_id'] = $userdetial['class_id'];
        $userinfoarry['class_name'] = $userdetial['class_name'];
        $userinfoarry['mobile_no'] = $userdetial['mobile_no'];
        $userinfoarry['percentage_completed'] = $userdetial['percentage_completed'];
        $userinfoarry['count_total'] = $userdetial['count_total'];
        $userinfoarry['count_total_completed'] = $userdetial['count_total_completed'];
        $userinfoarry['last_sess_completed'] = $userdetial['last_sess_completed'];
        $userinfoarry['last_sess_completed_name'] = $userdetial['last_sess_completed_name'];
        $userinfoarry['test_link_lassess_completed'] = $userdetial['test_link_lassess_completed'];
        $userinfoarry['test_name_lassess_completed'] = $userdetial['test_name_lassess_completed'];
        $userinfoarry['next_session_link'] = $userdetial['next_session_link'];
        $userinfoarry['next_session_no'] = $userdetial['next_session_no'];
        $userinfoarry['next_session_name'] = $userdetial['next_session_name'];
        $userinfoarry['doubt_clarification_link'] = $userdetial['doubt_clarification_link'];

        $uservalues[] = $userinfoarry;
        }
        return $uservalues;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function get_user_information_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                [
                    'user_id' => new external_value(PARAM_INT, 'user id'),
                    'user_name' => new external_value(PARAM_RAW, 'user name'),
                    'class_id' => new external_value(PARAM_INT, 'class id'),
                    'class_name' => new external_value(PARAM_RAW, 'class name'),
                    'mobile_no' => new external_value(PARAM_RAW, 'mobile no'),
                    'percentage_completed' => new external_value(PARAM_RAW, 'percentage completed'),
                    'count_total' => new external_value(PARAM_INT, 'count total'),
                    'count_total_completed' => new external_value(PARAM_INT, 'count total completed'),
                    'last_sess_completed' => new external_value(PARAM_INT, 'last sess completed'),
                    'last_sess_completed_name' => new external_value(PARAM_RAW, 'last sess completed name'),
                    'test_link_lassess_completed' => new external_value(PARAM_RAW, 'test link lassess completed'),
                    'test_name_lassess_completed' => new external_value(PARAM_RAW, 'test name lassess completed'),
                    'next_session_link' => new external_value(PARAM_RAW, 'next session link'),
                    'next_session_no' => new external_value(PARAM_INT, 'next session no'),
                    'next_session_name' => new external_value(PARAM_RAW, 'next session name'),
                    'doubt_clarification_link' => new external_value(PARAM_RAW, 'doubt clarification link'),
                    ], 'alluserinfo'
                )
            );
    }
}
