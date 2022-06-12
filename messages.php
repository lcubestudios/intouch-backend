<?php
// Report all errors 
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require('./config.php');

switch ($method):
   case 'GET':

      // load Contact information
      $headers = getallheaders();
      $token = preg_split('/\s/', $headers['Authorization'])[1];

      $username = $_GET['username'];

       //Get own ID with Token 
      $load_id = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_token_key} = '".$token."';";
      $result = pg_query($conn, $load_id);
      while ($row = pg_fetch_row($result)) {
         $u_id = $row[0];
      }
      // load Reciever user id
      $load_reciever_id = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_username_key} = '".$username."'";
      $result2 = pg_query($conn, $load_reciever_id);
      if($r = pg_fetch_row($result2)) {
         $r_uid = $r[0];
      }
      // update READ field
      $update_read_field = "UPDATE " . $messages_table ." SET {$db_read_status_key} = TRUE WHERE ({$db_sender_id_key} = '".$r_uid."' AND {$db_reciever_id_key} = '".$u_id."');";
      pg_query($conn, $update_read_field);

      // get all messages
      $load_messages = "SELECT * FROM " . $messages_table ." WHERE ({$db_sender_id_key} = '".$u_id."' AND {$db_reciever_id_key} = '".$r_uid."') OR ({$db_reciever_id_key} = '".$u_id."' AND {$db_sender_id_key} = '".$r_uid."') ORDER BY date ASC;";
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
         $raw_data = $data['raw_data'];
      }
      elseif( $message_type == 'image'){
         $valid_type = true;
         // load raw data
         $raw_data = $data['raw_data'];
         // extract image data from base64 data string
         $string_pieces = explode( ";base64,", $raw_data);
         $image_type_pieces = explode( "image/", $string_pieces[0] );
         // image file extension
         $file_extension = $image_type_pieces[1];
            
         if(exif_imagetype($raw_data) == IMAGETYPE_GIF ){
            $body_text = $raw_data;
            $raw_data = $raw_data;
         }else{
            // decode base64-encoded image data
            $decoded_file_data = base64_decode($string_pieces[1]);
            // Path
            $file = "/tmp/image-". uniqid() .".{$file_extension}";
            // save image data as file
            // file_put_contents($file, $decoded_file_data);
            // Create file in memory
            $file_in_memory = imagecreatefromstring($decoded_file_data);
            // load file size
            $file_size = strlen(base64_decode($raw_data));

            $max_image_height = 500;
            $max_image_filesize = 500000;
            // load image height
            $img_height_old = imagesy($file_in_memory);
            // load image width
            $img_width_old = imagesx($file_in_memory);
            
            if ($file_size > $max_image_filesize) {
               if ($img_height_old > $max_image_height) {
                  $img_height_new = $max_image_height;
                  $img_width_new = round($img_width_old * ($img_height_new / $img_height_old));
                  // create canva 
                  $resized = imagecreatetruecolor($img_width_new,$img_height_new);  
                  // copy file data into the new canva size
                  imagecopyresampled($resized, $file_in_memory, 0,0,0,0, $img_width_new,$img_height_new,$img_width_old, $img_height_old);
                  // create the file
                  if(exif_imagetype($raw_data) == IMAGETYPE_JPEG){
                     imagejpeg($resized, $file);
                  }elseif(exif_imagetype($raw_data)  == IMAGETYPE_PNG ){
                     imagepng($resized, $file);
                  }       
               }
               $image_size = filesize($file);
               $open_file = fopen($file, "r") or die("Unable to open file!");
               $read_file = fread($open_file, filesize($file));
               $raw_data = base64_encode($read_file);
               // close file
               fclose($open_file);
               $base64 = 'data:image/' . $file_extension . ';base64,' . base64_encode($read_file);
               $body_text = $base64;
            } else{
               $body_text = $raw_data;
            }
            
         }
      }

      if ($valid_type) {
        //Load User id 
        $query = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_token_key} = '".$token."'";
        $result = pg_query($conn, $query);
     
        if($row = pg_fetch_row($result)) {
            $u_id  = $row[0];
        }
     
        //Load Reciever user id
        $query2 = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_username_key} = '".$username."'";
        $result2 = pg_query($conn, $query2);
        if($r = pg_fetch_row($result2)) {
            $r_uid = $r[0];
        }
     
        //Insert Message
        $query3 = "INSERT INTO ".$messages_table." ({$db_sender_id_key}, {$db_reciever_id_key}, {$db_message_body_key}, {$db_message_type_key}, {$db_file_data_key}) VALUES ('". $u_id. "', '". $r_uid. "', '".$body_text."', '".$message_type."', '".$raw_data."')";
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
      $query = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_token_key} = '".$token."'";
      $result = pg_query($conn, $query);
      if($row = pg_fetch_row($result)) {
          $u_id  = $row[0];
      }

      //Load Reciever user id
      $query2 = "SELECT {$db_id_key} AS u_id FROM " . $users_table ." WHERE {$db_username_key} = '".$username."'";
      $result2 = pg_query($conn, $query2);
      if($r = pg_fetch_row($result2)) {
         $c_id  = "$r[0]\n";
      }

      //Delete Message
      $count_messages_query = "SELECT  COUNT(*) FROM " . $messages_table ." WHERE ({$db_sender_id_key} = '".$u_id."' AND {$db_reciever_id_key} = '".$c_id."') OR ({$db_reciever_id_key} = '".$u_id."' AND {$db_sender_id_key} = '".$c_id."');";
      $count_messages = pg_query($conn, $count_messages_query);
      $row = pg_fetch_row($count_messages);
      if($row[0] > 0){
         $delete_messages_query = "DELETE FROM " . $messages_table ." WHERE ({$db_sender_id_key} = '".$u_id."' AND {$db_reciever_id_key} = '".$c_id."') OR ({$db_reciever_id_key} = '".$u_id."' AND {$db_sender_id_key} = '".$c_id."');";
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