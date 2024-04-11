<?php
    $mysqli = require __DIR__ ."/database.php";

    $sql = "INSERT INTO address (CustomerAddress, ProviderAddress)
            VALUES (?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $_POST["caddress"], $_POST["paddress"]);
    if($stmt->execute()) {
        echo "Success";
    } else {
        echo "Fail";
    }

?>