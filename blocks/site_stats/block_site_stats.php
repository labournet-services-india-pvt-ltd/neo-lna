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
 * Controller for various actions of the block.
 *
 * This page display the community course search form.
 * It also handles adding a course to the community block.
 * It also handles downloading a course template.
 *
 * @package    block_statistics
 * @author     Manjunath B K <manjunathbk@elearn10.com>
 * @license    https://lmsofindia.com
 * @copyright  https://lmsofindia.com
 */

defined('MOODLE_INTERNAL') || die();

class block_site_stats extends block_base {

	function init() {
		$this->title = get_string('pluginname', 'block_site_stats');
	}

	public function get_content() {
		if ($this->content !== null) {
			return $this->content;
		}
		
		$this->content         =  new stdClass;
		$this->content->text   = $this->site_statistics_content();
		return $this->content;
	}
	//manju: this function returns 6 blocks with all stats.
	public function site_statistics_content(){
		global $DB,$PAGE,$CFG;
		$allusersinsite = $this->get_total_users_count();
		$allcoursecount = $this->get_available_course_count();
		$userenrollmentcount = $this->enrolled_users_count();
		$courseenrollments = $this->number_of_courseenrollments();
		$visitorscount = $this->get_visitors_count();
		$subsectorscount = $this->get_subsector_count();
		$html ='<div class="container" id="site-stats">
		<div class="row">
		<div class="col-md-2 text-center">
		<div class="card text-white bg-primary">

		<div class="card-body">'.$allusersinsite.'
		</div>
		<i class="fa fa-users" aria-hidden="true"></i>
		<div class="card-header">'.get_string('users','block_site_stats').'</div>
		</div>
		</div>
		<div class="col-md-2 text-center">
		<div class="card text-white bg-secondary">
		<div class="card-body">'.$allcoursecount.'
		</div>
		<i class="fa fa-book" aria-hidden="true"></i>
		<div class="card-header">'.get_string('courses','block_site_stats').'</div>
		</div>
		</div>
		<div class="col-md-2 text-center">
		<div class="card text-white bg-success">
		<div class="card-body">'.$userenrollmentcount.'
		</div>
		<i class="fa fa-user-plus" aria-hidden="true"></i>
		<div class="card-header">'.get_string('usersenrolled','block_site_stats').'</div>
		</div>
		</div>
		<div class="col-md-2 text-center">
		<div class="card text-white bg-danger">
		<div class="card-body">'.$courseenrollments.'
		</div>
		<i class="fa fa-check-circle-o" aria-hidden="true"></i>
		<div class="card-header">'.get_string('coursesenrolled','block_site_stats').'</div>
		</div>
		</div>
		<div class="col-md-2 text-center">
		<div class="card text-white bg-warning">
		<div class="card-body">'.$visitorscount.'
		</div>
		<i class="fa fa-eye" aria-hidden="true"></i>
		<div class="card-header">'.get_string('visitors','block_site_stats').'</div>
		</div>
		</div>
		<div class="col-md-2 text-center">
		<div class="card text-white bg-info">
		<div class="card-body">'.$subsectorscount.'
		</div>
		<i class="fa fa-diamond" aria-hidden="true"></i>
		<div class="card-header">'.get_string('subsectors','block_site_stats').'</div>
		</div>
		</div>
		</div>
		</div>';
		return $html; 
	}
	//manju: this function will returns total users present moodle.
	function get_total_users_count(){
		global $DB, $CFG;
		$usersql = "SELECT *  FROM {user} 
		WHERE deleted != 1 and suspended != 1";
		$users = $DB->get_records_sql($usersql);
		$alluserscount = count($users);
		return $alluserscount;
	}
	//manju: this funcction will returns the count of total courses present in moodle
	function get_available_course_count(){
		global $DB, $CFG;
		$coursesql = "SELECT * FROM {course}
		WHERE visible = 1";
		$allcourses = $DB->get_records_sql($coursesql);
		$coursecount = count($allcourses);
		return $coursecount;
	}
	//manju: this function will returns the total enrolled users count.
	function enrolled_users_count(){
		global $DB, $CFG;
		$query ="SELECT DISTINCT(userid) as id FROM {user_enrolments}";
		$enrollmentcount = count($DB->get_records_sql($query));
		
		return $enrollmentcount;


	}
	//manju: this function will teturns the total course enrollments in moodle.
	function number_of_courseenrollments(){
		global $DB, $CFG;
		$enrollmentcount = $DB->get_records('user_enrolments');
		$totalenrollments = count($enrollmentcount);
		return $totalenrollments;
	}
	//manju: this function will returns the total visitors to the site based on logged in logs in mdl_logstore_standard_log table in database.
	function get_visitors_count(){
		global $DB, $CFG;
		$visitorquery = "SELECT DISTINCT userid as uid FROM {logstore_standard_log}
		WHERE action LIKE 'loggedin'";
		$visitors = count($DB->get_records_sql($visitorquery));
		return $visitors;


	}
	function get_subsector_count(){
		global $DB, $CFG;
		$categorycount = count($DB->get_records('course_categories'));
		return $categorycount;

	}
}
