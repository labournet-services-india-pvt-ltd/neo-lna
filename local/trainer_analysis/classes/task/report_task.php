<?php

namespace trainer_analysis\task;

include_once dirname(dirname(dirname(__FILE__))).'/lib.php';

class report_task extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('pluginname', 'local_trainer_analysis');
    }

    public function execute() {
        global $DB, $CFG, $PAGE, $SITE;
        require_once($CFG->dirroot.'/user/lib.php');
        require_once($CFG->dirroot.'/course/lib.php');
        $syncdate = strtotime('today');
        $sql = "SELECT DISTINCT userid FROM {role_assignments} WHERE roleid = 3";
        $trainers = $DB->get_records_sql($sql);
        foreach ($trainers as $trainer) {
            $userid =$trainer->userid;
            $user = $DB->get_record('user',array('id'=>$userid));
            $cdetails = get_trainer_courses($userid);
            foreach ($cdetails as $value) {
                $coursevalue = $value;
                $checkcourse = $DB->get_record('course', array('id' => $coursevalue));
                $modinfo = get_fast_modinfo($checkcourse);
                foreach ($modinfo->get_instances_of('congrea') as $mgid => $cm) {
                    $someJSON = $cm->availability;
                    $someArray = json_decode($someJSON, true);
                    if (!empty($someArray)) {
                        foreach ($someArray['c'] as $condition) {
                            if ($condition['type'] == 'group') {
                                $groupid = $condition['id'];
                                $getgroups = $DB->get_records('groups_members', array('userid' => $userid, 'groupid' => $groupid));
                                if (!empty($getgroups)) {
                                    $trainercongreatime[] = get_congrea_details_trainer_todb($checkcourse,$cm,$userid,$syncdate);
                                }
                            }
                        }
                    }

                }
            }

    }// end of for each trainer
}
}

