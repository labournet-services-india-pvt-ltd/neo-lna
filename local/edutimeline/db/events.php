<?php
// This file is part of the Local welcome plugin
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
 * This plugin sends users a auto email welcome message after
 * moderator added a new user 
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage Edutimeline
 * @copyright  2019 Sangita (Mihir jana), lmsofindia.com
 * @license    http://lmsofindia.com GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = array(
     array
    (
        'eventname' => '\core\event\course_module_deleted',
        'callback' => '\local_edutimeline\observer::edutimeline_course_module_deleted',
    )
);

