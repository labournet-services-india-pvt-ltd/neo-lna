<?php
include "../../config.php";
global $DB,$CFG;
$subsector = optional_param('subsector','',PARAM_INT);
$occupation = optional_param('occupation','',PARAM_INT);

if($subsector)
{
	//get all the courses for subsector
	$results =[];
	$csid = array();
	$csname = array();
	$sql ="SELECT c.id,c.fullname FROM {course} c
	LEFT JOIN {customfield_data} cd
	ON c.id=cd.instanceid
	WHERE c.category = $subsector AND cd.fieldid = 3 AND cd.value = $occupation";
	$results = $DB->get_records_sql($sql);
	$results = array_values($results);
	echo json_encode($results);
}