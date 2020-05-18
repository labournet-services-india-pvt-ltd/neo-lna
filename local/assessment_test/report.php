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
require_once('lib.php');
global $DB, $CFG, $USER;  
require_login(true);
$context = context_system::instance();
$attemptid = required_param('attemptid',PARAM_INT);
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . '/local/assessment_test/report.php');
$title = get_string('heading', 'local_assessment_test');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
echo $OUTPUT->header();
$n  = '<h3 class="text-center">'.get_string('tableheading','local_assessment_test').'</h3>';
echo $n.'<br>';

$passingpercnt = 25;
$quizdata = quiz_category_stats($attemptid);
$html  = '';
$html .='<div class="frontpage-available-course-list">
			<div class="courses frontpage-course-list-all">
			<div class="courses-wrapper style-cards row">';
			$reccoursestemp = array();
			foreach ($quizdata as $categoryid => $value) {
			$reccoursestemp2 = get_recommended_courses($value[0]);
			$reccoursestemp=$reccoursestemp+$reccoursestemp2;

			}

			$reccourses = array_unique($reccoursestemp);
			
			// $reccourses = get_recommended_courses($value[0]);
				if($value[1] >= $passingpercnt){
					foreach ($reccourses as $key => $rcourse) {
						$course = $DB->get_record('course',array('id'=>$rcourse));
						$coursecat = $DB->get_record('course_categories',array('id'=>$course->category));
						$image = get_course_image_new_url($course);
						$imageurl = $CFG->wwwroot.'/local/assessment_test/test.jpg';
						if($image){
							$imageurl = $image;
						}
						$link = new moodle_url($CFG->wwwroot .'/course/view.php',array('id'=>$rcourse));
						$html .='<div class="theme-course-item col-12 col-md-6 col-lg-3">
							<div class="course-item-inner inner" data-courseid="6" data-type="1">
							<a href="'.$link.'">
								<div class="course-thumb-holder hasimage">
									<img src="'.$imageurl.'" class="course-thumb"></img>
								</div>
								<div class="card-body pr-1 course-info-container">
									<div class="d-flex align-items-start">
										<div class="w-100 text-truncate">
											<div class="text-muted muted d-flex mb-1 flex-wrap">
												<span class="text-truncate">'.$coursecat->name.'</span>
											</div>
											<a href="'.$link.'">
												<span class="text-truncate">'.$course->fullname.'</span>
											</a>
										</div>
									</div>
								</div>
							</a>
							</div>
						</div>';
					}
				}else{
					echo '<h5 class="text-center">'.get_string('donothavereccourses','local_assessment_test').'</h5>';
				}
		
		
		$html .='</div></div></div>';
	echo $html;
echo $OUTPUT->footer();