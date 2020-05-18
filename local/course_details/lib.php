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
 function local_course_details_extend_settings_navigation(settings_navigation $nav, $context) {
 	global $CFG;

 	if ($context->contextlevel >= CONTEXT_COURSE and ($branch = $nav->get('courseadmin'))
 		and has_capability('moodle/course:update', $context)) {
 		$url = new moodle_url($CFG->wwwroot . '/local/course_details/add_course_customfields.php', array('cid' => $context->instanceid));
 		$branch->add(get_string('course_landingpage', 'local_course_details'), $url, $nav::TYPE_CONTAINER, null, 'course_details' . $context->instanceid, new pix_icon('i/settings', ''));
 	}
 }

 /**
  * Hook function to extend the course settings navigation. Call all context functions
  */
 // function local_course_details_extend_navigation_course($parentnode, $course, $context) {
 //   global $CFG;
 //   $url = new moodle_url($CFG->wwwroot . '/local/course_details/index.php', array('cid' => $context->instanceid));
 //   $parentnode->add(get_string('course_landingpage', 'local_course_details'), $url, $nav::TYPE_CONTAINER, null, 'course_details' . $context->instanceid, new pix_icon('i/settings', ''));
 // }

function get_course_image_new_ll($course) {
    global $CFG;
    $courseinlist = new \core_course_list_element($course);
    foreach ($courseinlist->get_course_overviewfiles() as $file) {
        if ($file->is_valid_image()) {
            $pathcomponents = [
                '/pluginfile.php',
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea() . $file->get_filepath() . $file->get_filename()
            ];
            $path = implode('/', $pathcomponents);
            return (new moodle_url($path))->out();
        }
    }
    return false;
}

/**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
     function get_course_image_new($course) {
        global $CFG, $PAGE;
        $courseinlist = new \core_course_list_element($course);
        $contentimage = '';
        foreach ($courseinlist->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {


                    $contentimage  = $url;
                    break;
            }
        }

        $defaultcourseimage = (empty($PAGE->theme->setting_file_url('defaultcourseimage', 'defaultcourseimage'))) ? false : $PAGE->theme->setting_file_url('defaultcourseimage', 'defaultcourseimage');

        /* Default course image enabled */
        if (empty($contentimage) && $defaultcourseimage){

                $contentimage = $defaultcourseimage;

        }

        /* No course image */
        if (empty($contentimage) && !$defaultcourseimage) {

            //$contentimage .= "&#xE80C;";

        }

        return $contentimage;
    }



