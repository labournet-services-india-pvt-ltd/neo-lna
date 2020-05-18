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
require_once('upload_essci_users_form.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_login(0,false);
$capadmin = is_siteadmin();
$context = context_system::instance();
$PAGE->set_context(context_system::instance());
$title = get_string('uploadhrd', 'tool_uploaduser');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');
//$PAGE->set_url('/tool/uploaduser/upload_essci_file.php');
require_capability('moodle/site:uploadusers', context_system::instance());
$PAGE->set_url("$CFG->wwwroot/$CFG->admin/tool/uploaduser/upload_essci_file.php");
$PAGE->requires->jquery();		
//$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/updatepromocode/js/select.js'));
require_login();
$PAGE->navbar->add($title);
echo $OUTPUT->header();
$mform = new tool_upload_essci_users_form();
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
	//print_object($columns);
	$validation = array();
	$updatevalues = [];
	$xx = $DB->get_records_menu('user_info_field',null,null,'id,shortname');
	while ($line = $cir->next()) {
		$linenum++;
		if(empty($line)) {
			continue;
		}
		//user values insert
		if($line){
			if(!($DB->record_exists('user',array('username'=>$line[0],'email'=>$line[3])))){
				$insert = new stdClass();
				$insert->username = $line[0];
				//new code is added by prashant on 24-2020
				$insert->password = hash_internal_user_password($line[4],true);
				$insert->firstname =$line[1];
				$insert->lastname = $line[2];
				$insert->email = $line[3];
				$insert->mnethostid = 1;
				$insert->confirmed = 1;
				$insert->timecreated = time();
				$insert->timemodified = time();
				$DB->insert_record('user',$insert);
				$flag = 1;
			}
			$coursecheck1= $DB->get_record('user',array('username'=>$line[0],'email'=>$line[3]));
			if(!empty($coursecheck1)){
				$i = 5;
				foreach ($xx as $key => $value) {
					if(!empty($line[$i])){
						$insert1  = new stdClass();
						foreach ($columns as $key1 => $value1) {
							if($value==$value1){
								//echo 'matching value='.$value.'=='.$value1.'storing value in database='.$line[$key];
								$insert1->userid =$coursecheck1->id ;
								$insert1->fieldid =$key ;
								$insert1->data = $line[$key1];
								$insert1->dataformat	 ='';
								//print_object($insert1);
								$DB->insert_record('user_info_data',$insert1);
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
			get_string('insert', 'tool_uploaduser'),'alert alert-success'
		);
	}

}

	$mform->display();

echo $OUTPUT->footer();
?>






