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
 * Metadata user context plugin language file.
 *
 * @package local
 * @subpackage metadatacontext_user
 * @author Sangita
 * @copyright 2017 onwards Mike Churchward (mike.churchward@poetgroup.org)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
$string['pluginname'] = 'Labour Net User Create';
$string['preassess'] = 'Preassessment';

$string['email_subject_PCB'] = 'Hello {$a->fullname} Welcome to {$a->sitename}';
$string['email_body_PCB'] = '

<html>
<body>

<h3>Dear {$a->fullname}</h3>
<p>Congratulations! An account has successfully been created for you on LabourNet Academy.<br> <br>Your LabourNet Academy account gives you the ability to study online, access various course materials, participate in discussions, take quizzes and much more.
</p>
<p>Kindly click on below mentioned URL and login into your account to complete an assessment to enroll yourself into one of the available courses.</p>
<p></p>
<table cellspacing="0" cellpadding="8">
<tr><td>URL:</td><td>{$a->preassessment_link_d}</td></tr>
<tr><td>User name: </td><td>{$a->username}</td></tr>
<tr><td>Password: </td><td>Pass#123</td></tr>
</table>
<h4>Regards & Thanks,<br>
LabourNet Academy</h4>

</body>
</html>
';

$string['email_subject_IT'] = 'Hello {$a->fullname} Welcome to {$a->sitename}';
$string['email_body_IT'] = '

<html>
<body>

<h3>Dear {$a->fullname}</h3>
<p>Congratulations! An account has successfully been created for you on LabourNet Academy.<br> <br>Your LabourNet Academy account gives you the ability to study online, access various course materials, participate in discussions, take quizzes and much more.
</p>
<p>Kindly click on below mentioned URL and login into your account to complete an assessment to enroll yourself into one of the available courses.</p>
<p></p>
<table cellspacing="0" cellpadding="8">
<tr><td>URL:</td><td>{$a->preassessment_link_d}</td></tr>
<tr><td>User name: </td><td>{$a->username}</td></tr>
<tr><td>Password: </td><td>Pass#123</td></tr>
</table>
<h4>Regards & Thanks,<br>
LabourNet Academy</h4>

</body>
</html>
';
$string['email_subject'] = '[LabourNet Academy] Confirmation for account creation';
$string['email_body'] = '

<html>
<body>

<p>Dear {$a->fullname}</p>
<p>Congratulations! An account has successfully been created for you on LabourNet Academy.<br> <br>Your LabourNet Academy account gives you the ability to study online, share various course materials for reference, engage students in discussions, conduct quizzes and much more.
</p>
<p>Kindly click on below mentioned URL and login into your account to complete an assessment to enroll yourself into one of the available courses.</p>
<table cellspacing="0" cellpadding="8">
<tr><td>URL: </td><td>//skills.labournet.in</td></tr>
<tr><td>User name: </td><td>{$a->username}</td></tr>
<tr><td>Password: </td><td>Pass#123</td></tr>
</table>
<p></p>
<h4>Regards & Thanks,<br>
LabourNet Academy</h4>

</body>
</html>
';
$string['email_body_tutor'] = '

<html>
<body>

<p>Dear {$a->fullname}</p>
<p>Congratulations! An account has successfully been created for you on LabourNet Academy.<br> <br>Your LabourNet Academy account gives you the ability to study online, share various course materials for reference, engage students in discussions, conduct quizzes and much more.
</p>
<p>Kindly click on below mentioned URL and login into your account to access the courses assigned to you. <br>In case if you want access to any specific course, you may contact NEO Helpdesk for the same. Do mention [LabourNet Academy] while generating ticket inside Helpdesk.</p>
<table cellspacing="0" cellpadding="8">
<tr><td>URL: </td><td>https://skill.labournet.in</td></tr>
<tr><td>User name: </td><td>{$a->username}</td></tr>
<tr><td>Password: </td><td>Pass#123</td></tr>
</table>
<p></p>
<h4>Regards & Thanks,<br>
LabourNet Academy</h4>

</body>
</html>
';
