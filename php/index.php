<?php
echo ("Hello!");

##COMPOSER NEEDED!##
require_once realpath(__DIR__ . '/vendor/autoload.php');

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrive env variable
$host = $_ENV['HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASSWORD'];
$db = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];

// Create connection
$conn = pg_connect("host=$host port=5432 dbname=$db user=$db_user password=$db_pass");
// Check connecion 
if ($conn) {

    echo 'Connection attempt succeeded.';
    
    } else {
    
    echo 'Connection attempt failed.';
    
    }
    
    pg_close($db_handle);
?>