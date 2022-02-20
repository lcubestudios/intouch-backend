<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require('./config.php');

$token = null;
$headers = apache_request_headers();
if(isset($headers['Authorization'])){
   $matches = array();
   preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
   if(isset($matches[1])){
      $token = $matches[1];
   }
   $uid=token_to_id($conn,$token);
   // echo $uid;
   $data=array();
   $query = "SELECT s_id, r_id, body_text, r_read, date, m_id FROM \"public\".\"Messages\" WHERE s_id = '".$uid."' or r_id = '".$uid."' ;";
   $result = pg_query($conn, $query);
   while ($row = pg_fetch_assoc($result)) {
      // $u_id = $row['s_id'];
      $data[]=$row;
      // print_r($row);
      // print_r($data);
      echo json_encode($data);
      // echo $token;
   }
}
  
?>