<?php
    session_start();

    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }

    if(isset($_SESSION["user_id"])) {

        $mysqli = require __DIR__ ."/../account/account_php/database.php";
       
        $sql = "SELECT * FROM provider WHERE UserID = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        $provider = $result->fetch_assoc();
        $providerID = $provider["ProviderID"];

        $sql = "SELECT * FROM providerservicelist WHERE ProviderID = {$providerID}";
        $providerService = $mysqli->query($sql);

        $sql = "SELECT * FROM service";
        $serviceInfo = $mysqli->query($sql);

        
        $sqlDistinctServiceCount = "SELECT COUNT(DISTINCT service.ServiceID) AS ServiceCount
                                    FROM providerservicelist
                                    INNER JOIN subservice ON providerservicelist.SubServiceID = subservice.SubServiceID
                                    INNER JOIN service ON subservice.ServiceID = service.ServiceID
                                    WHERE providerservicelist.ProviderID = ?";
        $stmtDistinctServiceCount = $mysqli->prepare($sqlDistinctServiceCount);
        $stmtDistinctServiceCount->bind_param("i", $providerID);
        $stmtDistinctServiceCount->execute();
        $resultDistinctServiceCount = $stmtDistinctServiceCount->get_result();
        $distinctServiceCount = $resultDistinctServiceCount->fetch_assoc()["ServiceCount"];

        
        $subscriptionPackID = $provider["SubscriptionPackID"];

        $sqlSubscriptionPackTier = "SELECT * FROM subscriptionpack WHERE SubscriptionPackID = $subscriptionPackID";

        $result = $mysqli->query($sqlSubscriptionPackTier);
        $subscriptionTierID = $result->fetch_assoc()["SubscriptionTierID"];



        
        $sqlServiceQuantityLimit = "SELECT ServiceQuantityLimit FROM subscriptiontier WHERE SubscriptionTierID = ?";
        $stmtServiceQuantityLimit = $mysqli->prepare($sqlServiceQuantityLimit);
        $stmtServiceQuantityLimit->bind_param("i", $subscriptionTierID);
        $stmtServiceQuantityLimit->execute();
        $resultServiceQuantityLimit = $stmtServiceQuantityLimit->get_result();
        $serviceQuantityLimit = $resultServiceQuantityLimit->fetch_assoc();
        $serviceQuantityLimit = $serviceQuantityLimit["ServiceQuantityLimit"];

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
</head>
<body>
    <?php
        if($providerService->num_rows > 0) { ?>
            <h1>Your Services:</h1>
            <p><a href="provider.php">Back to your page</a></p>
            <?php
                if ($distinctServiceCount >= $serviceQuantityLimit) {
                    echo "<p>You have reached the maximum limit of services allowed in your subscription tier.</p>";
                } 
                else { ?>
                    <p><a href="createservice.php">Add Service</a></p>
               <?php } ?>
            <p><a href="createsubservice.php">Add Sub Service</a></p>
            <?php

               
                $sql = "SELECT ProviderID FROM provider WHERE UserID = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $_SESSION["user_id"]);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $providerRow = $result->fetch_assoc();
                    $providerID = $providerRow['ProviderID'];

                    
                    $sqlServices = "SELECT DISTINCT s.ServiceID, s.ServiceName, s.Description
                                    FROM service s
                                    INNER JOIN subservice ss ON s.ServiceID = ss.ServiceID
                                    INNER JOIN providerservicelist psl ON ss.SubServiceID = psl.SubServiceID
                                    WHERE psl.ProviderID = ?";
                    $stmtServices = $mysqli->prepare($sqlServices);
                    if (!$stmtServices) {
                        die("Failed to prepare statement: " . $mysqli->error);
                    }
                    $stmtServices->bind_param("i", $providerID);
                    if (!$stmtServices->execute()) {
                        die("Failed to execute statement: " . $stmtServices->error);
                    }
                    $resultServices = $stmtServices->get_result();
                    if (!$resultServices) {
                        die("Failed to get result set: " . $stmtServices->error);
                    }

                   
                    if ($resultServices->num_rows > 0) {
                        while ($row = $resultServices->fetch_assoc()) {
                            echo "<h2>{$row['ServiceName']} - {$row['Description']}</h2>";
                            
                            
                            $sqlSubservices = "SELECT ss.SubServiceID, ss.SubserviceName, ss.Description, psl.`ServicePrice(EUR)`
                                                FROM subservice ss
                                                INNER JOIN providerservicelist psl ON ss.SubServiceID = psl.SubserviceID
                                                WHERE psl.ProviderID = ? AND ss.ServiceID = ?";
                    
                            $stmtSubservices = $mysqli->prepare($sqlSubservices);
                            if (!$stmtSubservices) {
                                die("Failed to prepare statement: " . $mysqli->error);
                            }
                            $stmtSubservices->bind_param("ii", $providerID, $row['ServiceID']);
                            if (!$stmtSubservices->execute()) {
                                die("Failed to execute statement: " . $stmtSubservices->error);
                            }
                            $resultSubservices = $stmtSubservices->get_result();
                            if (!$resultSubservices) {
                                die("Failed to get result set: " . $stmtSubservices->error);
                            }
                            
                            
                            if ($resultSubservices->num_rows > 0) {
                                echo "<ul>";
                                while ($subRow = $resultSubservices->fetch_assoc()) {
                                    echo "<li>{$subRow['SubserviceName']} - {$subRow['Description']} (Price: {$subRow['ServicePrice(EUR)']} EUR)</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>No subservices found for this service.</p>";
                            }
                            
                            
                            $stmtSubservices->close();
                        }
                    } else {
                        echo "<p>No services found for this provider.</p>";
                    }

                   
                    $stmtServices->close();
                } else {
                    echo "<p>No provider found for this user.</p>";
                }

            
            ?>

        <?php }
        else { ?>
            <p>You don't have any service <a href="createservice.php">Create one</a></p>
            
        <?php } ?>
</body>
</html>