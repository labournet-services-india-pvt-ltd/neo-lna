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
 * @package    local_updatepromocode
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

require('../../../config.php');
require_once('uploadcourses_form.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_login(0,false);
$capadmin = is_siteadmin();
$context = context_system::instance();
//$addpromocode = has_capability('/updatepromocode:deletepromo',$context);
$PAGE->set_context(context_system::instance());
$title = get_string('uploadhrd', 'tool_uploadcourse');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
$PAGE->set_url("$CFG->wwwroot/$CFG->admin/tool/uploadcourse/uploadfile.php");
require_capability('moodle/course:create', context_system::instance());
require_login();
$PAGE->navbar->add($title);
echo $OUTPUT->header();
$mform = new tool_uploadcourses_form();
$flag = 0;
if ($mform->is_cancelled()){
	redirect(new moodle_url('/', array()));
} else if ($data = $mform->get_data()) {
	$iid = csv_import_reader::get_new_iid('uploaduser');
	$cir = new csv_import_reader($iid, 'uploaduser');
	$content = $mform->get_file_content('uploadfile');
	$readcount = $cir->load_csv_content($content, $data->encoding, $data->delimiter_name);
	$csvloaderror = $cir->get_error();
	$cir->init();
	$linenum = 1;
        // init upload progress tracker
	$columns = $cir->get_columns();	
	$validation = array();
	$updatevalues = [];
	//$xx = $DB->get_records('customfield_field');
	//new code is added by prashant on 14-jan-2020
	$xx = $DB->get_records_menu('customfield_field',null,null,'id,shortname');
	while ($line = $cir->next()) {
		$linenum++;
		if(empty($line)) {
			continue;
		}
		if($line){
			if(!($DB->record_exists('course',array('shortname'=>$line[2])))){
				$insert = new stdClass();
				$insert->category = $line[0];
				$insert->fullname = $line[1];
				$insert->shortname =$line[2];
				$insert->summary = $line[3];
				$insert->timecreated = time();
				$insert->timemodified = time();
				$DB->insert_record('course',$insert);
				$flag = 1;
			}
			//INERT VALUES IN CUSTOM FILED 
			$coursecheck1= $DB->get_record('course',array('shortname'=>$line[2],'category'=>$line[0]));
			if(!empty($coursecheck1)){
				$context = \context_course::instance($coursecheck1->id);
				$i = 4;
				foreach ($xx as $key => $value) {
					if(!empty($line[$i])){
						$insert1 = new stdClass();
						foreach ($columns as $key1 => $value1) {
							if($value==$value1){
								$insert1->fieldid = $key;
								$insert1->instanceid = $coursecheck1->id;
								if($i==6 or $i==15 or $i==16){
									$insert1->charvalue = Null;
									$insert1->intvalue = $line[$i];
								}else{
									$insert1->charvalue = $line[$i];
									$insert1->intvalue = Null;
								}								
								$insert1->value = $line[$i];
								$insert1->valueformat = 0;
								$insert1->timecreated = time();
								$insert1->timemodified = time();
								$insert1->contextid = $context->id;
								$DB->insert_record('customfield_data',$insert1);
								$flag =1;
							}
							
						}
						
					}					
					$i++;
				}
			}			
		}
	}
	
    //array value updateing here 
	if($flag==1){
		echo html_writer::div(
			get_string('insert', 'tool_uploadcourse'),'alert alert-success'
		);
	}

}
$mform->display();
echo $OUTPUT->footer();
?>






