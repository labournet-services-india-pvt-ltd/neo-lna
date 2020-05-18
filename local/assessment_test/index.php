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
 * @copyright  Manjunath B K <manjunaathbk@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('form/selecttest.php');
require_once($CFG->libdir . '/formslib.php');
global $DB, $CFG, $USER;  
require_login(true);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/assessment_test/index.php');
$title = get_string('pluginame', 'local_assessment_test');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();

$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/assessment_test/js/custom.js'),true);
echo $OUTPUT->header();
//$mform = new local_assessment_test_form();
$mform = new local_assessment_test_form();

//find the module id of the quiz
global $DB;
$moduleid = $DB->get_field('modules','id',array('name'=>'quiz'));

if($formdata  = $mform->get_data()){
	//print_object($formdata);die;
	$qcmid = $formdata->quizcmid;
	if(isset($qcmid) and !empty($qcmid) ){
		
	}else{
	// first find outthe pre assessment course, which will have shortname = PRE_ASSESS
		if(!empty($formdata->qpname)){
			$qpname = $formdata->qpname;
			// this is important to know. Basically when we create any pre-assssment test inside the pre-assessment course, in that quiz we should give idnumber like below QP_{courseid}, example QP_7
			//This will create the mapping that this particular quiz belongs to the QP for which id is 7. Other when students go via sub sector selection the right mapping will not be found.
			$qpname_search = 'QP_'.$qpname;
			$search_query = "SELECT id from {course_modules} where module=? and 
			idnumber = ?";
			$result = $DB->get_field_sql($search_query,array($moduleid,$qpname_search));
			if(!empty($result)){
				$qcmid = $result;
			}
		}		
	}

	if(isset($qcmid) and !empty($qcmid) ){

		$redirecturl = new moodle_url($CFG->wwwroot .'/mod/quiz/view.php',array('id'=>$qcmid));
		redirect($redirecturl);
	} else {
		echo html_writer::div(get_string('message','local_assessment_test'),'alert alert-danger');
	}
	
	// $mform->reset();
}

$mform->display();

echo $OUTPUT->footer();