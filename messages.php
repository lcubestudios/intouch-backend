<?php
// Report all errors 
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require('./config.php');

// $users_table = 'public.messaging_app_user';
// $contacts_table = 'public.messaging_app_contacts';
// $messages_table = 'public.messaging_app_messages';

switch ($method):
   case 'GET':

      //Load Contact information
      $headers = getallheaders();
      $token = preg_split('/\s/', $headers['Authorization'])[1];

      $username = $_GET['username'];

       //Get own ID with Token 
      $load_id = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."';";
      $result = pg_query($conn, $load_id);
      while ($row = pg_fetch_row($result)) {
         $u_id = $row[0];
      }
      //Load Reciever user id
      $load_reciever_id = "SELECT u_id FROM " . $users_table ." WHERE username = '".$username."'";
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
            "message_type" => $row[6],
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
      $username = $data['username'];
      $message_type = $data['message_type'];
      $valid_type = false;

      if ($message_type == 'text'){
         $valid_type = true;
         $body_text = $data['body_text'];
      }
      elseif( $message_type == 'image'){
         $valid_type = true;
         $body_text = $data['raw_data'];
         // $string_pieces = explode( ";base64,", $raw_data);
         // $image_type_pieces = explode( "image/", $string_pieces[0] );
         // $image_type = $image_type_pieces[1];
         // $data = base64_decode($string_pieces[1]);
         // $image = imagecreatefromstring($data);
         
         // $max_image_height = 500;
         // $max_image_filesize = 500000;

         // $img_height_old = imagesy($image);
         // $img_width_old = imagesy($image);
         //$image_size = getimagesizefromstring($data);
         // print_r($image_size);
         // if ($image_size > $max_image_filesize) {
         //    if ($img_height_old > $max_image_height) {
         //       $img_height_new = $max_image_height;
         //       $img_width_new = $img_width_old * ($img_height_new / $img_height_old);
      
         //       $image = imagecreatetruecolor($img_width_new,$img_height_new);
         //    }

         //    $image_size = strlen($image);
         //    echo $image_size;

         //    $compress_value = ($max_image_filesize / $image_size) * 100;
         // }

         // // encode
         // $new_img_data = file_get_contents($image);
         // $b64 = base64_encode($new_img_data);
         // $body_text = $b64;

         // $file_name = $_FILES['file']['name'];
         // $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
         // $file_size = $_FILES['file']['size'];
         // $file_tmp= $_FILES['file']['tmp_name']; #tmp name
         // $data = file_get_contents($file_tmp);
         // $base64 = 'data:image/' . $file_extension . ';base64,' . base64_encode($data);
         // $body_text = $base64;
      }

      if ($valid_type) {
        //Load User id 
        $query = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."'";
        $result = pg_query($conn, $query);
     
        if($row = pg_fetch_row($result)) {
            $u_id  = $row[0];
        }
     
        //Load Reciever user id
        $query2 = "SELECT u_id FROM " . $users_table ." WHERE username = '".$username."'";
        $result2 = pg_query($conn, $query2);
        if($r = pg_fetch_row($result2)) {
            $r_uid = $r[0];
        }
     
        //Insert Message
        $query3 = "INSERT INTO ".$messages_table." (sender_id, reciever_id, body_text, message_type) VALUES ('". $u_id. "', '". $r_uid. "', '".$body_text."', '".$message_type."')";
        pg_query($conn, $query3);
        //Output Contacts
        $output = array(
           'status_code' => 200,
           'message' => "message sent"
       );
      }
      else {
         $output = array(
            'status_code' => '500', 
            'message'=> 'Error, Invalid message type'
         );
      }
   echo json_encode($output);
   pg_close($conn);
   break;

   case 'DELETE':
      //Load Contact information
      $headers = getallheaders();
      $token = preg_split('/\s/', $headers['Authorization'])[1];
			
      $raw=file_get_contents('php://input');
      $data=json_decode($raw,true);
      $username = $data['username'];

      //Load User id 
      $query = "SELECT u_id FROM " . $users_table ." WHERE token = '".$token."'";
      $result = pg_query($conn, $query);
      if($row = pg_fetch_row($result)) {
          $u_id  = $row[0];
      }

      //Load Reciever user id
      $query2 = "SELECT u_id FROM " . $users_table ." WHERE username = '".$username."'";
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
