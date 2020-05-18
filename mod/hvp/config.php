<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'essci';
$CFG->dbuser    = 'essci';
$CFG->dbpass    = 'root#123!';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'http://skills.labournet.in';
$CFG->dataroot  = '/var/esscidata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');



// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
// Force a debugging mode regardless the settings in the site administration
 //  @error_reporting(E_ALL | E_STRICT);   // NOT FOR PRODUCTION SERVERS!
 // @ini_set('display_errors', '1');         // NOT FOR PRODUCTION SERVERS!
 // $CFG->debug = (E_ALL | E_STRICT);   // === DEBUG_DEVELOPER - NOT FOR PRODUCTION SERVERS!
 // $CFG->debugdisplay = 1;              // NOT FOR PRODUCTION SERVERS!
//set_user_preference('filepicker_recentrepository',4);
print_r($_SERVER);
$urltogo = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// test the session actually works by redirecting to self
//Mihir for skills check mp4

$finalpart = array_reverse(explode('/',$urltogo));
$findme   = 'mp4';

$pos = strpos($finalpart[0], $findme);

// The !== operator can also be used.  Using != would not work as expected
// because the position of 'a' is 0. The statement (0 != false) evaluates
// to false.

if ($pos !== false) {
//  echo 'Sorry!! You will not be able to access this page. Please click below button to go back to Dashboard' ;
  //echo "<a href='.$CFG->wwwroot.'/my'.' > GO BACK </a>';
//  die();
  //redirect($urltogo);
}
