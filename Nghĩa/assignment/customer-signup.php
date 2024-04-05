<?php
    if(empty($_POST["fname"])) {
        die("First name is required");
    }

    if(empty($_POST["lname"])) {
        die("Last name is required");
    }

    if(!preg_match('/^[A-Za-z]+$/', $_POST["fname"])) {
        die("First name can not contain number");
    }

    if(!preg_match('/^[A-Za-z]+$/', $_POST["lname"])) {
        die("Last name can not contain number");
    }

    $mysqli = require __DIR__ ."/database.php";

    //Add to database

    session_start();
    $userID = $_SESSION["user_id"];
    
    $sql = "INSERT INTO customer (FirstName, LastName, UserID) VALUES(?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $_POST["fname"], $_POST["lname"], $userID);
    if($stmt->execute()) {
        header("Location: signup-success.html");
     } else {
         echo"Error";
     }
?>