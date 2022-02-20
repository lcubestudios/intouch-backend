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
    
        $query2 = "SELECT c_uid FROM " . $contacts_table. " WHERE u_id = '". $u_id. "'";
        $result2 = pg_query($conn, $query2);
        $contact_array = array();

        // Load Contacts information
        while ($r = pg_fetch_row($result2)) {
            $c_uid  = $r[0];
            $query3 = "SELECT phone_number, first_name, last_name FROM " . $users_table. " WHERE u_id = '". $c_uid. "'";
            $result3 = pg_query($conn, $query3);
            while($r3 = pg_fetch_row($result3)) {
                print_r($r3);
                // $phone_number = $r3[0];
                // $first_name = $r3[1];
                // $last_name = $r3[2];

                array_push($contact_array, $r3);
            }
        }

         //Output Contacts
         $output = array(
            'status_code' => 200,
            'contacts' => $contact_array
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