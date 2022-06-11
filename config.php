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
$users_table = $_ENV['USERS_TABLE'];
$contacts_table = $_ENV['CONTACTS_TABLE'];
$messages_table = $_ENV['MESSAGES_TABLE'];

// DB Columns
# User/Auth table
$db_id_key = $_ENV['DB_ID_KEY'];
$db_username_key = $_ENV['DB_USERNAME_KEY'];
$db_password_key = $_ENV['DB_PASSWORD_KEY'];
$db_token_key = $_ENV['DB_ACESSS_TOKEN_KEY'];
$db_first_name_key = $_ENV["DB_USER_FIRST_NAME_KEY"];
$db_last_name_key = $_ENV["DB_USER_LAST_NAME_KEY"];

# Contacts table
$db_user_id_key = $_ENV['DB_USER_ID_KEY'];
$db_contact_id_key = $_ENV['DB_CONTACT_ID_KEY'];

# Messages table


// Create connection
$conn = pg_connect("host=$host port=$port dbname=$db user=$db_user password=$db_pass");

$method = $_SERVER['REQUEST_METHOD'];
?>