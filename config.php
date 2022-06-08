<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

##COMPOSER NEEDED!##
require_once realpath(__DIR__ . '/vendor/autoload.php');

// Looing for .env at the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrive env variable
$host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASSWORD'];
$db = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];
$origin = $_ENV['HEADER_ORIGIN'];
$methods = $_ENV['HEADER_METHODS'];
$headers = $_ENV['HEADER_HEADERS'];

// Headers
header("Access-Control-Allow-Origin: $origin"); 
header("Access-Control-Allow-Methods: $methods"); 
header("Access-Control-Allow-Headers: $headers"); 


// DB Tables
$users_table = 'public.messaging_app_user';
$contacts_table = 'public.messaging_app_contacts';
$messages_table = 'public.messaging_app_messages';

//DB Columns 
//Messaging_app_user table
// $db_u_id = "u_id";
// $db_username = "username";
// $db_password = "password";
// $db_token = "token";
// $db_first_name = "first_name";
// $db_last_name = "last_name";


// Create connection
$conn = pg_connect("host=$host port=$port dbname=$db user=$db_user password=$db_pass");

$method = $_SERVER['REQUEST_METHOD'];
?>