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
$users_table = 'public.messaging_app_user';
$contacts_table = 'public.messaging_app_contacts';
$messages_table = 'public.messaging_app_messages';

switch ($method):
   case 'GET':

      //Load Contact information
			$headers = getallheaders();
			$token = preg_split('/\s/', $headers['Authorization'])[1];

      $phone_number = $_GET['phone_number'];

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
      $update_read_field = "UPDATE " . $messages_table ." SET reciever_read = TRUE WHERE (sender_id = '".$r_uid."' AND reciever_id = '".$u_id."');";
      pg_query($conn, $update_read_field);

      //get all messages
      $load_messages = "SELECT * FROM " . $messages_table ." WHERE (sender_id = '".$u_id."' AND reciever_id = '".$r_uid."') OR (reciever_id = '".$u_id."' AND sender_id = '".$r_uid."') ORDER BY date ASC;";
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
         'messages' => $messages_array
      );
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
      $query3 = "INSERT INTO ".$messages_table." (sender_id, reciever_id, body_text) VALUES ('". $u_id. "', '". $r_uid. "', '".$body_text."')";
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
         $c_id  = "$r[0]\n";
      }

      //Delete Message
      $count_messages_query = "SELECT  COUNT(*) FROM " . $messages_table ." WHERE (sender_id = '".$u_id."' AND reciever_id = '".$c_id."') OR (reciever_id = '".$u_id."' AND sender_id = '".$c_id."');";
      $count_messages = pg_query($conn, $count_messages_query);
      $row = pg_fetch_row($count_messages);
      if($row[0] > 0){
         $delete_messages_query = "DELETE FROM " . $messages_table ." WHERE (sender_id = '".$u_id."' AND reciever_id = '".$c_id."') OR (reciever_id = '".$u_id."' AND sender_id = '".$c_id."');";
         pg_query($conn, $delete_messages_query);
         $output = array(
            'status_code' => 200,
            'message' => "All messages deleted"
         );
      }else{
         $output = array(
            'status_code' => 301,
            'message' => "No messages found"
         );
      }
   echo json_encode($output);
	pg_close($conn);
   break;


endswitch;  
  
?>