<?php
function connect()
{
    $url = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'projet_ter';
    $conn = mysqli_connect($url, $username, $password, $db_name);
    if (!$conn) {
        die('Could not Connect My Sql:' . mysql_error());
        return;
    }
    return $conn;
}