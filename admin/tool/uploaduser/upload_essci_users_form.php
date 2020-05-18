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
 * @package    tool_uploaduser
 * @copyright  Prashant Yallatti<prashant@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); /// It must be included from a Moodle 
}
require_once($CFG->libdir.'/formslib.php');

class tool_upload_essci_users_form extends \moodleform {
	function definition() {
		global $CFG,$DB,$USER,$PAGE,$OUTPUT;
        $mform =& $this->_form; 
        $mform->addElement('header','uploadhrd1',get_string('uploadhrd','tool_uploaduser'));
        $mform->setExpanded('uploadhrd1');

        /*$maxbytes = 10485760;
         $mform->addElement('filemanager', 'attachments', 'xxxxxxxx', null,hrd
                    array('subdirs' => 0, 'maxbytes' => $maxbytes, 'areamaxbytes' => 10485760, 'maxfiles' => 50,
                          'accepted_types' => array('*'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));*/


        $mform->addElement('filepicker', 'uploadfile', get_string('uploadfile','tool_uploaduser'), null,
          '');
        $mform->addRule('uploadfile', null, 'required');

       


        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');
        $mform->addElement('hidden', 'showpreview', 1);
        $mform->setType('showpreview', PARAM_INT);
        $this->add_action_buttons();
    }
}

