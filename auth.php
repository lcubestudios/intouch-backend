<?php
require('./config.php');

if($method === "POST"){
	$output = array();
	$purpose = $_GET['purpose'];
	$table = 'public.users';

	// LOGIN
	if ($purpose === 'login') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

    $phone_number = $data['phone_number'];
    $password = $data['password'];

		$query = "SELECT token, u_id, first_name, last_name, phone_number 
			FROM " . $table . "
			WHERE phone_number = '" . $phone_number . "'
			AND password = '" . $password . "'";
		
    $result = pg_query($conn, $query);

		// Login Successful
    if ($row = pg_fetch_assoc($result)) {
			$output = array(
				'status_code' => 200,
				'message' => 'User has been created',
				'profile' => $row
			);
    }
		// Login Failed
		else {
			$output = array(
				'status_code' => 301,
				'message' => 'Wrong username or password. Please try again.',
			);
		}
	}
	// REGISTER
	else if ($purpose === 'reg') {
		$raw = file_get_contents('php://input');
		$data = json_decode($raw, true);

    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $phone_number = $data['phone_number'];
    $password = $data['password'];

		$token = bin2hex(openssl_random_pseudo_bytes(20));


		$query = "INSERT INTO " . $table . " (first_name, last_name, phone_number, password, token)
			VALUES ('". $first_name ."', '". $last_name ."', '". $phone_number ."', '". $password ."', '". $token ."')
			RETURN u_id";

		pg_send_query($conn, $query);
		$result = pg_get_result($conn);
		$state = pg_result_error_field($result, PGSQL_DIAG_SQLSTATE);

		if ($state == '23505') {
			$output = array(
				'status_code' => 301,
				'message' => 'This phone number has already been registered.'
			)
		}
		else {
			$output = array(
				'status_code' => 200,
				'message' => 'User has been created',
				'profile' => array(
					'token' => $token, 
					'u_id' => pg_last_oid($result),
					'first_name' => $first_name,
					'last_name' => $last_name,
					'phone_number' => $phone_number
				)
			)
		}
	}
	else {
    $output = array(
			'status_code' => 500,
			'message' => 'Invalid Request',
		);
	}

	echo json_encode($output);

	pg_close($conn);
}
?>