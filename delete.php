<?php

require('./config.php');
        

switch ($method):
    case 'GET':
        echo "";
        Getting();
        echo"";
        break;    
    case 'DELETE':
        echo("Deleting the user guy \n");
        DeleteRelationship();
        break;
endswitch;

function Getting(){
    require('./config.php');
    $table = 'public.users';
    $table3 = 'public.messages';

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $token = $data['token'];

    $query = "SELECT u_id FROM " . $table ." WHERE token = '".$token."';";
    $result = pg_query($conn, $query);
    while ($rowing = pg_fetch_row($result)) {
        $u_id = "$rowing[0]";
    }

    $query2 = "SELECT body_text,date,r_read,r_id FROM " . $table3 ." WHERE s_id = '".$u_id."';";
    $result2 = pg_query($conn, $query2);
    while ($row = pg_fetch_row($result2)) {
        //echo "\n the u_id is: ";
        //echo $u_id;
        $messages = "$row[0]";
        //echo "\nthe message is: ";
        //echo $messages;
        $date_message = "$row[1]";
        //echo "\nthe date is: ";
        //echo $date_message;
        $r_read = "$row[2]";
        //echo "\nthe Message was read: ";
        //echo $r_read;
        $r_id = "$row[3]";
        //echo "\nthe receiber id is: ";
        //echo $r_id;

        $query3 = "SELECT phone_number FROM " . $table ." WHERE u_id = '".$r_id."';";
        $result3 = pg_query($conn, $query3);
        while ($rowe = pg_fetch_row($result3)) {
            $phone_number = "$rowe[0]";
            //echo "\nthe phone number is: ";
            //echo $phone_number;
            //echo "\n";
        }
        //$datos=["phone_number:",$phone_number,"r_id :",$r_id];
        //echo json_encode ($datos, JSON_PRETTY_PRINT);

        $datos=array("uid"=>$r_id,"phone_number"=>$phone_number,"unread_count"=>$r_read,"last_message"=>["body"=>$messages,"dt_created"=>$date_message,"timestamp"=>$date_message]);
        echo json_encode ($datos, JSON_PRETTY_PRINT);
        echo "\n";
    }

}


function Delete(){
    require('./config.php');

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phone_number = $data['phone_number'];
    //$phone_number = 9293284993;


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