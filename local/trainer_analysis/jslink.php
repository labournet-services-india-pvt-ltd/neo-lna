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
/**
* for dispalying data tables we need to add all js files here
*/

$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/custom.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/jquery.dataTables.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/dataTables.buttons.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/jszip.min.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/vfs_fonts.js'), true);
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/js/buttons.html5.min.js'), true);
// https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js
// https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js
// https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js
// https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js
// https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
// https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js
// https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js
// https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/trainer_analysis/css/jquery.dataTables.min.css'));
