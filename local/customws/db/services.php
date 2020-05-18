<?php

// Web service functions to install.
$functions = array(
    // course
    //Get user detaails present in moodle.
    'customws_get_user_information' => array(
        'classname' => 'customws_user_info',
        'methodname' => 'get_user_information',
        'classpath' => 'local/customws/externallib.php',
        'description' => 'Returns user details as per LN format',
        'type' => 'read',

    ),
);


$functionlist = array();
foreach ($functions as $key => $value) {
    $functionlist[] = $key;
}

$services = array(
    'Custom web services' => array(
        'functions' => $functionlist,
        'shortname' => 'custom',
        'enabled' => 0,
        'restrictedusers' => 0,
    ),
);
