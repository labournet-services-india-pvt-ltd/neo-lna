<?php
// This file keeps track of upgrades to
// the assignment module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the methods of database_manager class
//
// Please do not forget to use upgrade_set_timeout()
// before any action that may take longer time to finish.

defined('MOODLE_INTERNAL') || die();

 function xmldb_local_course_details_upgrade($oldversion) {
     global $CFG,$DB;

    $dbman = $DB->get_manager();
    
    if ($oldversion < 2020040900) {

        $table = new xmldb_table('local_course_custom_fields');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null); 
        $table->add_field('cid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL,null, null, null);
        $table->add_field('slider_img_1', XMLDB_TYPE_INTEGER, '10', null, null,null, null, null);
        $table->add_field('slider_title_1', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('slider_desc_1', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('slider_img_2', XMLDB_TYPE_INTEGER, '10', null, null,null, null, null);
        $table->add_field('slider_title_2', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('slider_desc_2', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('slider_img_3', XMLDB_TYPE_INTEGER, '10', null, null,null, null, null);
        $table->add_field('slider_title_3', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('slider_desc_3', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);

        $table->add_field('course_about', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('sector_about', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('why_study', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('is_right_course', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('course_take_you', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('length', XMLDB_TYPE_TEXT, '100',null, null,null, null, null);
        $table->add_field('effort', XMLDB_TYPE_TEXT, '100',null, null,null, null, null);
        $table->add_field('mode', XMLDB_TYPE_TEXT, '100',null, null,null, null, null);
        $table->add_field('level', XMLDB_TYPE_TEXT, '100',null, null,null, null, null);
        $table->add_field('bulets_point_bg_img', XMLDB_TYPE_INTEGER, '10', null, null,null, null, null);
        $table->add_field('bulets_point_text_1', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('bulets_point_text_2', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('dummy_fields_1', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('dummy_fields_2', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('dummy_fields_3', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('dummy_fields_4', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_field('dummy_fields_5', XMLDB_TYPE_TEXT, '255',null, null,null, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Patientrecord savepoint reached.
        upgrade_plugin_savepoint(true, 2020040900,'local', 'course_details');
    }


    return true;
}
