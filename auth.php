<?php
require('./config.php');

$output = array();
$table = 'public.messaging_app_user';

if ($method === "POST"){
	$purpose = $_GET['purpose'];

	// LOGIN
	if ($purpose === 'login') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

		$username = $data['username'];
		$password = $data['password'];

		$verify_pass_query = "SELECT password FROM " . $table . " WHERE username = '" . $username . "'";
		$result = pg_query($conn, $verify_pass_query);
		$hashed_pass = pg_fetch_row($result);
		
		if($hashed_pass){
			$password_verify = password_verify($password, $hashed_pass[0]);

			if($password_verify == true){
				$query = "SELECT token, first_name, last_name, username FROM " . $table . " WHERE username = '" . $username . "' ";
			
				$result = pg_query($conn, $query);
	
				// Login Successful
				if ($row = pg_fetch_assoc($result)) {
						$output = array(
							'status_code' => 200,
							'message' => 'Login sucessful!',
							'results' => $row
						);
				}
			}
			// Login Failed
			else {
				$output = array(
					'status_code' => 301,
					'message' => 'Wrong username or password. Please try again.',
				);
			}
		}
		else{
			$output = array(
				'status_code' => 301,
				'message' => 'User does not exist',
			);
		}
		
	}
	// REGISTER
	else if ($purpose === 'reg') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$username = $data['username'];
		$password = $data['password'];
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$token = bin2hex(openssl_random_pseudo_bytes(20));

		$query = "INSERT INTO " . $table . " (first_name, last_name, username, password, token)
			VALUES ('". $first_name ."', '". $last_name ."', '". $username ."', '". $hash ."', '". $token ."')";

		pg_send_query($conn, $query);
		$result = pg_get_result($conn);
		$state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);

		if ($state == '23505') {
			$output = array(
				'status_code' => 301,
				'message' => 'This phone number has already been registered.'
			);
		}
		else {
			$output = array(
				'status_code' => 200,
				'message' => 'User has been created',
				'results' => array(
					'token' => $token, 
					'first_name' => $first_name,
					'last_name' => $last_name,
					'username' => $username
				)
			);
		}
	}
	else {
    $output = array(
			'status_code' => 500,
			'message' => 'Invalid Request',
		);
	}
}
else {
	$output = array(
		'status_code' => 500,
		'message' => 'Invalid Request.',
	);
}

echo json_encode($output);

pg_close($conn);
?>