// insert course_custom_fielddata

    function insert_datas($data){
        global $DB;

        $insert  = new stdClass();
        $insert->cid = $data->cid;
        $insert->slider_img_1 = $data->slider_img_1;
        $insert->slider_title_1 = $data->slider_title_1;
        $insert->slider_desc_1 = $data->slider_desc_1;
        $insert->slider_img_2 = $data->slider_img_2;
        $insert->slider_title_2 = $data->slider_title_2;
        $insert->slider_desc_2 = $data->slider_desc_2;
        $insert->slider_img_3 = $data->slider_img_3;
        $insert->slider_title_3 = $data->slider_title_3;
        $insert->slider_desc_3 = $data->slider_desc_3;
        $insert->course_about = $data->course_about['text'];
        $insert->sector_about = $data->sector_about['text'];
        $insert->why_study = $data->why_study['text'];
        $insert->is_right_course = $data->is_right_course['text'];
        $insert->course_take_you = $data->course_take_you['text'];
        $insert->length = $data->length;
        $insert->effort = $data->effort;
        $insert->mode = $data->mode;
        $insert->level = $data->level;
        $insert->bulets_point_bg_img = $data->bulets_point_bg_img;
        $insert->bulets_point_text_1 = $data->bulets_point_text_1['text'];
        $insert->bulets_point_text_2 = $data->bulets_point_text_2['text'];
        $insert->dummy_fields_1 = $data->dummy_fields_1;


        $res = $DB->insert_record('local_course_custom_fields',$insert);

        if ($res) {
            return $res;
        } else {
            return false;
        }


    }

    function update_datas($data){
        global $DB;

        $update  = new stdClass();
        $update->id = $data->id;
        $update->cid = $data->cid;
        $update->slider_img_1 = $data->slider_img_1;
        $update->slider_title_1 = $data->slider_title_1;
        $update->slider_desc_1 = $data->slider_desc_1;
        $update->slider_img_2 = $data->slider_img_2;
        $update->slider_title_2 = $data->slider_title_2;
        $update->slider_desc_2 = $data->slider_desc_2;
        $update->slider_img_3 = $data->slider_img_3;
        $update->slider_title_3 = $data->slider_title_3;
        $update->slider_desc_3 = $data->slider_desc_3;
        $update->course_about = $data->course_about['text'];
        $update->sector_about = $data->sector_about['text'];
        $update->why_study = $data->why_study['text'];
        $update->is_right_course = $data->is_right_course['text'];
        $update->course_take_you = $data->course_take_you['text'];
        $update->length = $data->length;
        $update->effort = $data->effort;
        $update->mode = $data->mode;
        $update->level = $data->level;
        $update->bulets_point_bg_img = $data->bulets_point_bg_img;
        $update->bulets_point_text_1 = $data->bulets_point_text_1['text'];
        $update->bulets_point_text_2 = $data->bulets_point_text_2['text'];
        $update->dummy_fields_1 = $data->dummy_fields_1;


        $res = $DB->update_record('local_course_custom_fields',$update);

        if ($res) {
            return $res;
        } else {
            return false;
        }


    }


    function local_course_details_image($itemid,$filearea){
    // global $DB,$CFG;
    if(!empty($itemid)){
        $context = context_system::instance();
        $contextid = $context->contextlevel;
        global $USER;
        $component = 'local_course_details';

        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, $component, $filearea, $itemid,'sortorder', false);

        if(!empty($files)){
            $count = count($files);
            if($count>1)
            {
                  $url2 =[];
                foreach($files as $file) {
                    $file->get_filename();
                    $url2[] = moodle_url::make_pluginfile_url(
                        $file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename()
                    );

                }
            }
            else
            {
                 $url2 ="";
                foreach($files as $file) {
                    $file->get_filename();
                    $url2 = moodle_url::make_pluginfile_url(
                        $file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename()
                    );

                }
            }

            return $url2;
        }
    }
}



    // function local_course_details_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
function local_course_details_pluginfile($course, $birecordorcm, $context, $filearea, $args, $forcedownload, array $options = array()) {

    // // Make sure the filearea is one of those used by the plugin.
    // if ($filearea !== 'catimage') {
    //     return false;
    // }
    //     // Make sure the filearea is one of those used by the plugin.
    // if ($filearea !== 'catlogo') {
    //     return false;
    // }

    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    // require_login();
        // Make sure the filearea is one of those used by the plugin.


    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    // $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.
    if ($filearea !== 'course_about' && $filearea !== 'sector_about' && $filearea !== 'why_study' && $filearea !== 'is_right_course' && $filearea !== 'course_take_you' && $filearea !== 'bulets_point_text_1' && $filearea !== 'bulets_point_text_2') {
        $itemid = array_shift($args); // The first item in the $args array.

        // Extract the filename / filepath from the $args array.
        $filename = array_pop($args); // The last item in the $args array.
        if (!$args) {
            $filepath = '/'; // $args is empty => the path is '/'
        } else {
            $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
        }

        // Retrieve the file from the Files API.
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'local_course_details', $filearea, $itemid, $filepath, $filename);
        if (!$file) {
            return false; // The file does not exist.
        }

        // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
        // From Moodle 2.3, use send_stored_file instead.
        //send_stored_file($file, 0, 0, true, $options); // download MUST be forced - security!

        $forcedownload = true;

        send_file($file, $file->get_filename(), true, $forcedownload, $options);

    }else{
        $fs = get_file_storage();

        $filename = array_pop($args);
        $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

        if (!$file = $fs->get_file($context->id, 'local_course_details', $filearea, 0, $filepath, $filename) or $file->is_directory()) {
            send_file_not_found();
        }

        \core\session\manager::write_close();
        send_stored_file($file, null, 0, $forcedownload, $options);

    }

}

// function local_course_details_pluginfile($course, $birecordorcm, $context, $filearea, $args, $forcedownload, array $options = array()) {
//     $fs = get_file_storage();

//     $filename = array_pop($args);
//     $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

//     if (!$file = $fs->get_file($context->id, 'local_course_details', $filearea, 0, $filepath, $filename) or $file->is_directory()) {
//         send_file_not_found();
//     }

//     \core\session\manager::write_close();
//     send_stored_file($file, null, 0, $forcedownload, $options);
// }
