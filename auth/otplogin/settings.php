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
 * Admin settings and defaults
 *
 * @package auth_otplogin
 * @copyright  manjunathbk@elearn10.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_otplogin/pluginname',
            new lang_string('otplogin_settings', 'auth_otplogin'),
            new lang_string('otplogin_settings_description', 'auth_otplogin')));

    // API Key.
    $settings->add(new admin_setting_configtext('auth_otplogin/otplogin_apikey', get_string('otplogin_apikey','auth_otplogin'),
            get_string('auth_otplogin_apikey_description', 'auth_otplogin'), '', PARAM_RAW));

    // Sender Name.
    $settings->add(new admin_setting_configtext('auth_otplogin/otplogin_sender', get_string('otplogin_sender','auth_otplogin'),
            get_string('auth_otplogin_sender_description', 'auth_otplogin'), '', PARAM_RAW));
}
