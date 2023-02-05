<?php 
define('DB_HOST'    , 'fdb28.awardspace.net'); 
define('DB_USERNAME', '4256992_tempdb'); 
define('DB_PASSWORD', 'S@kur@2000'); 
define('DB_NAME'    , '4256992_tempdb');

define('POST_DATA_URL', 'http://sw-assignment.atwebpages.com/sensordata.php');

//PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in NodeMCU sketch file
//Both values must be same
define('PROJECT_API_KEY', 'tPmAT5Ab3j7F9');


//set time zone for your country
date_default_timezone_set('Asia/Kuala_Lumpur');

// Connect with the database 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); 
 
// Display error if failed to connect 
if ($db->connect_errno) { 
    echo "Connection to database is failed: ".$db->connect_error;
    exit();
}
