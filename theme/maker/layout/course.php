<?php

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

//added by prashant for essci  redirect to pre assessment test pge for student Jan 20. 2020
global $COURSE, $CFG;
$coursecontext = context_course::instance($COURSE->id);
$checkedit = has_capability('moodle/course:manageactivities',$coursecontext);
//if (isloggedin() and !is_siteadmin()) {
if (!$checkedit) { // means these are student
    if($COURSE->shortname=='PRE_ASSESSMENT'){
        redirect(new moodle_url($CFG->wwwroot.'/local/assessment_test/index.php'));
    }
}




if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];

$PAGE->requires->jquery ();
$PAGE->requires->js('/theme/maker/plugins/back-to-top.js');

$templatecontext['flatnavigation'] = $PAGE->flatnav;
echo $OUTPUT->render_from_template('theme_maker/columns2', $templatecontext);

