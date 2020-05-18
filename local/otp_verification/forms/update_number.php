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
 * You may have settings in your plugin
 *
 * @copyright  Manjunath<manjunath@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); /// It must be included from a Moodle page
}
global $OUTPUT, $CFG;
require_once($CFG->libdir.'/formslib.php');

class local_update_mobileno extends moodleform {
    function definition() {
        global $CFG,$DB,$USER,$PAGE,$OUTPUT;
        $username = optional_param('username','',PARAM_RAW);
        $userobject = $DB->get_record('user',array('username'=>$username));
        $id = $userobject->id;
        $mform =& $this->_form;
        $mform->addElement('hidden', 'id',$id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'mobilephone', get_string('phoneno', 'local_otp_verification'));
        $mform->setType('mobilephone', PARAM_INT);

        $mform->addElement('submit', 'submitbutton', get_string('submit', 'local_otp_verification'));
    }
}