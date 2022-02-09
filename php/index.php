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
$host = $_ENV['HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASSWORD'];
$db = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];

// Create connection
$conn = pg_connect("host=$host port=5432 dbname=$db user=$db_user password=$db_pass");
// Check connecion 
if ($conn) {
    echo "Connection attempt succeeded. \n";
    } else {
    echo "Connection attempt failed. \n"; 
    }
$method = $_SERVER['REQUEST_METHOD'];

switch ($method):
    ##GET-CONTACTS
    case 'GET':
        echo("hello get");
        ##NEED TO CHECK FOR TOKEN
        $token = 1234567890;
        $query = "SELECT u_id,phone_number FROM \"public\".\"Login\" WHERE token = '".$token."' ;";
        $result = pg_query($conn, $query);
        if ($row = pg_fetch_assoc($result)) {
            $u_id = $row['u_id'];
            $phone_number = $row['phone_number'];
            $query2 = "SELECT * FROM \"public\".\"Messages\" WHERE s_id = '".$u_id."' ;";
            $response = pg_query($conn, $query2);
            if($row =  pg_fetch_assoc($response)){
                $message = $row['body_text'];
                # need to look  throuhg read messages
                #$read = count($row['r_read']);
                $date = $row['date'];
            }
            $contactsArray = array(); // new array to hold data
            $contactsArray['uid'] = $token;
            $contactsArray['phone_number'] = $phone_number;
            $contactsArray['unread_count'] = 0;
            $contactsArray["last_message"]['body'] = $message;
            $contactsArray["last_message"]['dt_created'] = $date;
            $contactsArray["last_message"]['timestamp'] = "01:22";
            echo json_encode($contactsArray);
            pg_close($conn);
        }else{
            $status_code = 301;
            $error_msg = "Wrong username  or passoword. Please try again.";
            $contactsArray = array();
            $contactsArray['status_code'] = $status_code;
            $contactsArray['error_message'] = $error_msg;
            echo json_encode($contactsArray);
        }
        #$query = "SELECT c_id FROM \"public\".\"Relationship\" WHERE u_id = '".$u_id."';";
        break;    
    case 'PUT':
        echo("hello PUT");
        break;
    ##AUTH##
    case 'POST':
        $raw=file_get_contents('php://input');
        $data=json_decode($raw,true);
        $phone_number = $data['phone_number'];
        $pass = $data['password'];
        $query = "SELECT token FROM \"public\".\"Login\" WHERE phone_number = '".$phone_number."' AND password = '".$pass."';";
        $result = pg_query($conn, $query);
        if ($row = pg_fetch_assoc($result)) {
            $token = $row['token'];
            $authArray = array(); // new array to hold data
            $auth_array['token'] = $token;
            $auth_array['profile']['phone_number'] = $phone_number;
            echo json_encode($auth_array);
            pg_close($conn);
        }else {
            $status_code = 301;
            $error_msg = "Wrong username  or passoword. Please try again.";
            $auth_array = array();
            $auth_array['status_code'] = $status_code;
            $auth_array['error_message'] = $error_msg;
            echo json_encode($auth_array);
        };

        break;
    case 'DELETE':
        echo("hello Delete");
        break;
endswitch;

?>