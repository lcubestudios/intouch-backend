<?php
require('./config.php');

$output = array();
$table = 'public.users';

if ($method === "POST") {
	$headers = getallheaders();
	$token = preg_split('/\s/', $headers['Authorization'])[1];
	
	$raw = file_get_contents('php://input');
	$data = json_decode($raw, true);

	$first_name = $data['first_name'];
	$last_name = $data['last_name'];

	$query = "UPDATE " . $table . "
		SET first_name = '" . $first_name . "', last_name = '" . $last_name . "' 
		WHERE token = '" . $token . "'
		RETURNING first_name, last_name, phone_number, token";

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
			'message' => 'User not found.',
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