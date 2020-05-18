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
 * @package    local_organization
 * @copyright  Sangita Kumari<sangita@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 **/

require_once('../../config.php');
require_once('lib.php');
defined('MOODLE_INTERNAL') || die();
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . '/local/edutimeline/index.php');
$title = get_string('headding', 'local_edutimeline');
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->navbar->ignore_active();
$previewnode = $PAGE->navbar->add($title);
echo $OUTPUT->header();
echo'<br>';
$headingtext = get_string('pluginheading','local_edutimeline');
echo '<hr>';
global $DB,$USER;
// this table is used to display availbe organization to admin
echo $OUTPUT->footer();
