<?php
//manju: this function returns 6 blocks with all stats.
function site_statistics_content(){
  global $DB,$PAGE,$CFG;
  $allusersinsite = get_total_users_count();
  $allcoursecount = get_available_course_count();
  $userenrollmentcount = enrolled_users_count();
  $courseenrollments = number_of_courseenrollments();
  $visitorscount = get_visitors_count();
  $subsectorscount = get_subsector_count();
  $totaltrainercount = get_total_trainer_count();
  $html ='<div class="container" id="site-stats">
  <div class="row">
  <div class="col-md-12 text-center m-5">
    <h2> LN Statistics </h2>
    <hr>
  </div>
  </div>

  <div class="row">
  <div class="col-md-2 text-center">
  <div class="card text-white bg-primary">

  <div class="card-body">'.$allusersinsite.'
  </div>
  <i class="fa fa-users" aria-hidden="true"></i>
  <div class="card-header">'.get_string('users','local_trainer_analysis').'</div>
  </div>
  </div>
  <div class="col-md-2 text-center">
  <div class="card text-white bg-secondary">
  <div class="card-body">'.$allcoursecount.'
  </div>
  <i class="fa fa-book" aria-hidden="true"></i>
  <div class="card-header">'.get_string('courses','local_trainer_analysis').'</div>
  </div>
  </div>

<!--
  <div class="col-md-2 text-center">
  <div class="card text-white bg-success">
  <div class="card-body">'.$userenrollmentcount.'
  </div>
  <i class="fa fa-user-plus" aria-hidden="true"></i>
  <div class="card-header">'.get_string('usersenrolled','local_trainer_analysis').'</div>
  </div>
  </div>
  -->

  <div class="col-md-2 text-center">
  <div class="card text-white bg-danger">
  <div class="card-body">'.$courseenrollments.'
  </div>
  <i class="fa fa-check-circle-o" aria-hidden="true"></i>
  <div class="card-header">'.get_string('coursesenrolled','local_trainer_analysis').'</div>
  </div>
  </div>

  <!--
  <div class="col-md-2 text-center">
  <div class="card text-white bg-warning">
  <div class="card-body">'.$visitorscount.'
  </div>
  <i class="fa fa-eye" aria-hidden="true"></i>
  <div class="card-header">'.get_string('visitors','local_trainer_analysis').'</div>
  </div>
  </div>
  -->

  <div class="col-md-2 text-center">
  <div class="card text-white bg-info">
  <div class="card-body">'.$totaltrainercount.'
  </div>
  <i class="fa fa-diamond" aria-hidden="true"></i>
  <div class="card-header">'.get_string('totaltrainer','local_trainer_analysis').'</div>
  </div>
  </div>
  </div>
  </div>';
  return $html;
}
//manju: this function will returns total users present moodle.
function get_total_trainer_count(){
  global $DB, $CFG;
  $usersql = "SELECT *  FROM {user}
  WHERE deleted != 1 and suspended != 1 and username != 'guest' and icq='Trainer' and yahoo='ONLN'";
  $users = $DB->get_records_sql($usersql);
  $alluserscount = count($users);
  return $alluserscount;
}

//manju: this function will returns total users present moodle.
function get_total_users_count(){
  global $DB, $CFG;
  $usersql = "SELECT *  FROM {user}
  WHERE deleted != 1 and suspended != 1 and username != 'guest' and icq='Student' and yahoo='ONLN'";
  $users = $DB->get_records_sql($usersql);
  $alluserscount = count($users);
  return $alluserscount;
}

//manju: this funcction will returns the count of total courses present in moodle
function get_available_course_count(){
  global $DB, $CFG;
  $coursesql = "SELECT * FROM {course}
  WHERE visible = 1 and id !=1 and shortname !='PRE_ASSESSMENT' and category = 84 "; // this is only for LN ACAD
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
  //$enrollmentcount = $DB->get_records('user_enrolments');

  $sqlquery = "SELECT ra.userid
  FROM {user_enrolments} AS ra
  JOIN {user} AS u ON u.id = ra.userid
  WHERE u.deleted != 1 and u.suspended != 1 and u.username != 'guest' and u.icq='Student' and u.yahoo='ONLN'
  ";
  $enrollmentcount = $DB->get_records_sql($sqlquery);
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
  //$categorycount = count($DB->get_records('course_categories'));
  $categorycount = count($DB->get_records_sql('Select * from {course_categories} where parent = 0'));
  return $categorycount;

}
