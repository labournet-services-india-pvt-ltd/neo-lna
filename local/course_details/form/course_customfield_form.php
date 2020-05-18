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
 * @package    local_course_details
 * @author     Manjunath B K <manjunaathbk@elearn10.com>
 * @license    Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @copyright  http://www.lmsofindia.com 2017 or later
 */
global $DB;
require_once($CFG->libdir . '/formslib.php');


class local_course_customfield_form extends moodleform {

    public function set_data($defaults) {
        $context = context_system::instance();

        $course_about_draftideditor = file_get_submitted_draft_itemid('course_about');
        $defaults->course_about['text'] = file_prepare_draft_area($course_about_draftideditor, $context->id,
            'local_course_details', 'course_about', 0, array('subdirs' => true), $defaults->course_about['text']);
        $defaults->course_about['itemid'] = $course_about_draftideditor;
        $defaults->course_about['format'] = FORMAT_HTML;

        $sector_about_draftideditor = file_get_submitted_draft_itemid('sector_about');
        $defaults->sector_about['text'] = file_prepare_draft_area($sector_about_draftideditor, $context->id,
            'local_course_details', 'sector_about', 0, array('subdirs' => true), $defaults->sector_about['text']);
        $defaults->sector_about['itemid'] = $sector_about_draftideditor;
        $defaults->sector_about['format'] = FORMAT_HTML;

        $why_study_draftideditor = file_get_submitted_draft_itemid('why_study');
        $defaults->why_study['text'] = file_prepare_draft_area($why_study_draftideditor, $context->id,
            'local_course_details', 'why_study', 0, array('subdirs' => true), $defaults->why_study['text']);
        $defaults->why_study['itemid'] = $why_study_draftideditor;
        $defaults->why_study['format'] = FORMAT_HTML;

        $is_right_course_draftideditor = file_get_submitted_draft_itemid('is_right_course');
        $defaults->is_right_course['text'] = file_prepare_draft_area($is_right_course_draftideditor, $context->id,
            'local_course_details', 'is_right_course', 0, array('subdirs' => true), $defaults->is_right_course['text']);
        $defaults->is_right_course['itemid'] = $is_right_course_draftideditor;
        $defaults->is_right_course['format'] = FORMAT_HTML;

        $course_take_you_draftideditor = file_get_submitted_draft_itemid('course_take_you');
        $defaults->course_take_you['text'] = file_prepare_draft_area($course_take_you_draftideditor, $context->id,
            'local_course_details', 'course_take_you', 0, array('subdirs' => true), $defaults->course_take_you['text']);
        $defaults->course_take_you['itemid'] = $course_take_you_draftideditor;
        $defaults->course_take_you['format'] = FORMAT_HTML;

        $bulets_point_text_1_draftideditor = file_get_submitted_draft_itemid('bulets_point_text_1');
        $defaults->bulets_point_text_1['text'] = file_prepare_draft_area($bulets_point_text_1_draftideditor, $context->id,
            'local_course_details', 'bulets_point_text_1', 0, array('subdirs' => true), $defaults->bulets_point_text_1['text']);
        $defaults->bulets_point_text_1['itemid'] = $bulets_point_text_1_draftideditor;
        $defaults->bulets_point_text_1['format'] = FORMAT_HTML;

        $bulets_point_text_2_draftideditor = file_get_submitted_draft_itemid('bulets_point_text_2');
        $defaults->bulets_point_text_2['text'] = file_prepare_draft_area($bulets_point_text_2_draftideditor, $context->id,
            'local_course_details', 'bulets_point_text_2', 0, array('subdirs' => true), $defaults->bulets_point_text_2['text']);
        $defaults->bulets_point_text_2['itemid'] = $bulets_point_text_2_draftideditor;
        $defaults->bulets_point_text_2['format'] = FORMAT_HTML;

        return parent::set_data($defaults);
    }


