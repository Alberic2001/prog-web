<?php
    function connect($url, $username, $password)
    {
        $conn = mysqli_connect($url, $username, $password);
        if (!$conn) {
            die('Could not Connect My Sql:' . mysql_error());
            return;
        }
        return $conn;
    }