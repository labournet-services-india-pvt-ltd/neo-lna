<?php
// This file is part of Moodle - http://moodle.org/
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
 * Auth email external functions tests.
 *
 * @package    rhwservice
 * @category   external
 * @copyright  2019 June Sangita
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.7
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
/**
 * Sangita :Edutimeline: july 29 2019
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php)
 * This function will create mform extra fields
 * for activites core form
 *
 * @param stdClass $forum add forum instance
 * @param $formwrapper form wrapper
 * @return attached extra fields in activity form
 */
function local_edutimeline_coursemodule_standard_elements($formwrapper, $mform){
	global $CFG, $COURSE,$DB;
//   if (!get_config('core_competency', 'enabled')) {
//     return;
// } else if (!has_capability('moodle/competency:coursecompetencymanage', $formwrapper->get_context())) {
//     return;
// }
$mform->addElement('header', 'edutimelineheader', get_string('edutimeline', 'local_edutimeline'));
$options = array();
$options['OTHER'] = get_string('others','local_edutimeline');
$options['TRAINING'] = get_string('training','local_edutimeline');
$options['READING'] = get_string('reading','local_edutimeline');
$options['SOCIAL'] = get_string('social','local_edutimeline');
$options['VIDEO'] = get_string('video','local_edutimeline');
$options['ACTIVITY'] = get_string('activity','local_edutimeline');
$options['DISCUSSION'] = get_string('discussion','local_edutimeline');
$options['KNOWLEDGECHECK'] = get_string('knowledgecheck','local_edutimeline');
$mform->addElement('select', 'edutypes', get_string('edutype','local_edutimeline'), $options);
$mform->setType('edutypes', PARAM_RAW);

$options2 = array();
$options2['0'] = get_string('yes','local_edutimeline');
$options2['1'] = get_string('no','local_edutimeline');
$mform->addElement('select', 'requiredtype', get_string('requiredtype','local_edutimeline'), $options2);
$mform->setType('requiredtype', PARAM_INT);

$mform->addElement('text', 'eduopentime', get_string('eduopentime', 'local_edutimeline'));
$mform->setType('eduopentime', PARAM_RAW);
$mform->addRule('eduopentime', get_string('numericdata','local_edutimeline'), 'numeric', null, null);
$mform->addHelpButton('eduopentime', 'eduopentime', 'local_edutimeline');
$cmid = null;
if ($cm = $formwrapper->get_coursemodule()) {
    $cmid = $cm->id;
    $timelinedetails = $DB->get_record('edutimeline',array('cmid'=>$cmid));
    if(!empty($timelinedetails)){
     $timelinedata  = new stdClass();
     $timelinedata->edutypes = $timelinedetails->type;
     $timelinedata->eduopentime = $timelinedetails->time/60;//time convert second to minut
     $formwrapper->set_data($timelinedata);
 }
}
}
/**Sangita :Edutimeline
 * This function will get mform fields
 * data
 *
 * @param stdClass $data all mform data contains
 * @param $course course object
 * @return call function for insert and updata edutimeline table
 */
function local_edutimeline_coursemodule_edit_post_actions($data,$course){
	global $CFG,$DB,$USER;

	$cid = $course->id; //course id
    $moduleid = $data->module;//module id
    $instanceid = $data->instance;//instance id
    //first time cmid is not available
    $findcmdetils = $DB->get_record('course_modules',array('course'=>$cid,'module'=>$moduleid,'instance'=>$instanceid));
    if(!empty($findcmdetils)){
    	$cmid = $findcmdetils->id;
    	$timelinedetails = $DB->get_record('edutimeline',array('cmid'=>$cmid));
    	if(!empty($timelinedetails)){
        $id = $timelinedetails->id;//activity update id

    		//calling function for updata fields data in edutimeline table
    		update_edutimeline($data,$id);
    	}else{
    		//calling function for insert fields data in edutimeline table
          insert_edutimeline($data,$cmid);
      }
  }
}
/**Sangita :Edutimeline
 * This function will insert edutimeline table fields
 * data
 *
 * @param stdClass $data all mform data contains
 * @param $cmid course module id
 * @return  insert data into table
 */
