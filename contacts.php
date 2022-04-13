<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require('./config.php');

$users_table = 'public.messaging_app_user';
$contacts_table = 'public.messaging_app_contacts';
$messages_table = 'public.messaging_app_messages';

switch ($method):
    case 'GET':
    $output =  array();

    // Load Token
		$headers = getallheaders();
		$token = preg_split('/\s/', $headers['Authorization'])[1];
		
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
                $query4 = "SELECT COUNT(*) FROM " . $messages_table ." WHERE (reciever_read = FALSE AND reciever_id = '". $u_id."' AND sender_id = '". $c_uid."');";
                $result4 = pg_query($conn, $query4);
                $row4 = pg_fetch_row($result4);
                array_push($contact_array, array(
                    "phone_number" => $r3[0],
                    "first_name" => $r3[1],
                    "last_name" => $r3[2],
                    "u_id" => $u_id,
                    "unread" => $row4[0]
                ));
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
    break;

    case 'POST':
        //Load Contact information
				$headers = getallheaders();
				$token = preg_split('/\s/', $headers['Authorization'])[1];
				
        $raw=file_get_contents('php://input');
        $data=json_decode($raw,true);
        $phone_number = $data['phone_number'];

        //Load User id 
        $query = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."'";
        $result = pg_query($conn, $query);
    
        if($row = pg_fetch_row($result)) {
            $u_id  = $row[0];
        }

        //Load Reciever user id
        $query2 = "SELECT u_id FROM " . $users_table ." WHERE phone_number = '".$phone_number."'";
        $result2 = pg_query($conn, $query2);
        if($r = pg_fetch_row($result2)) {
            $r_uid = $r[0];
        }

        //Check if Relationship exists
        $query3 = "SELECT u_id, c_uid FROM " . $contacts_table ." WHERE u_id = '".$u_id."' AND c_uid = '".$r_uid."'" ;
        $result3 = pg_query($conn, $query3);
        if(pg_fetch_row($result3)){
            $output = array(
                'status_code' => 301,
                'message' => "Contact exist"
             );
        }
        // Create Relationship
        else{
            $query4 = "INSERT INTO " . $contacts_table . " (u_id, c_uid) VALUES ('". $u_id."', '".$r_uid."')";
            $query5 = "INSERT INTO " . $contacts_table . " (c_uid, u_id) VALUES ('". $u_id."', '".$r_uid."')";
            pg_query($conn, $query4);
            pg_query($conn, $query5);
            $output = array(
                'status_code' => 200,
                'message' => "Contact added"
             );
        }
        echo json_encode($output);
        pg_close($conn);
    break;
    case 'DELETE':
				$headers = getallheaders();
				$token = preg_split('/\s/', $headers['Authorization'])[1];

        $raw=file_get_contents('php://input');
        $data=json_decode($raw,true);
        $phone_number = $data['phone_number'];
    
        $query = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."';";
        $result = pg_query($conn, $query);
    
        if($row = pg_fetch_row($result)) {
                $u_id  = $row[0];
        }
        //getting the c_id from phone_number
        $query2 = "SELECT u_id FROM " . $users_table ." WHERE phone_number = '".$phone_number."';";
        $result2 = pg_query($conn, $query2);
        while ($row = pg_fetch_row($result2)) {
            $c_uid  = $row[0];
        }

        $delete_relationship="DELETE FROM " . $contacts_table ." WHERE (u_id = '".$u_id."' AND c_uid = '".$c_uid."');";
        $result = pg_query($conn, $delete_relationship);
         
        //Output Contacts
        $output = array(
            'status_code' => 200,
            'message' => 'Contact has been deleted!'
        );
        echo json_encode($output);
        pg_close($conn);
endswitch;
?>