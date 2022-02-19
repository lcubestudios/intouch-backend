<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Retrive env variable
$host = '172.21.0.2';
$db_user = 'lcube';
$db_pass = 'LCubeStudios2022!%';
$db = 'intouch';
$port = 5432;

// Create connection
$conn = pg_connect("host=$host port=5432 dbname=$db user=$db_user password=$db_pass");
// Check connecion 
if ($conn) {
    echo "Connection attempt succeeded. \n";
    } else {
    echo "Connection attempt failed. \n"; 
    }

?>