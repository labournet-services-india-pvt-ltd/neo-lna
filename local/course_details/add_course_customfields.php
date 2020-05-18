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
 * @package    local_other_pages
 * @copyright  Manoj Prabahar<manojprabahar@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2020 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once('form/course_customfield_form.php');

global $DB,$OUTPUT;

require_once($CFG->libdir.'/formslib.php');
defined('MOODLE_INTERNAL') || die();
require_login();
$cid = required_param('cid', PARAM_INT); //course id required for this page
$context = context_course::instance($cid);
$course = $DB->get_record('course', array('id'=>$cid));
require_login($course);

$PAGE->set_pagelayout('standard');
$PAGE->set_context($context);
$PAGE->set_url('/local/course_details/add_course_customfields.php',array('cid'=>$cid));
// $local = get_string('local','local_other_pages');
// $url = 'local/moodle';
// $mform = '';
// $mform1 = '';


$course_details = $DB->get_record('local_course_custom_fields',array('cid'=>$cid));
if(!empty($course_details)){
    $title = get_string('update_course_custom_fields', 'local_course_details');

}else{
    $title = get_string('add_course_custom_fields', 'local_course_details');
}

$PAGE->set_title($title);
$PAGE->set_heading($title);

$mform  =  new local_course_customfield_form($CFG->wwwroot.'/local/course_details/add_course_customfields.php?cid='.$cid);
$data = $mform->get_data();
$returnurl = new moodle_url('/course/view.php',array('cid'=>$cid));

