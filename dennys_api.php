<?php
require('./config.php');

switch ($method):
    case 'GET':
        echo "";
        //Getting();
        GetMessages();
        echo"";
        break;    
    case 'DELETE':
        //DeleteRelationship();
        //DeleteContact();
        DeleteMessages();
        break;
endswitch;

function Getting(){
    require('./config.php');
    $table = 'public.users';
    $table3 = 'public.messages';

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];

    //Get own ID with Token 
    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
        $u_id = "$row[0]";
    }

    $query2 = "SELECT body_text,date,r_read,r_id FROM " . $table3 ." WHERE s_id = '".$u_id."';";
    $result2 = pg_query($conn, $query2);
    while ($row = pg_fetch_row($result2)) {
        $messages = "$row[0]";
        $date_message = "$row[1]";
        $r_read = "$row[2]";
        $r_id = "$row[3]";

        $query3 = "SELECT phone_number FROM " . $table ." WHERE u_id = '".$r_id."';";
        $result3 = pg_query($conn, $query3);
        while ($row = pg_fetch_row($result3)) {
            $phone_number = "$row[0]";
        }

        $datos=array("uid"=>$r_id,"phone_number"=>$phone_number,"unread_count"=>$r_read,"last_message"=>["body"=>$messages,"dt_created"=>$date_message,"timestamp"=>$date_message]);
        echo json_encode ($datos, JSON_PRETTY_PRINT);
        echo "\n";
    }

}

function GetMessages(){
    require('./config.php');
    $table = 'public.users';
    $table3 = 'public.messages';

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];
    $phone_number = $data['phone_number'];

    //Get own ID with Token 
    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
        $u_id = "$row[0]";
    }

    //getting the c_id from phone_number
    $query2 = "SELECT u_id FROM " . $table ." WHERE phone_number = '".$phone_number."';";
    $result2 = pg_query($conn, $query2);
    while ($row = pg_fetch_row($result2)) {
            $c_id  = "$row[0]\n";
    }

    $query_update = "UPDATE " . $table3 ." SET r_read = TRUE WHERE s_id = '".$u_id."';";
    $result_update = pg_query($conn, $query_update);

    //get all messages
    $query3 = "SELECT * FROM " . $table3 ." WHERE (s_id = '".$u_id."' AND r_id = '".$c_id."') OR (r_id = '".$u_id."' AND s_id = '".$c_id."');";
    $result3 = pg_query($conn, $query3);
    while ($row = pg_fetch_row($result3)) {
        $sender_id = "$row[0]";
        echo "this is the sender_id $sender_id";
        $receiver_id = "$row[1]";
        $body_text = "$row[2]";
        $messages_read = "$row[3]";
        $date_message = "$row[4]";
        $message_id = "$row[5]";

        //getting the name and lastname of the sender
        $query_sender = "SELECT first_name,last_name FROM " . $table ." WHERE u_id = '".$sender_id."';";
        $result_sender = pg_query($conn, $query_sender);
        while ($row = pg_fetch_row($result_sender)) {
            $sender_name = "$row[0]";
            $sender_lastname = "$row[1]";
            $sender = "$sender_name $sender_lastname";
        }
     
        //getting the name and lastname of the receiver
        $query_receiver = "SELECT first_name,last_name FROM " . $table ." WHERE u_id = '".$receiver_id."';";
        $result_receiver = pg_query($conn, $query_receiver);
        while ($row = pg_fetch_row($result_receiver)) {
                $receiver_name = "$row[0]";
                $receiver_lastname = "$row[1]";
                $receiver = "$receiver_name $receiver_lastname";
            }

        //get all messages in json format
        $datos=array("sender"=>$sender,"s_id"=>$sender_id,"receiver"=>$receiver,"r_id"=>$receiver_id,"body_text"=>$body_text,"r_read"=>$messages_read,"date"=>$date_message,"m_id"=>$message_id);
        echo json_encode ($datos, JSON_PRETTY_PRINT);
        echo "\n";
        }

}


function DeleteContact(){
    require('./config.php');

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phone_number = $data['phone_number'];

    //delete contact with the phone number
    $delete = "DELETE FROM users WHERE phone_number = '".$phone_number."' ;";
    $result = pg_query($conn, $delete);
}


function DeleteRelationship(){
    require('./config.php');
    $table = 'public.users';
    $table2 = 'public.contacts';

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];
    $phone_number = $data['phone_number'];

    //getting the u_id from token
    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
            $u_id  = "$row[0]\n";
            echo "the u_id is: ";
            echo $u_id;
        }

    //getting the c_id from phone_number
    $query2 = "SELECT u_id FROM " . $table ." WHERE phone_number = '".$phone_number."';";
    $result2 = pg_query($conn, $query2);
    while ($row = pg_fetch_row($result2)) {
            $c_id  = "$row[0]\n";
            echo "the u_id is: ";
            echo $c_id;
        }

    $delete_relationship="DELETE FROM " . $table2 ." WHERE (u_id = '".$u_id."' AND c_uid = '".$c_id."');";
    $result = pg_query($conn, $delete_relationship);
    echo "deleting the relationship of: ";
    echo $u_id;
    echo " and ";
    echo $c_id;

}

function DeleteMessages(){
    require('./config.php');
    $table = 'public.users';
    $table3 = 'public.messages';

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];
    $phone_number = $data['phone_number'];

    //Get own ID with Token 
    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    $row = pg_fetch_row($result);
    $u_id = "$row[0]";

    //getting the c_id from phone_number
    $query2 = "SELECT u_id FROM " . $table ." WHERE phone_number = '".$phone_number."';";
    $result2 = pg_query($conn, $query2);
    $row = pg_fetch_row($result2);
    $c_id  = "$row[0]\n";
    echo "the c_id is: ";
    echo $c_id;

    //get all messages
    $delete_messages = "DELETE FROM " . $table3 ." WHERE (s_id = '".$u_id."' AND r_id = '".$c_id."') OR (r_id = '".$u_id."' AND s_id = '".$c_id."');";
    $result2 = pg_query($conn, $delete_messages);
    echo "deleting all the messages";
       
}
?>