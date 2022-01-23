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
    
    pg_close($conn);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method):
    case 'GET':
        echo("hello get");
        break;    
    case 'PUT':
        echo("hello PUT");
        break;
    case 'POST':
        $raw=file_get_contents('php://input');
        $data=json_decode($raw,true);
        $phone_number = $data['phone_number'];
        $pass = $data['password'];
        $query = "SELECT token FROM  WHERE phone_number = '".$phone_number."' AND password = '".$pass."';";
        echo($query);
        #$response = pg_query($conn, $query);
        #var_dump($response);
        #$row = pg_fetch_assoc($response);
        #echo($row['phone_number']);
        #pg_close($conn);

        break;
    case 'DELETE':
        echo("hello Delete");
        break;
endswitch;


function Auth(){
    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phoneNumber = $data['phone_number'];
    $pass = $data['password'];
    $query = "SELECT token FROM Login WHERE phone_number = $phoneNumber AND password = $pass";
    $result = pg_exec($conn, $query);
    if ($result) {
        echo "The query executed successfully.<br>";
        $row = pg_fetch_assoc($result);
        $profile = $row['phoneNumber'];
        $authArray = array(); // new array to hold data
        $statusCode = header("HTTP/1.0 200");
        $auth_array['token'] = $token;
        $auth_array['profile']['phoneNumber'] = $phoneNumber;
        $auth_array['status_code'] = $statusCode;
        echo json_encode($auth_array);
      }
      else {
            // if error
            $status_code = header("HTTP/1.0 301");
            $error_msg = "Wrong username  or passoword. Please try again";
            $auth_array = array();
            $auth_array['status_code'] = $status_code;
            $auth_array['error_message'] = $error_msg;
            echo json_encode($auth_array);
        };
}
?>
