<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

##COMPOSER NEEDED!##
// require_once realpath(__DIR__ . '/vendor/autoload.php');

// Looing for .env at the root directory
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

/* 
DB_USER="postgres"
DB_PORT=6000
DB_PASSWORD="postgres"
#DB_NAME="messingapp"
DB_CONTAINER_NAME="postgres"
DB_TAG="latest"

*/

// Retrive env variable
$host = "172.27.0.2";
$db_user = "postgres";
$db_pass = "postgres";
$db = "intouch";
$port = 5432;

// Create connection
$conn = pg_connect("host=$host port=$port dbname=$db user=$db_user password=$db_pass");

$method = $_SERVER['REQUEST_METHOD'];

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
                       }
        // print_r($data);
        echo json_encode($data);
      // echo $token;
}

function token_to_id($conn,$token)
{
   $query = "SELECT u_id,phone_number FROM \"public\".\"Login\" WHERE token = '".$token."' ;";
      $result = pg_query($conn, $query);
      if ($row = pg_fetch_assoc($result)) {
         $u_id = $row['u_id'];
         $phone_number = $row['phone_number'];
         // $query2 = "SELECT * FROM \"public\".\"Messages\" WHERE s_id = '".$u_id."' ;";
         // $response = pg_query($conn, $query2);
         // if($row =  pg_fetch_assoc($response)){
         //       $message = $row['body_text'];
                  # need to look  throuhg read messages
         //}
      }
      return($u_id);
      // print_r($row);
}

?>