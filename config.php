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
$db_sender_id_key = $_ENV['DB_SENDER_ID_KEY'];
$db_receiver_id_key = $_ENV['DB_RECEIVER_ID_KEY'];
$db_message_body_key = $_ENV['DB_MESSAGE_BODY_KEY'];
$db_read_status_key = $_ENV['DB_READ_STATUS_KEY'];
// $db_message_date_key = $_ENV['DB_MESSAGE_DATE_KEY'];
// $db_message_id_key = $_ENV['DB_MESSAGE_ID_KEY'];
$db_message_type_key = $_ENV['DB_MESSAGE_TYPE_KEY'];
$db_file_data_key = $_ENV['DB_FILE_DATA_KEY'];
$db_file_extension_key = $_ENV['DB_FILE_EXTENSION_KEY'];

// Create connection
$conn = pg_connect("host=$host port=$port dbname=$db user=$db_user password=$db_pass");

$method = $_SERVER['REQUEST_METHOD'];
?>