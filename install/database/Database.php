<?php
    $url = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'projet_ter';

    function connect()
    {
        $conn = mysqli_connect($url, $username, $password);
        if (!$conn) {
            die('Could not Connect My Sql:' . mysql_error());
            return;
        }
        return $conn;
    }