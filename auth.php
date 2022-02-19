<?php
require('./config.php');

if($method === "POST"){
	$output = array();
	$purpose = $_GET['purpose'];

	echo $purpose;

	// LOGIN
	if ($purpose === 'login') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);
	}
	// REGISTER
	else if ($purpose === 'reg') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

		$token = bin2hex(openssl_random_pseudo_bytes(16));
	}
	else {
    $output = array(
			'status_code' => 500,
			'error_message' => 'Invalid Request',
		);
	}

	echo json_encode($output);
}
    // $raw=file_get_contents('php://input');
    // $data=json_decode($raw,true);
    // $phone_number = $data['phone_number'];
    // $pass = $data['password'];
    // $query = "SELECT token FROM \"public\".\"Login\" WHERE phone_number = '".$phone_number."' AND password = '".$pass."';";
    // $result = pg_query($conn, $query);
    // if ($row = pg_fetch_assoc($result)) {
    //     $token = $row['token'];
    //     $authArray = array(); // new array to hold data
    //     $auth_array['token'] = $token;
    //     $auth_array['profile']['phone_number'] = $phone_number;
    //     echo json_encode($auth_array);
    //     pg_close($conn);
    // }else {
    //     $status_code = 301;
    //     $error_msg = "Wrong username  or passoword. Please try again.";
    //     $auth_array = array();
    //     $auth_array['status_code'] = $status_code;
    //     $auth_array['error_message'] = $error_msg;
    //     echo json_encode($auth_array);
?>