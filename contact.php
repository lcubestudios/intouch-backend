<?php
require('./config.php');

if($method === "GET"){
    $output =  array();
    $users_table = 'public.users';
    $contacts_table = 'public.contacts'

    // Load Token
    $token = 1234567890;
    $query = "SELECT u_id FROM " . $users_table. " WHERE token = '". $token. "'";
    $result = pg_query($conn, $query);

    // Load User ID
    if($row = pg_fetch_assoc($result)){
        $u_id = $row['u_id'];
    
    // Load User Contacts 
    $query2 = "SELECT c_uid FROM " . $contacts_table. " WHERE u_id = '". $u_id. "'";
    $data = pg_query($conn, $query2);
    while ($row2 = pg_fetch_row($data)){
        var_dump($row2);
    }
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