if($mform->is_cancelled()){
	redirect($returnurl);
}else{
	if(!empty($data)){
		// $context = context_system::instance();
		$data->cid = $cid;
		$context = context_system::instance();

		$data->course_about['text'] = file_save_draft_area_files($data->course_about['itemid'], $context->id,
                'local_course_details', 'course_about',
                0, array('subdirs' => true), $data->course_about['text']);

		$data->sector_about['text'] = file_save_draft_area_files($data->sector_about['itemid'], $context->id,
                'local_course_details', 'sector_about',
                0, array('subdirs' => true), $data->sector_about['text']);

		$data->why_study['text'] = file_save_draft_area_files($data->why_study['itemid'], $context->id,
                'local_course_details', 'why_study',
                0, array('subdirs' => true), $data->why_study['text']);

		$data->is_right_course['text'] = file_save_draft_area_files($data->is_right_course['itemid'], $context->id,
                'local_course_details', 'is_right_course',
                0, array('subdirs' => true), $data->is_right_course['text']);

		$data->course_take_you['text'] = file_save_draft_area_files($data->course_take_you['itemid'], $context->id,
                'local_course_details', 'course_take_you',
                0, array('subdirs' => true), $data->course_take_you['text']);

		$data->bulets_point_text_1['text'] = file_save_draft_area_files($data->bulets_point_text_1['itemid'], $context->id,
                'local_course_details', 'bulets_point_text_1',
                0, array('subdirs' => true), $data->bulets_point_text_1['text']);

		$data->bulets_point_text_2['text'] = file_save_draft_area_files($data->bulets_point_text_2['itemid'], $context->id,
                'local_course_details', 'bulets_point_text_2',
                0, array('subdirs' => true), $data->bulets_point_text_2['text']);


		$contextid = $context->contextlevel;
		$slider_img_1 = $data->slider_img_1;
		$slider_img_2 = $data->slider_img_2;
		$slider_img_3 = $data->slider_img_3;
		$bulets_point_bg_img = $data->bulets_point_bg_img;

		if(!empty($slider_img_1)){

			file_save_draft_area_files($slider_img_1,$contextid,'local_course_details','slider_img_1',$slider_img_1,array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		}

		if(!empty($slider_img_2)){

			file_save_draft_area_files($slider_img_2,$contextid,'local_course_details','slider_img_2',$slider_img_2,array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		}

		if(!empty($slider_img_3)){

			file_save_draft_area_files($slider_img_3,$contextid,'local_course_details','slider_img_3',$slider_img_3,array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		}

		if(!empty($bulets_point_bg_img)){

			file_save_draft_area_files($bulets_point_bg_img,$contextid,'local_course_details','bulets_point_bg_img',$bulets_point_bg_img,array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		}



		if(isset($data->id)){
			$update_datas = update_datas($data);
		}else{
			$insert_datas = insert_datas($data);

		}

		$url = new moodle_url ('/local/course_details/add_course_customfields.php',array('cid'=>$cid));
		$redirect = redirect($url);
	}
}

if(!empty($course_details)){
	$dataobject = new stdClass();
	$dataobject->cid = $cid;
	if(!empty($course_details->slider_img_1)){
		$context = context_system::instance();
		$contextid = $context->contextlevel;
		$slider_img_1_draftitemid = file_get_submitted_draft_itemid('slider_img_1');
			file_prepare_draft_area($slider_img_1_draftitemid, $contextid, 'local_course_details', 'slider_img_1', $course_details->slider_img_1, array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		$dataobject->slider_img_1 = $slider_img_1_draftitemid;

	}
	$dataobject->slider_title_1 = $course_details->slider_title_1;
	$dataobject->slider_desc_1 = $course_details->slider_desc_1;

	if(!empty($course_details->slider_img_2)){
		$context = context_system::instance();
		$contextid = $context->contextlevel;
		$slider_img_2_draftitemid = file_get_submitted_draft_itemid('slider_img_2');
			file_prepare_draft_area($slider_img_2_draftitemid, $contextid, 'local_course_details', 'slider_img_2', $course_details->slider_img_2, array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		$dataobject->slider_img_2 = $slider_img_2_draftitemid;

	}
	$dataobject->slider_title_2 = $course_details->slider_title_2;
	$dataobject->slider_desc_2 = $course_details->slider_desc_2;

	if(!empty($course_details->slider_img_3)){
		$context = context_system::instance();
		$contextid = $context->contextlevel;
		$slider_img_3_draftitemid = file_get_submitted_draft_itemid('slider_img_3');
			file_prepare_draft_area($slider_img_3_draftitemid, $contextid, 'local_course_details', 'slider_img_3', $course_details->slider_img_3, array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		$dataobject->slider_img_3 = $slider_img_3_draftitemid;

	}
	$dataobject->slider_title_3 = $course_details->slider_title_3;
	$dataobject->slider_desc_3 = $course_details->slider_desc_3;

	$dataobject->course_about['text'] = $course_details->course_about;
	$dataobject->sector_about['text'] = $course_details->sector_about;
	$dataobject->why_study['text'] = $course_details->why_study;
	$dataobject->is_right_course['text'] = $course_details->is_right_course;
	$dataobject->course_take_you['text'] = $course_details->course_take_you;

	$dataobject->length = $course_details->length;
	$dataobject->effort = $course_details->effort;
	$dataobject->mode = $course_details->mode;
	$dataobject->level = $course_details->level;

	if(!empty($course_details->bulets_point_bg_img)){
		$context = context_system::instance();
		$contextid = $context->contextlevel;
		$bulets_point_bg_img_draftitemid = file_get_submitted_draft_itemid('bulets_point_bg_img');
			file_prepare_draft_area($bulets_point_bg_img_draftitemid, $contextid, 'local_course_details', 'bulets_point_bg_img', $course_details->bulets_point_bg_img, array('subdirs' => 0, 'maxbytes' => '*', 'maxfiles' => 50));
		$dataobject->bulets_point_bg_img = $bulets_point_bg_img_draftitemid;

	}

	$dataobject->bulets_point_text_1['text'] = $course_details->bulets_point_text_1;
	$dataobject->bulets_point_text_2['text'] = $course_details->bulets_point_text_2;
	$dataobject->dummy_fields_1 = $course_details->dummy_fields_1;


	$mform->set_data($dataobject);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
