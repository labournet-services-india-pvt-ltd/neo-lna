<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// This file is part of Moodle - http://moodle.org/                      //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//                                                                       //
// Moodle is free software: you can redistribute it and/or modify        //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation, either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// Moodle is distributed in the hope that it will be useful,             //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details.                          //
//                                                                       //
// You should have received a copy of the GNU General Public License     //
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.       //
//                                                                       //
///////////////////////////////////////////////////////////////////////////

/**
 * Form for community search
 *
 * @package    local_assessment_test
 * @author     Manjunath B K <manjunaathbk@elearn10.com>
 * @license    Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @copyright  http://www.lmsofindia.com 2017 or later
 */
global $DB;
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot. '/course/lib.php');
require_once('./lib.php');

class local_assessment_test_form extends moodleform {
	public function definition() {
		global $DB,$CFG,$PAGE;
		$mform =& $this->_form;
		$customdata = $this->_customdata['csdata']; // this contains the data of this
		$mform->addElement('header','assessmenttest',get_string('headingone','local_assessment_test'));
		$mform->setExpanded('assessmenttest');

		//get QP dropdown based on 'shortname' as 'test_quiz'.
		$courses = $DB->get_records('course',array('shortname'=>'PRE_ASSESSMENT'));
		//$quizes will contains all the test quiz ids and quiz names.
		$quizes=[];
		$quizes[''] = get_string('selectqp', 'local_assessment_test');
		if(!empty($courses)){
			foreach ($courses as $course) {
				$allactivities = get_array_of_activities($course->id);
				if(!empty($allactivities)){
					foreach ($allactivities as $activity) {
						if($activity->mod =='quiz' && $activity->visible == 1){
							$quizes[$activity->cm]= $activity->name;

						}
					}
				}
			}
		}
		$mform->addElement('select', 'quizcmid', get_string('selectqp', 'local_assessment_test'), $quizes);
		$mform->addElement('submit', 'submit', get_string('submit', 'local_assessment_test'));
		//subsector occupation based qp selection.

		$mform->addElement('header','assessmenttesttwo',get_string('headingtwo','local_assessment_test'));
		$mform->setExpanded('assessmenttesttwo');
		$subsectors = $DB->get_records('course_categories',array('parent'=>0));
		$subsectoroption = [];
		$subsectoroption['']=get_string('selectsubsect', 'local_assessment_test');
		foreach ($subsectors as  $subsector) {
			$subsectoroption[$subsector->id]=$subsector->name;
		}
		$mform->addElement('select', 'subsector', get_string('selectsubsect', 'local_assessment_test'), $subsectoroption);
		//occupation dropdown
		$occupation = get_occupations();
		$mform->addElement('select', 'occupation', get_string('selectoccupation', 'local_assessment_test'), $occupation,array('onblur'=>'getQP()'));
		//qp dropdown
		$emptyarray=[];
		$emptyarray[]=get_string('selectqp', 'local_assessment_test');

		$mform->addElement('hidden', 'reply', 'yes');
		$mform->settype('reply', PARAM_RAW);

		//$mform->addElement('select', 'qpname', get_string('selectqp', 'local_assessment_test'), $emptyarray,array('onblur'=>'getQPID()'));
		$sql ="SELECT c.id,c.fullname FROM {course} c
		LEFT JOIN {customfield_data} cd
		ON c.id=cd.instanceid
		WHERE  cd.fieldid = 3";
		$results = $DB->get_records_sql($sql);
		$csarry = [];
		$csarry['selectqp'] = get_string('selectqp','local_assessment_test');
		if($results){
			foreach ($results as $key => $course) {
				$csarry[$course->id] = $course->fullname;
			}
		}
		/*if(isset($customdata['qpname'])){
			$csarry = array();
		}else{
			$csarry = $csarry;
		}*/
		$mform->addElement('select', 'qpname', get_string('selectqp', 'local_assessment_test'), $csarry);

		// $mform->addElement('submit', 'chek', get_string('submit', 'local_assessment_test'));
		$mform->addElement('submit', 'submit', get_string('submit', 'local_assessment_test'));
		 //$mform->reset();
	}
	/*public function reset() {
        $this->_form->updateSubmission(null, null);
    }*/
}