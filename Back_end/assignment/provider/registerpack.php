<?php
    session_start();
    $mysqli = require __DIR__ ."/../account/account_php/database.php";

    $add_pack_success = false;

    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $start_date = date("Y-m-d H:i:s");
    $end_date = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($start_date)));

    $subscriptionTierID = $_POST["subscription_tier"];


    //check if exist
    $checkQuery = "SELECT SubscriptionPackID FROM subscriptionpack WHERE StartDate = ? AND EndDate = ? AND SubscriptionTierID = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param("ssi",$start_date, $end_date, $subscriptionTierID);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if($stmtCheck->num_rows > 0) {
        $stmtCheck->bind_result($exsitingPackID);
        $subscriptionpackID = $exsitingPackID;
    } else {
        $sql = "INSERT INTO subscriptionpack (StartDate, EndDate, SubscriptionTierID)
                VALUES (?,?,?)";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $start_date, $end_date, $subscriptionTierID);
        if($stmt->execute()){
            $subscriptionpackID = $mysqli->insert_id;
        } else {
            echo "Error: " . $stmtInsert->error;
        }
    }

    $sql = "UPDATE provider 
            SET SubscriptionPackID = {$subscriptionpackID}
            WHERE UserID = {$_SESSION["user_id"]}";
    
    if ($mysqli->query($sql) === TRUE) {
            $add_pack_success = true;
      } else {
        die("Error updating record: " . $mysqli->error);
      }

      if($add_pack_success) {
        echo"<h1>Your subscription pack added successfully <a href='provider.php'>Back to your page</a></h1>";
    }
?>

    
    