function insert_edutimeline($data,$cmid){
	global $DB,$USER;
    $timedata = '';
    $vediotime = '';
    if(!empty($data->edutypes)){
      $typeval = $data->edutypes;//selected type (0 = other,1=traning,2=reading)
    }else{
      $typeval = '';
    }
    //check activity is a video ortypeval not

    if(!empty($data)){
      // if($data->modulename == 'hvp'){
      //  // $mid = $data->id; // this gives me the id of mdl_module table.
      //   $vediotime = get_vimeo_video_total_duration($cmid);
			//
      // }else{
      //   //check typeval is other or not if other then not insert data into database.
      //   if (!empty($data->eduopentime)) {
      //     $vediotime = $data->eduopentime*60;//convert into second
      //   }
      // }
			//check typeval is other or not if other then not insert data into database.
			if (!empty($data->eduopentime)) {
				$vediotime = $data->eduopentime*60;//convert into second
			}
			$requiredtype = $data->requiredtype;

      $timedata = get_insert_edutimeline_object($cmid,$typeval,$vediotime,$requiredtype);
       if(!empty($timedata)){
          $timedata= $DB->insert_record('edutimeline',$timedata);
       }
    }
}
//Sangita :Edutimeline: 19 August 2019:
//this function is used to find vimeo vedio item id
//$filepth is vimeo url param
//this function returns vimeo vedio id.
function get_vimeo_video_total_duration_edutimeline($cmid){
  global $DB;
  $coursemuduleinfo = $DB->get_record('course_modules',array('id'=>$cmid),'instance');
  $hvpid = $coursemuduleinfo->instance;
  $videodetails = $DB->get_record('hvp',array('id'=>$hvpid),'totalduration');
  $videoduration = $videodetails->totalduration;
  return $videoduration;
}
//Sangita :Edutimeline:
// this function receives all data and create the object so that it can be inserted
//$cmid is course module id
//$selecttype is activity type in edutimeline
//$vediotime is total vedio duration
//return object of edutimeline data
function get_insert_edutimeline_object($cmid,$selecttype=null,$vediotime=null,$requiredtype=null){
    global $USER;
    $timedata = new stdClass();
    $timedata->cmid = $cmid;
    $timedata->type = $selecttype;
    $timedata->requiredtype = $requiredtype;
    $timedata->time = $vediotime;
    $timedata->modifiedby = $USER->id;
    $timedata->timecreated = time();
    $timedata->timemodified = time();
    return $timedata;
}

/**Sangita :Edutimeline
 * This function will update edutimeline fields
 * data when mform editing
 *
 * @param stdClass $data all mform data contains
 * @param $timelineid edutimeline table id
 * @return updata edutimeline table
 */
function update_edutimeline($data,$timelineid){
  global $DB,$USER;
  $cmid = $data->coursemodule;//course module id
  $typeval = $data->edutypes; //selected type (0 = other,1=traning,2=reading)
  $modulename = $data->modulename;
  // if($modulename == 'hvp'){
  //     $videoduration = get_vimeo_video_total_duration_edutimeline($cmid);
  //     $updatedetails = get_update_edutimeline_object($cmid,$typeval,$timelineid,$videoduration);
  // }else{
  //   if(!empty($data->eduopentime)){
  //     $videoduration = $data->eduopentime *60;//convert into second
  //   }else{
  //     $videoduration = 0;
  //   }
  //     $updatedetails = get_update_edutimeline_object($cmid,$typeval,$timelineid,$videoduration);
	//
  // }
	if(!empty($data->eduopentime)){
		$videoduration = $data->eduopentime *60;//convert into second
	}else{
		$videoduration = 0;
	}
	$requiredtype = $data->requiredtype;
		$updatedetails = get_update_edutimeline_object($cmid,$typeval,$timelineid,$videoduration, $requiredtype);
  if(!empty($updatedetails)){
    $updatedat = $DB->update_record('edutimeline',$updatedetails);
  }
}
//Sangita :Edutimelin
//this function is used to create object for update record.
//$cmid is course module id
//$typeval is activity type in edutimeline
//$timelineid is edutimeline id
//$videoduration is total vedio duration
//return updatetimedata object for update edutimeline data
function get_update_edutimeline_object($cmid,$typeval,$timelineid=null,$videoduration=null,$requiredtype = null){
    global $USER;
    $updatetimedata = new stdClass();
    $updatetimedata->id = $timelineid;
    $updatetimedata->cmid = $cmid;
    $updatetimedata->type = $typeval;
		$updatetimedata->requiredtype = $requiredtype;
    $updatetimedata->time = $videoduration;
    $updatetimedata->modifiedby = $USER->id;
    $updatetimedata->timecreated = time();
    $updatetimedata->timemodified = time();
    return $updatetimedata;
}
