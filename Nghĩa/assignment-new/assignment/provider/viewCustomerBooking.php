<?php
    session_start();

    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }

    $mysqli = require __DIR__ ."/../account/account_php/database.php";

    $sql = "SELECT * FROM provider WHERE UserID = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $provider = $result->fetch_assoc();
    $providerID = $provider["ProviderID"];
    $providerEnterprise = $provider["EnterpriseName"];

    $sql = "SELECT * FROM booking WHERE ProviderID = $providerID";
    $result_booking = $mysqli->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
    <?php
        if($result_booking->num_rows > 0){
            echo "<h2>Customer Booking:</h2>";
            echo "<table style='width:100%'>";
            echo "<tr>
                    <th>Booking no</th>
                    <th>Customer Name</th>
                    <th>Enterprise Name</th>
                    <th>Time</th>
                    <th>Street Address</th>
                </tr>";
            $index = 1;
            while($booking = $result_booking->fetch_assoc()){
                $customerID = $booking["CustomerID"];
                $addressID = $booking["AddressID"];

                $sql = "SELECT * FROM customer WHERE CustomerID = $customerID";
                $result_customer = $mysqli->query($sql);
                $customer = $result_customer->fetch_assoc();
                $customerName = $customer["FirstName"] .' '. $customer["LastName"];

                $sql = "SELECT * FROM address WHERE AddressID = $addressID";
                $result_address = $mysqli->query($sql);
                $address = $result_address->fetch_assoc();
                $streetName = $address["StreetAddress"];

                $time = $booking["Time"];
                echo "<tr>
                        <td style='text-align: center;'>$index</td>
                        <td style='text-align: center;'>$customerName</td>
                        <td style='text-align: center;'>$providerEnterprise</td>
                        <td style='text-align: center;'>$time</td>
                        <td style='text-align: center;'>$streetName</td>
                    </tr>";
                $index += 1;
            }
            echo "</table>";
        }
        else {
            echo "<h2>You don't have any booking</h2>";
        }
    ?>
    <p><a href="provider.php">Back to your page</a></p>
</body>
</html>