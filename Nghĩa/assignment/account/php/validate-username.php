<?php

    $mysqli = require __DIR__ ."/database.php";

    $sql = sprintf("SELECT * FROM user WHERE Username = '%s'", 
                    $mysqli->real_escape_string($_GET["uname"]));

    $result = $mysqli->query($sql);

    $is_available = $result->num_rows === 0;

    header("Content-Type: application/json");

    echo json_encode(["available" => $is_available]);
?>  