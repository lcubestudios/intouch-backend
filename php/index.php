<?php
echo ("Hello!");

##COMPOSER NEEDED!##
require_once realpath(__DIR__ . '/vendor/autoload.php');

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrive env variable
$host = $_ENV['HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$db = $_ENV['DB_NAME'];

// Create connection
$conn = pg_connect($host, $username, $password, $dbname);
// Check connecion 
if ($conn) {

    echo 'Connection attempt succeeded.';
    
    } else {
    
    echo 'Connection attempt failed.';
    
    }
    
    pg_close($db_handle);
?>