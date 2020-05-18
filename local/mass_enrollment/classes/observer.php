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
 * This plugin sends users a welcome message after logging in
 * and notify a moderator a new user has been added
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage cs_reminder
 * @copyright  Manjunath
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_mass_enrollment;
global $CFG;
include_once($CFG->dirroot.'/user/profile/lib.php');
defined('MOODLE_INTERNAL') || die();
class observer {

	public static function user_mass_enroll(\core\event\user_updated $event) {
		global $CFG, $SITE,$DB,$USER;
		$eventdata = $event->get_data();
		$user = \core_user::get_user($eventdata['objectid']);
        //get all enrolled courses of this user.
		$userscourses = enrol_get_users_courses($user->id);
		$alreadyenrolled = [];
		foreach ($userscourses as $ucourse) {
			$alreadyenrolled[] = $ucourse->id;
		}
        //get custom field id for user role and sub sector fields.
		$userroleid = $DB->get_field('user_info_field', 'id', array('shortname'=>'userrole'));
		$subsectorid = $DB->get_field('user_info_field', 'id', array('shortname'=>'subsector'));
        //get custom field data for perticular user and custom field ids.
		$roledata = $DB->get_field('user_info_data', 'data', array('userid'=>$user->id,'fieldid'=>$userroleid));
		$subsectordata = $DB->get_field('user_info_data', 'data', array('userid'=>$user->id,'fieldid'=>$subsectorid));
        //get the roleid and course category id from the roledata and subsector data
		$roleid = substr($roledata, 0, strpos($roledata, "-"));
		//below function will check any number followed by special chars like "2-"
		//it will make array of those chars.
		preg_match_all('!\d\W+!', $subsectordata, $matches);
		foreach ($matches as $carray) {
			foreach ($carray as $key => $value) {
				//replacing special chars from the array elements
				$catid = str_replace("-","",$value);
				//this query will give all visible courses id inside a main category.
				$query ="SELECT c.id 
				FROM  {course} c,  {course_categories} d
				WHERE c.visible=1 AND c.category = d.id
				AND (
				d.path LIKE '%/".$catid."/%'
				OR  d.path LIKE '%/".$catid."'
			)";
				//enrolling user into all the courses selected by the user.
			$enrolmethod ='manual';
			$catcourses = $DB->get_records_sql($query);
			foreach ($catcourses as $crs) {
				//checking if the user is already enrolled in the perticular course or not
				if (!in_array($crs->id, $alreadyenrolled)){
					$instances = enrol_get_instances($crs->id, true);
					foreach ($instances as $instance) {
						if($instance->enrol ===$enrolmethod){
							$manualinstance = $instance;
							break;
						}	
					}
					$enrol = enrol_get_plugin($enrolmethod);
					$enrol->enrol_user($manualinstance, $user->id, $roleid);
				}

			}

		}
	}

}

public static function user_mass_enroll_create(\core\event\user_created $event) {
	global $CFG, $SITE,$DB,$USER;
	$eventdata = $event->get_data();
	$user = \core_user::get_user($eventdata['objectid']);
        //get custom field id for user role and sub sector fields.
	$userroleid = $DB->get_field('user_info_field', 'id', array('shortname'=>'userrole'));
	$subsectorid = $DB->get_field('user_info_field', 'id', array('shortname'=>'subsector'));
        //get custom field data for perticular user and custom field ids.
	$roledata = $DB->get_field('user_info_data', 'data', array('userid'=>$user->id,'fieldid'=>$userroleid));
	$subsectordata = $DB->get_field('user_info_data', 'data', array('userid'=>$user->id,'fieldid'=>$subsectorid));
        //get the roleid and course category id from the roledata and subsector data
	$roleid = substr($roledata, 0, strpos($roledata, "-"));
	//below function will check any number followed by special chars like "2-"
		//it will make array of those chars.
	preg_match_all('!\d\W+!', $subsectordata, $matches);
	foreach ($matches as $carray) {
		foreach ($carray as $key => $value) {
			$catid = str_replace("-","",$value);
			$query ="SELECT c.id 
			FROM  {course} c,  {course_categories} d
			WHERE  c.visible=1 AND c.category = d.id 
			AND (
			d.path LIKE '%/".$catid."/%'
			OR  d.path LIKE '%/".$catid."'
		)";
		//enrolling user into all the courses selected by the user.
		$enrolmethod ='manual';
		$catcourses = $DB->get_records_sql($query);
		foreach ($catcourses as $crs) { 
			$instances = enrol_get_instances($crs->id, true);
			foreach ($instances as $instance) {
				if($instance->enrol ===$enrolmethod){
					$manualinstance = $instance;
					break;
				}	
			}
			$enrol = enrol_get_plugin($enrolmethod);
			$enrol->enrol_user($manualinstance, $user->id, $roleid);
		}

	}
}
}

 
}       