<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require('./config.php');

// $token = null;
// $headers = apache_request_headers();
// if(isset($headers['Authorization'])){
//    $matches = array();
//    preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
//    if(isset($matches[1])){
//       $token = $matches[1];
//    }
// }

switch ($method):
   case 'GET':
      $users_table = 'public.users';
      $messages_table = 'public.messages';

      //Load Contact information
      $raw=file_get_contents('php://input');
      $data=json_decode($raw,true);
      $token = $data['token'];
      $phone_number = $data['phone_number'];

       //Get own ID with Token 
      $load_id = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."';";
      $result = pg_query($conn, $load_id);
      while ($row = pg_fetch_row($result)) {
         $u_id = $row[0];
      }
      //Load Reciever user id
      $load_reciever_id = "SELECT u_id FROM " . $users_table ." WHERE phone_number = '".$phone_number."'";
      $result2 = pg_query($conn, $load_reciever_id);
      if($r = pg_fetch_row($result2)) {
         $r_uid = $r[0];
      }
      //Update READ field
      $update_read_field = "UPDATE " . $messages_table ." SET r_read = TRUE WHERE (s_id = '".$u_id."' AND r_id = '".$r_uid."');";
      pg_query($conn, $update_read_field);

      //get all messages
      $load_messages = "SELECT * FROM " . $messages_table ." WHERE (s_id = '".$u_id."' AND r_id = '".$r_uid."') OR (r_id = '".$u_id."' AND s_id = '".$r_uid."') ORDER BY date ASC;";
      $results = pg_query($conn, $load_messages);
      $messages_array = array();

      while ($row = pg_fetch_row($results)) {
         array_push($messages_array, array( 
            "sender_id" => $row[0],
            "receiver_id" => $row[1],
            "body_text" => $row[2],
            "messages_read" => $row[3],
            "date_message" => $row[4],
            "message_id" => $row[5],
            "is_sender" => $row[0] === $u_id ? true:false
         ));
      }
      //Output Contacts
      $output = array(
         'status_code' => 200,
         'contacts' => $messages_array
      );
   echo json_encode($output);
	pg_close($conn);
   break;
   case 'POST':
      $users_table = 'public.users';
      $contacts_table = 'public.contacts';
      $messages_table = 'public.messages';
      
      //Load Contact information
      $raw=file_get_contents('php://input');
      $data=json_decode($raw,true);
      $token = $data['token'];
      $phone_number = $data['phone_number'];
      $body_text = $data['body_text'];

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

      //Insert Message
      $query3 = "INSERT INTO ".$messages_table." (s_id, r_id, body_text) VALUES ('". $u_id. "', '". $r_uid. "', '".$body_text."')";
      pg_query($conn, $query3);
      //Output Contacts
      $output = array(
         'status_code' => 200,
         'message' => "message sent"
     );
   echo json_encode($output);
	pg_close($conn);
   break;

   case 'DELETE':
      $users_table = 'public.users';
      $contacts_table = 'public.contacts';
      $messages_table = 'public.messages';
      
      //Load Contact information
      $raw=file_get_contents('php://input');
      $data=json_decode($raw,true);
      $token = $data['token'];
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
         $c_id  = "$r[0]\n";
      }

      //Insert Message
      $query3 = "DELETE FROM " . $table3 ." WHERE (s_id = '".$u_id."' AND r_id = '".$c_id."') OR (r_id = '".$u_id."' AND s_id = '".$c_id."');";
      pg_query($conn, $query3);
      //Output Contacts
      $output = array(
         'status_code' => 200,
         'message' => "deleting messages"
     );
   echo json_encode($output);
	pg_close($conn);
   break;


endswitch;  
  
?>