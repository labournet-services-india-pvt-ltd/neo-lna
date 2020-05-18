<?php
require_once(__DIR__ . '/../config.php');
global $DB;
$subsectdata = 'Dell';
$sqlquery = "SELECT cc.idnumber from {course_categories} AS cc where idnumber LIKE '%$subsectdata%'";
$datainfo = $DB->get_record_sql($sqlquery);
print_object($sqlquery);
print_object($datainfo);