    public function definition() {
        global $DB,$CFG,$PAGE;
        $context = context_system::instance();
        $courseid = required_param('cid', PARAM_INT); //course id required for this page

        $course_details = $DB->get_record('local_course_custom_fields',array('cid'=>$courseid));
        
        $mform =& $this->_form;
        if(!empty($course_details)){
            $formheader = get_string('update_course_custom_fields', 'local_course_details');
            $mform->addElement('hidden', 'id',$course_details->id);
            $mform->setType('id', PARAM_INT);

        }else{
            $formheader = get_string('add_course_custom_fields', 'local_course_details');
        }


        // 

        $mform->addElement('filemanager', 'slider_img_1', get_string('slider_img_1', 'local_course_details'), null,
                    array('subdirs' => 0, 'areamaxbytes' => 10485760, 'maxfiles' => 1));
        $mform->addRule('slider_img_1', get_string('selectslider_img_1','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('text', 'slider_title_1', get_string('slider_title_1', 'local_course_details'));
        $mform->addRule('slider_title_1', get_string('selectslider_title_1','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_title_1', PARAM_RAW);

        $mform->addElement('text', 'slider_desc_1', get_string('slider_desc_1', 'local_course_details'));
        $mform->addRule('slider_desc_1', get_string('selectslider_desc_1','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_desc_1', PARAM_RAW);

        // 

        $mform->addElement('filemanager', 'slider_img_2', get_string('slider_img_2', 'local_course_details'), null,
                    array('subdirs' => 0, 'areamaxbytes' => 10485760, 'maxfiles' => 1));
        $mform->addRule('slider_img_2', get_string('selectslider_img_2','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('text', 'slider_title_2', get_string('slider_title_2', 'local_course_details'));
        $mform->addRule('slider_title_2', get_string('selectslider_title_2','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_title_2', PARAM_RAW);

        $mform->addElement('text', 'slider_desc_2', get_string('slider_desc_2', 'local_course_details'));
        $mform->addRule('slider_desc_2', get_string('selectslider_desc_2','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_desc_2', PARAM_RAW);

        // 

        $mform->addElement('filemanager', 'slider_img_3', get_string('slider_img_3', 'local_course_details'), null,
                    array('subdirs' => 0, 'areamaxbytes' => 10485760, 'maxfiles' => 1));
        $mform->addRule('slider_img_3', get_string('selectslider_img_3','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('text', 'slider_title_3', get_string('slider_title_3', 'local_course_details'));
        $mform->addRule('slider_title_3', get_string('selectslider_title_3','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_title_3', PARAM_RAW);

        $mform->addElement('text', 'slider_desc_3', get_string('slider_desc_3', 'local_course_details'));
        $mform->addRule('slider_desc_3', get_string('selectslider_desc_3','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('slider_desc_3', PARAM_RAW);

        // 
        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $context);

        $mform->addElement('editor', 'course_about', get_string('course_about', 'local_course_details'),null,$editoroptions);
        // $mform->setType('course_about', PARAM_RAW);
        // $mform->addRule('course_about', get_string('selectcourse_about','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'sector_about', get_string('sector_about', 'local_course_details'),null,$editoroptions);
        // $mform->setType('sector_about', PARAM_RAW);
        // $mform->addRule('sector_about', get_string('selectsector_about','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'why_study', get_string('why_study', 'local_course_details'),null,$editoroptions);
        // $mform->setType('why_study', PARAM_RAW);
        // $mform->addRule('why_study', get_string('selectwhy_study','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'is_right_course', get_string('is_right_course', 'local_course_details'),null,$editoroptions);
        // $mform->setType('is_right_course', PARAM_RAW);
        // $mform->addRule('is_right_course', get_string('selectis_right_course','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'course_take_you', get_string('course_take_you', 'local_course_details'),null,$editoroptions);
        // $mform->setType('course_take_you', PARAM_RAW);
        // $mform->addRule('course_take_you', get_string('selectcourse_take_you','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('text', 'length', get_string('length', 'local_course_details'));
        $mform->addRule('length', get_string('select_length','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('length', PARAM_RAW);

        $mform->addElement('text', 'effort', get_string('effort', 'local_course_details'));
        $mform->addRule('effort', get_string('select_effort','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('effort', PARAM_RAW);

        $mform->addElement('text', 'mode', get_string('mode', 'local_course_details'));
        $mform->addRule('mode', get_string('select_mode','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('mode', PARAM_RAW);

        $mform->addElement('text', 'level', get_string('level', 'local_course_details'));
        $mform->addRule('level', get_string('select_level','local_course_details'), 'required', '', 'client', false, false);
        $mform->setType('level', PARAM_RAW);

        $mform->addElement('filemanager', 'bulets_point_bg_img', get_string('bulets_point_bg_img', 'local_course_details'), null,
                    array('subdirs' => 0, 'areamaxbytes' => 10485760, 'maxfiles' => 1));
        $mform->addRule('bulets_point_bg_img', get_string('selectbulets_point_bg_img','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'bulets_point_text_1', get_string('bulets_point_text_1', 'local_course_details'),null,$editoroptions);
        $mform->addRule('bulets_point_text_1', get_string('selectbulets_point_text_1','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('editor', 'bulets_point_text_2', get_string('bulets_point_text_2', 'local_course_details'),null,$editoroptions);
        $mform->addRule('bulets_point_text_2', get_string('selectbulets_point_text_2','local_course_details'), 'required', '', 'client', false, false);

        $mform->addElement('textarea', 'dummy_fields_1', get_string("career_path", "local_course_details"), 'wrap="virtual" rows="4" cols="50"');

        
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        // $buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('revert'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

        // $mform->addElement('submit', 'submit', get_string('submit', 'local_course_details'));
    }
}
