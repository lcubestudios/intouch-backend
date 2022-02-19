<?php
require('./config.php');

switch ($method):
    case 'GET':
        echo("hello get");
        break;    
    case 'PUT':
        echo("hello PUT");
        break;
    case 'POST':
        echo("hello POST");
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
    $query = "SELECT token FROM auth WHERE phoneNumber = $phoneNumber AND password = $pass";
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