<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_trainer_analysis_upgrade($oldversion) {
if ($oldversion < 2020010104) { // Define table local_trainer_congreatime to be created.
  global $DB, $CFG;

  require_once($CFG->libdir . '/db/upgradelib.php');

$dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
$table = new xmldb_table('local_trainer_liveclasstime'); // Adding fields to table local_trainer_congreatime.
$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
$table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('sessionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('starttime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('endtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('duration', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('presentpercent', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
$table->add_field('userrole', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, ' ');
$table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0'); // Adding keys to table local_trainer_congreatime.
$table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']); // Conditionally launch create table for local_trainer_congreatime.
if (!$dbman->table_exists($table)) {
$dbman->create_table($table);
} // local_trainer_congreatime savepoint reached.
upgrade_plugin_savepoint(true, 2020010104, 'local','local_trainer_liveclasstime');
}




return true;
}
