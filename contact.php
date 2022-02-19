<?php
require('./config.php');

if($method === "GET"){
    $output =  array();
    $table = 'public.users';

    // Load Token
    $token = 1234567890;
    $query = "SELECT u_id FROM " . $table. " WHERE token = '". $token. "'";
    $result = pg_query($conn, $query);

    if($row = pg_fetch_assoc($result)){
        $output = array(
            'status_code' => 200,
            'id' => $row['id']
        );
    }
    
    // No Token Found
		else {
			$output = array(
				'status_code' => 301,
				'message' => 'Please log back in again',
			);
		}
}

?>