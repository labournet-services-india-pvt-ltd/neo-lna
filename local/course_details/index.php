<?php
// This file is part of the Local welcome plugin
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
 * @package    local_course_details
 * @copyright  Abhijit Sen<abhijitsen@elearn10.com>
 * @copyright  EDZLearn Services Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

require_once('../../config.php');
require_once('lib.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_url($CFG->wwwroot . '/local/course_details/index.php');
// $title = get_string('pluginname', 'local_course_details');
// $PAGE->set_title($title);
global $CFG,$DB;
$courseid = required_param('cid', PARAM_INT); //course id required for this page

$course = $DB->get_record('course',array('id'=>$courseid)); //find the course details

$title = $course->fullname;
$PAGE->set_title($title);

if(!empty($course)){ // cheking is course exist

	$course_cat_data = $DB->get_record('course_categories',array('id'=>$course->category));
	//	$course_cat_data->idnumber='ONLN'; // Please comment this line : Abhijit
	$catval = 'LN-ACAD-ABCD';
	$course_customfields = $DB->get_record('local_course_custom_fields',array('cid'=>$course->id));

	//if(!empty($course_cat_data) && $course_cat_data->idnumber== $catval) {
	if (!empty($course_customfields)) {

		// echo 'i am here';

		$course_customfields = $DB->get_record('local_course_custom_fields',array('cid'=>$course->id));
		// echo "demo:".$course_customfields->sector_about;
		// exit;



		if (!empty($course_customfields)) {
			// $context = \context_system::instance();
			if(!empty($course_customfields->slider_img_1)){
				$slider_img_1 = local_course_details_image($course_customfields->slider_img_1,'slider_img_1');


			}else{
				$slider_img_1 = new moodle_url($CFG->wwwroot.'/local/course_details/pics/course-defoult-img.jpg');
			}

			if(!empty($course_customfields->slider_img_2)){
				$slider_img_2 = local_course_details_image($course_customfields->slider_img_2,'slider_img_2');
			}else{
				$slider_img_2 = new moodle_url($CFG->wwwroot.'/local/course_details/pics/course-defoult-img.jpg');

			}

			if(!empty($course_customfields->slider_img_3)){
				$slider_img_3 = local_course_details_image($course_customfields->slider_img_3,'slider_img_3');
			}else{
				$slider_img_3 = new moodle_url($CFG->wwwroot.'/local/course_details/pics/course-defoult-img.jpg');

			}

			if(!empty($course_customfields->bulets_point_bg_img)){
				$bulets_point_bg_img = local_course_details_image($course_customfields->bulets_point_bg_img,'bulets_point_bg_img');
			}

			if(!empty($course_customfields->course_about)){
				$context = \context_system::instance();
				$course_about = file_rewrite_pluginfile_urls($course_customfields->course_about, 'pluginfile.php',$context->id, 'local_course_details', 'course_about', null);
			}

			if(!empty($course_customfields->sector_about)){
				$context = \context_system::instance();
				$sector_about = file_rewrite_pluginfile_urls($course_customfields->sector_about, 'pluginfile.php',$context->id, 'local_course_details', 'sector_about', null);
			}

			if(!empty($course_customfields->why_study)){
				$context = \context_system::instance();
				$why_study = file_rewrite_pluginfile_urls($course_customfields->why_study, 'pluginfile.php',$context->id, 'local_course_details', 'why_study', null);
			}

			if(!empty($course_customfields->is_right_course)){
				$context = \context_system::instance();
				$is_right_course = file_rewrite_pluginfile_urls($course_customfields->is_right_course, 'pluginfile.php',$context->id, 'local_course_details', 'is_right_course', null);
			}

			if(!empty($course_customfields->course_take_you)){
				$context = \context_system::instance();
				$course_take_you = file_rewrite_pluginfile_urls($course_customfields->course_take_you, 'pluginfile.php',$context->id, 'local_course_details', 'course_take_you', null);
			}

			if(!empty($course_customfields->bulets_point_text_1)){
				$context = \context_system::instance();
				$bulets_point_text_1 = file_rewrite_pluginfile_urls($course_customfields->bulets_point_text_1, 'pluginfile.php',$context->id, 'local_course_details', 'bulets_point_text_1', null);
			}

			if(!empty($course_customfields->bulets_point_text_2)){
				$context = \context_system::instance();
				$bulets_point_text_2 = file_rewrite_pluginfile_urls($course_customfields->bulets_point_text_2, 'pluginfile.php',$context->id, 'local_course_details', 'bulets_point_text_2', null);
			}
			$career_path_final = '';
			if(!empty($course_customfields->dummy_fields_1)){
				$career_path_text_arr = explode(",",$course_customfields->dummy_fields_1);
				if(!empty($career_path_text_arr)){
					$career_path = '';
					foreach ($career_path_text_arr as $key => $value) {
						if ($key!=0) {
							$arrow_icon_img_path = new moodle_url($CFG->wwwroot.'/local/course_details/pics/arrow-icon.png');
							$career_path .= html_writer::start_tag('span',array());
							$career_path .= html_writer::empty_tag('img', array('src'=>$arrow_icon_img_path, 'class'=>'arrow_icon_img'));
							$career_path .= html_writer::end_tag('span');
						}
						$career_path .= html_writer::start_tag('span',array());
						$career_path .= $value;
						$career_path .= html_writer::end_tag('span');
					}

					$career_path_final = html_writer::start_tag('h1',array());
					$career_path_final .= $career_path;
					$career_path_final .= html_writer::end_tag('h1');

				}

			}

			$templatecontext = [

									'course_name'=>$course->fullname,
									'course_summary'=>$course->summary,
									'slider_img_1'=>$slider_img_1,
									'slider_title_1'=>$course_customfields->slider_title_1,
									'slider_desc_1'=>$course_customfields->slider_desc_1,
									'slider_img_2'=>$slider_img_2,
									'slider_title_2'=>$course_customfields->slider_title_2,
									'slider_desc_2'=>$course_customfields->slider_desc_2,
									'slider_img_3'=>$slider_img_3,
									'slider_title_3'=>$course_customfields->slider_title_3,
									'slider_desc_3'=>$course_customfields->slider_desc_3,
									'course_about'=>$course_about,
									'sector_about'=>$sector_about,
									'why_study'=>$why_study,
									'is_right_course'=>$is_right_course,
									'course_take_you'=>$course_take_you,
									'length'=>$course_customfields->length,
									'effort'=>$course_customfields->effort,
									'mode'=>$course_customfields->mode,
									'level'=>$course_customfields->level,
									'bulets_point_bg_img'=>$bulets_point_bg_img,
									'bulets_point_text_1'=>$bulets_point_text_1,
									'bulets_point_text_2'=>$bulets_point_text_2,
									'career_path'=>$career_path_final
								];


			$pagecontaint = $OUTPUT->render_from_template('local_course_details/course_details_new', $templatecontext);
		}

	}else{


		$coursedetails_arr = array();
		//find the course image
		$courseimg = get_course_image_new($course);

		$coursedetails_arr['course_name'] = $course->fullname;
		$coursedetails_arr['summary'] = $course->summary;
		$coursedetails_arr['course_image_url'] = $courseimg;

		$sql = 'SELECT cd.id, cd.fieldid, cf.shortname, cf.name, cd.instanceid, cd.value
				FROM {customfield_data} AS cd
				INNER JOIN {customfield_field} AS cf ON cd.fieldid=cf.id
				WHERE instanceid=?';

		$course_extradetails = $DB->get_records_sql($sql ,array($courseid)); //find the course extra details details
		$course_extradetails_arr1 = array();
		$course_extradetails_arr2 = array();

		if(!empty($course_extradetails)){
			foreach ($course_extradetails as $cdid => $course_extradetail) {

				if($course_extradetail->shortname=='brief_jobdescription' || $course_extradetail->shortname=='personal_ttributes'){
					$course_extradetails_arr1[] = array('name'=>$course_extradetail->name, 'value'=>$course_extradetail->value);
				}else{
					if($course_extradetail->shortname!='bucket'){
						$course_extradetails_arr2[] = array('name'=>$course_extradetail->name, 'value'=>$course_extradetail->value);
					}
				}
			}
			$coursedetails_arr['course_extradetails_arr1'] = $course_extradetails_arr1;
			$coursedetails_arr['course_extradetails_arr2'] = $course_extradetails_arr2;
			$coursedetails_arr['enrol_url'] = new moodle_url($CFG->wwwroot.'/enrol/index.php', array('id'=>$courseid));
		}

		$pagecontaint = $OUTPUT->render_from_template('local_course_details/course_details', $coursedetails_arr);
	}

}else{ // if course not exist display error message

	$errormsg = get_string('coursedetailsnotfound','local_course_details');
	$pagecontaint = $OUTPUT->notification($errormsg);
}


echo $OUTPUT->header();
echo $pagecontaint;
echo $OUTPUT->footer();
