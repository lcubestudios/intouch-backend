<?php
require('./config.php');

$output = array();
$table = 'public.messaging_app_user';

if ($method === "PUT") {
	$headers = getallheaders();
	$token = preg_split('/\s/', $headers['Authorization'])[1];
	
	$raw = file_get_contents('php://input');
	$data = json_decode($raw, true);
	// $purpose = $data['purpose'];

	// if ($purpose === 'basic'){

	// }
	$first_name = $data['first_name'];
	$last_name = $data['last_name'];
	$old_password = $data['old_password'];
	$new_password = $data['new_password'];
	$hash = password_hash($new_password, PASSWORD_DEFAULT);

	if($old_password){
		$verify_pass_query = "SELECT password FROM " . $table . " WHERE token = '" . $token . "' ";
		$result = pg_query($conn, $verify_pass_query);
		$hashed_pass = pg_fetch_row($result);
		$password_verify = password_verify($old_password, $hashed_pass[0]);
		
		if($password_verify){
			$query = "UPDATE " . $table . " SET first_name = '" . $first_name . "', last_name = '" . $last_name . "', password = '" . $hash . "' 
			WHERE token = '" . $token . "' RETURNING first_name, last_name, phone_number, token";
	
			$result = pg_query($conn, $query);
	
			if ($row = pg_fetch_assoc($result)) {
				$output = array(
					'status_code' => 200,
					'message' => 'Profile updated!',
					'profile' => $row
				);
			}
			else {
				$output = array(
					'status_code' => 301,
					'message' => 'An error has occur, please try again.'
				);
			}
		}	
		else {
			$output = array(
				'status_code' => 301,
				'message' => 'Wrong password, please try again'
			);
		}
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