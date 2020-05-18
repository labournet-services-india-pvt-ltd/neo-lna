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
 * @package    local_trainer_analysis
 * @copyright  Manjunath<manjunath@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */
require_once('../../config.php');
require_once('lib.php');
global $DB, $CFG, $USER;


$cmid = optional_param('cmid', 0, PARAM_INT); // Course_module ID.
$session = optional_param('session', '', PARAM_RAW); // Course_module ID.

$getcourse_cm = $DB->get_record('course_modules', array('id'=>$cmid));

$checkcourse = $DB->get_record('course', array('id' => $getcourse_cm->course));
//get cmid
$trainercongreatime = get_congrea_details_trainer_todb_again_session($checkcourse,$getcourse_cm,$session,'',$syncdate);

echo $trainercongreatime;
