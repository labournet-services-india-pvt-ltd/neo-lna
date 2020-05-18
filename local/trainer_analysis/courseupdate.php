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
//manju: this function will return total number of trainers in moodle.

require_once('../../config.php');
global $DB, $CFG, $USER;
require_login(true);

$catid = 84;
$getallcourse = $DB->get_records('course', array ('category'=>$catid));

if (!empty($getallcourse)) {
	foreach($getallcourse as $course) {
		$csid = $course->id;
			$upd = course_shortname_upd($csid);
	}
}


/* to get congrea details based on cm  this stores results in DB */
function course_shortname_upd($csid) {
	global $CFG,$DB, $USER;

		$checkrecord = $DB->get_record('course', array('id'=>$csid));
		if (!empty($checkrecord)) { // record is already present
			global $DB;
			$upduser = new stdClass();
			$upduser->id = $checkrecord->id;
			$upduser->shortname = $checkrecord->idnumber;
		//	$DB->update_record('course', $upduser);
		//	echo 'updated for'.$upduser->id;
		}
	return true;
	//return $timespent;
}
