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
 * @package    local_assessment_test
 * @copyright  Prashant <prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
global $DB, $CFG, $USER;  
require_login(true);
$context = context_system::instance();
$quizid = required_param('qid',PARAM_INT);
$userid = required_param('userid',PARAM_INT);
$cmid = required_param('cmid',PARAM_INT);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . '/local/assessment_test/pre_assessment_report.php');
$title = get_string('heading', 'local_assessment_test');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
echo $OUTPUT->header();
$table = new html_table();
$table->head = (array) get_strings(array('attempt', 'state','review'), 'local_assessment_test');
$attempts = quiz_get_user_attempts($quizid, $userid, 'finished', true);
$attemptid = 0;
$recolink = get_string('recommended', 'local_assessment_test');
$attemptlink = get_string('attemptpretest', 'local_assessment_test');
if(!empty($attempts)){
	$i= 1;
	foreach ($attempts as $qtkey => $qttvalue) {
	$finish = userdate($qttvalue->timefinish);
		$xx = $i;
		$table->data[] = array(
			$xx,
			ucfirst($qttvalue->state).'<br>'.$finish,
			html_writer::link(
				new moodle_url(
					$CFG->wwwroot.'/mod/quiz/review.php',
					array('attempt' => $qttvalue->id,'cmid'=>$cmid)
				), $recolink)

		);
		$i++;
	}
}
echo html_writer::table($table);
$html ='';
$html .= html_writer::start_div('div text-center');
$html .=html_writer::link(
	new moodle_url($CFG->wwwroot.'/mod/quiz/startattempt.php',
            array('cmid' => $cmid, 'sesskey' => sesskey())),
             $attemptlink,array('class' =>'btn btn-small btn-primary' 
        )
);
$html .= html_writer::end_div();
echo $html;
echo $OUTPUT->footer();