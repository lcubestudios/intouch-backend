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
   case 'POST':
      $users_table = 'public.users';
      $contacts_table = 'public.contacts';
      $messages_table = 'publis.messages';
      
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
         'message' => "new message"
     );
   echo json_encode($output);
	pg_close($conn);
   break;

endswitch;  
  
?>