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
 * Contains class core_tag\output\course_module_name
 *
 * @package   core_course
 * @copyright 2016 Marina Glancy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_lntopics\output;

/**
 * Class to prepare a course module name for display and in-place editing
 *
 * @package   core_course
 * @copyright 2016 Marina Glancy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_name extends \core_course\output\course_module_name {

    /**
     * Export this data so it can be used as the context for a mustache template (core/inplace_editable).
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return array data context for a mustache template
     */
     public function export_for_template(\renderer_base $output) {
         global $PAGE;
         $courserenderer = $PAGE->get_renderer('format_lntopics', 'course'); // Use our renderer instead.
         $this->displayvalue = $courserenderer->course_section_cm_name_title($this->cm, $this->displayoptions);
         if (strval($this->displayvalue) === '') {
             $this->editable = false;
         }
         return parent::export_for_template($output);
     }
}
