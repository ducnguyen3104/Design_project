<?php
    $host = "localhost";
    $dbname = "beautysalon";
    $username = "root";
    $password = "A1qwertyuiop@";

    $mysqli = new mysqli(hostname:$host, 
                         username: $username, 
                         password: $password, 
                         database: $dbname);

    if($mysqli->connect_errno) {
        die("Connection error: " .$mysqli->connect_errno);
    }

    return $mysqli;


?>

