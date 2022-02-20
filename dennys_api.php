<?php

function GettingTotalUnread(){
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
    $query3 = "SELECT COUNT(*) FROM " . $table3 ." WHERE (r_read = FALSE AND r_id = '". $u_id."' AND r_id = '". $c_id."');";
    $result3 = pg_query($conn, $query3);
    $row = pg_fetch_row($result3);
    echo "the number of unread messages from $u_id to $c_id is: ";
    echo "$row[0]\n"; 
}




?>