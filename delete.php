<?php

require('./config.php');

switch ($method):
    case 'GET':
        echo("hello get");
        Getting();
        break;    
    case 'PUT':
        echo("hello PUT");
        break;
    case 'POST':
        echo("hello POST");
        break;
    case 'DELETE':
        echo("Deleting the user guy");
        Delete();
        break;
endswitch;

function Getting(){
    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phone_number = $data['phone_number'];

    $query = "SELECT u_id FROM users WHERE phone_number = '".$phone_number."' ;";
    $result = pg_query($conn, $query);
    while ($row = pg_fetch_row($result)) {
    #    echo "$row[0] $row[1] $row[2] $row[3] $row[4] $row[5]\n";
        echo "$row[0]\n";
    }

}


function Delete(){
    $raw=file_get_contents('php://input');
    $data=json_decode($raw,true);
    $phone_number = $data['phone_number'];
    #$phone_number = 9293284993;
    $delete = "DELETE FROM users WHERE phone_number = '".$phone_number."' ;";
    $result = pg_query($conn, $delete);
}
?>