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
        echo("Deleting the user guy \n");
        DeleteRelationship();
        //DeleteContact();
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
    $c_id = $data['c_id'];

    //Get own ID with Token 
    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
        $u_id = "$row[0]";
    }
    //get all messages
    $query2 = "SELECT * FROM " . $table3 ." WHERE (s_id = '".$u_id."' AND r_id = '".$c_id."') OR (r_id = '".$u_id."' AND s_id = '".$c_id."');";
    $result2 = pg_query($conn, $query2);
    while ($row = pg_fetch_row($result2)) {
        $sender_id = "$row[0]";
        $receiver_id = "$row[1]";
        $body_text = "$row[2]";
        $messages_read = "$row[3]";
        $date_message = "$row[4]";
        $message_id = "$row[5]";

        //get all messages in json format
        $datos=array("s_id"=>$sender_id,"r_id"=>$receiver_id,"body_text"=>$body_text,"r_read"=>$messages_read,"date"=>$date_message,"m_id"=>$message_id);
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

    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);

    while ($row = pg_fetch_row($result)) {
            $u_id  = "$row[0]\n";
            echo "the u_id is: ";
            echo $u_id;
        }
    $delete_relationship="DELETE FROM " . $table2 ." WHERE u_id = '".$u_id."' ;";
    $result = pg_query($conn, $delete_relationship);
    echo "deleting the relationship of: ";
    echo $token;

}
?>