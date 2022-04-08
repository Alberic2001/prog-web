<?php
    include './database/Database.php';
    
    $conn = connect();

    if(mysqli_connect_errno()){
        die("Could not Connect My Sql: " .mysqli_connect_error()."<br>");
        return;
    }else {
        echo "Connection established :) <br>";
    } 

    $req = "CREATE DATABASE IF NOT EXISTS " .$db_name;

    if(!$conn->query($req)){
        die("Database not created My Sql:" .$conn->errno)."<br>";
        return;
    }else {
        echo "Database created : ".$db_name."<br>";
    }

    if(!$conn->select_db($db_name)) {
        die("Database " .$db_name ." not selected ," .$conn->errno)."<br>";
        return;
    }
    else{
        echo "Database ".$db_name." selected <br>";
    }

    $sqlFile = file_get_contents("./database/projet_ter.sql");

    if($conn->multi_query($sqlFile)){
        echo "Tables successfully created.<br>";
    } else if ($mysqli_error = mysqli_error($conn)) {
        echo $mysqli_error."<br>";
    }else{
        echo "Error: ".$conn->errno.",<br>";
    }

    mysqli_close($conn);
?>