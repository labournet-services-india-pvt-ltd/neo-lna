<?php
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array
    (
        'eventname' => '\core\event\course_viewed',
        'callback' => '\local_trainer_analysis\observer::send_sesstion_todb',
    ),
    array
    (
        'eventname' => '\core\event\user_loggedin',
        'callback' => '\local_trainer_analysis\observer::send_sesstion_todb_loggedin',
    ),
    array
    (
        'eventname' => '\core\event\user_loggedout',
        'callback' => '\local_trainer_analysis\observer::send_sesstion_todb_loggedout',
    )
);
