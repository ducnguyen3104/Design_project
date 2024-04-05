<?php
     if(empty($_POST["Ename"])) {
        die("Enterprise name is required");
    }

    if(!preg_match('/^\d{14}$/', $_POST["SIRET"])) {
        die("Must be 14 numbers");
    }

    $mysqli = require __DIR__ ."/database.php";

    //Add to database
    session_start();
    $userID = $_SESSION["user_id"];

    $Ename = $_POST["Ename"]; 
    $checkQuery = "SELECT EnterpriseName FROM provider WHERE EnterpriseName = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $Ename);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        die("Enterprise Name already taken");
    } 

    $SIRET = $_POST["SIRET"]; 
    $checkQuery = "SELECT SIRETCode FROM provider WHERE SIRETCode = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $SIRET);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("SIRET code already taken");
    }

    $sql = "INSERT INTO provider (EnterpriseName, SIRETCode, UserID) VALUES (?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $Ename, $SIRET, $userID);

    if($stmt->execute()) {
       header("Location: signup-success.html");
    } else {
        echo"Error";
    }



?>