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
 * View H5P Content
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../config.php");
require_once("locallib.php");

global $PAGE, $DB, $CFG, $OUTPUT;




$id = required_param('id', PARAM_INT);

// Verify course context.
$cm = get_coursemodule_from_id('hvp', $id);
if (!$cm) {
    print_error('invalidcoursemodule');
}

//mihir
//just setting a CFG global variable to identify that student have come to this page and they can watch the hvp video
global $SESSION;
//$SESSION->hvpgood = 1;
//print_object($PAGE);


$course = $DB->get_record('course', array('id' => $cm->course));
if (!$course) {
    print_error('coursemisconf');
}
require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/hvp:view', $context);

// Set up view assets.
$view    = new \mod_hvp\view_assets($cm, $course);
$content = $view->getcontent();
$view->validatecontent();

// Configure page.
$PAGE->set_url(new \moodle_url('/mod/hvp/view.php', array('id' => $id)));
$PAGE->set_title(format_string($content['title']));
$PAGE->set_heading($course->fullname);

// Add H5P assets to page.
$view->addassetstopage();
$view->logviewed();

// Print page HTML.
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($content['title']));
echo '<div class="clearer"></div>';

// Output introduction.
if (trim(strip_tags($content['intro'], '<img>'))) {
    echo $OUTPUT->box_start('mod_introbox', 'hvpintro');
    echo format_module_intro('hvp', (object) array(
        'intro'       => $content['intro'],
        'introformat' => $content['introformat'],
    ), $cm->id);
    echo $OUTPUT->box_end();
}

// Print any messages.
\mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
\mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));

$view->outputview();
?>
    <script>
        window.oncontextmenu = function () {
            alert("Sorry!! Right Click Disabled");
            return false;
        }
</script>
    <script>
            $('.h5p-iframe-wrapper').bind('contextmenu', function(e) {
              alert("Sorry!! Right Click Disabled here");
                return false;
            });
        </script>
    <script language="JavaScript">
  /**
    * Disable right-click of mouse, F12 key, and save key combinations on page
    * By Arthur Gareginyan (arthurgareginyan@gmail.com)
    * For full source code, visit https://mycyberuniverse.com
    */
  window.onload = function() {
    document.addEventListener("contextmenu", function(e){
      e.preventDefault();
    }, false);
    document.addEventListener("keydown", function(e) {
    //document.onkeydown = function(e) {
      // "I" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
      }
      // "J" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
      }
      // "S" key + macOS
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
      }
      // "U" key
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
      }
      // "F12" key
      if (event.keyCode == 123) {
        disabledEvent(e);
      }
    }, false);
    function disabledEvent(e){
      if (e.stopPropagation){
        e.stopPropagation();
      } else if (window.event){
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  };
</script>


<?php
echo $OUTPUT->footer();
