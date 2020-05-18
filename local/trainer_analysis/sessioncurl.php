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
require_once('../../config.php');
require_once('lib.php');
global $DB, $CFG, $USER;


$syncdate1 = optional_param('date', '', PARAM_RAW); // Course_module ID.
$syncdate = strtotime($syncdate1);

if (!empty($syncdate)) {
  $sql = "Select * from {liveclass_session} where sessiondate >= $syncdate";
} else {
  $sql = "Select * from {liveclass_session} where syncflag = 0";
}

$checkrecord = $DB->get_records_sql($sql);

if (!empty($checkrecord)) {
  foreach($checkrecord as $newsyncrec) {
    $url = $newsyncrec->sessionurl;
    // init the resource
    $ch = curl_init();
    $url = $url;
    curl_setopt_array(
    $ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true
    ));

    $output = curl_exec($ch);
    echo $output;
    curl_error($ch);

//update the sync flag
      global $DB;
      $upduser = new stdClass();
      $upduser->id = $newsyncrec->id;
      $upduser->syncdate = time();
      $upduser->syncflag = 1;
      $DB->update_record('liveclass_session', $upduser);

  }
}
