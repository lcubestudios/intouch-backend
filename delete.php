<?php

require('./config.php');
        

switch ($method):
    case 'GET':
        echo("hello get");
        Getting();
        break;    
    case 'DELETE':
        echo("Deleting the user guy \n");
        DeleteRelationship();
        break;
endswitch;

function Getting(){
    require('./config.php');

    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phone_number = $data['phone_number'];

    $query = "SELECT * FROM users WHERE phone_number = '".$phone_number."';";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
    //    echo "$row[0] $row[1] $row[2] $row[3] $row[4] $row[5]\n";
        echo "$row[0]\n";
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
    $table2 = 'public.contacs';

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