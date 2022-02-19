<?php
require('./config.php');

if($method === "GET"){
    $output =  array();
    $users_table = 'public.users';
    $contacts_table = 'public.contacts';

    // Load Token
    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];
    $query = "SELECT u_id FROM " . $users_table. " WHERE token = '". $token. "'";
    $result = pg_query($conn, $query);

    // Load User ID
    if($row = pg_fetch_assoc($result)){
        $u_id = $row['u_id'];
        $output = array(
            'status_code' => 200,
            'id' => $u_id
        );
    }
    
    // No Token Found
		else {
			$output = array(
				'status_code' => 301,
				'message' => 'Please log back in again',
			);
		}
    
    echo json_encode($output);

	pg_close($conn);
}

?>