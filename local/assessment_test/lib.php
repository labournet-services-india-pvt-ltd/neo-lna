<?php
//manju:this function returns an array containing all category information for that perticular quiz attempts.(05/12/2019).
    /*
     * @param variable $attemptid is quiz attempt id.
     * @param variable $attemptid is quiz attempt id.
     * @return string HTML content to go inside the td.
     */
        function quiz_category_stats($attemptid) {
            global $DB, $CFG, $USER;
            $a = 0;
            $b = 0;
            $c = 0;
            $qreport = []; 
            $question = [];
            $qstimearray = [];
            $qstimearraywrg = [];
            $qstimearraynt = [];
            $newaar = [];
            $qresults = $DB->get_records_sql("
                SELECT qas.id,qa.questionid, qas.questionattemptid, qas.timecreated, qas.state 
                FROM {quiz_attempts} quiza 
                LEFT JOIN {question_usages} qu ON qu.id = quiza.uniqueid 
                LEFT JOIN {question_attempts} qa ON qa.questionusageid = qu.id 
                LEFT JOIN {question_attempt_steps} qas ON qas.questionattemptid = qa.id 
                WHERE quiza.id = $attemptid");
            if(!empty($qresults)){
                foreach ($qresults as $valuetime) {
                    if(!empty($valuetime->questionid)){
                        $qcatid =$DB->get_field('question', 'category', array('id'=>$valuetime->questionid));
                    }
                    if($valuetime->state == 'gaveup'){              
                        $question[$qcatid]['notattemted'][] = $valuetime->questionid;
                    }elseif($valuetime->state == 'gradedright'){
                        $question[$qcatid]['right'][] = $valuetime->questionid;
                    }elseif($valuetime->state == 'gradedwrong'){
                        $question[$qcatid]['wrong'][] = $valuetime->questionid;
                    }

                }

                if(!empty($question)){
                    foreach ($question as $key => $categories) {
                        foreach ($categories as $key11 => $value11) {
                            if($key11=='right'){
                                $a = (count($value11));
                            }
                            if($key11=='wrong'){
                                $b = (count($value11));
                            }
                            if($key11=='notattemted'){
                                $c = (count($value11));
                            }
                        }
                        $catname = $DB->get_field('question_categories', 'name', array('id'=>$key));
                        $totalquestions = $a + $b + $c;
                        $percentageright = round(($a * 100)/$totalquestions);
                        $percentagewrong = round(($b * 100)/$totalquestions);
                        $percentagenotattempt = round(($c * 100)/$totalquestions);

                        $qreport[$key] = array($catname,$percentageright,$percentagewrong,$percentagenotattempt);
                    }
                }
            }

return $qreport;

}

function get_recommended_courses($questioncat){
	global $DB,$CFG;
    $trimquestioncat = str_replace(' ', '', $questioncat);
	$returnarray=[];
    $nosfieldid = $DB->get_field('customfield_field','id', array('shortname'=>'nos_name'));
	$nosdata =$DB->get_records('customfield_data',array('fieldid'=>$nosfieldid));
    if (!empty($nosdata)) {
        foreach($nosdata as $key => $nosval) {
            $stripvalue = strip_tags($nosval->value);
            $trimvalue = str_replace(' ', '', $stripvalue);
            $explodval = explode(",", $trimvalue);

            if(array_search($trimquestioncat, $explodval)!== false){            
                $returnarray[]=$nosval->instanceid;
               
            }
        }
    }
	// $courses = $DB->get_records('customfield_data',array('intvalue'=>$nosid,'fieldid'=>$nosfieldid));
	// foreach ($courses as $course) {
		
	// }
    
	return $returnarray;
}

function get_occupations(){
    global $DB,$CFG;
    $returnarray=[];
    $nosdata =$DB->get_field('customfield_field','configdata', array('id'=>3));
    $data = explode(",",$nosdata);
    $str1 = substr($data[2], 10);
    $str1 = str_replace( array('"'), '', $str1);
    $expstring = '\r\n';
    $dataarray[0]= get_string('selectoccupation', 'local_assessment_test');
    $dataarray2 = explode($expstring,$str1);
    $dataarray3 = array_merge($dataarray,$dataarray2);
    return $dataarray3;
}

function get_course_image_new_url($course) {
        global $CFG;
        $courseinlist = new \core_course_list_element($course);
        foreach ($courseinlist->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $pathcomponents = [
                    '/pluginfile.php',
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                ];
                $path = implode('/', $pathcomponents);
                return (new moodle_url($path))->out();
            }
        }
        return false;
}
function get_recommended_courses_dropdown($questioncat){
    global $DB,$CFG;
    $returnarray=[];
    $nosfieldid = $DB->get_field('customfield_field','id', array('shortname'=>'nos_name'));
    $nosdata =$DB->get_field('customfield_field','configdata', array('id'=>$nosfieldid));
    $data = explode(",",$nosdata);
    $str1 = substr($data[2], 10);
    $str1 = str_replace( array('"'), '', $str1);
    $expstring = '\r\n';
    $dataarray[0]='select';
    $dataarray2 = explode($expstring,$str1);
    $dataarray3 = array_merge($dataarray,$dataarray2);
    $nosid = array_search($questioncat, $dataarray3);
    $courses = $DB->get_records('customfield_data',array('intvalue'=>$nosid,'fieldid'=>14));
    foreach ($courses as $course) {
        $returnarray[]=$course->instanceid;
    }
    return $returnarray;
}