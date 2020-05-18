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
 * Handles uploading files
 *
 * @package    local_trainer_analysis
 * @copyright  Manjunath<manjunath@elearn10.com>
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

defined('MOODLE_INTERNAL') || die;

// Used to stay DRY with the get_string function call.
$componentname = 'local_trainer_analysis';
$ADMIN->add('localplugins', new \admin_category('local_trainer_analysis', get_string('title', $componentname)));

// Add the 'Add other pages' page to the nav tree.
    $ADMIN->add(
        'local_trainer_analysis',
        new \admin_externalpage(
            'trainer_analysis',
            get_string('traineranalysis', $componentname),
            new \moodle_url('/local/trainer_analysis/index.php')
        ));