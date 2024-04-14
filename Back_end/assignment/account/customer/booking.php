<?php
session_start();

if(!isset($_SESSION["user_name"])) {
    header("Location: /assignment/account/account_php/logout.php");
}

$mysqli = require __DIR__ ."/../account/account_php/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $providerId = $_POST['provider_id'];
    $service_id = $_POST['service_id'];
    $subservice_id = $_POST['subservice_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $addressID = $_POST['address'];

    $time = date("H:i:s", strtotime($time));
    $timeBooking = $date .' '. $time;
  
    $sql = "SELECT * FROM customer WHERE UserID = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    $customerID = $user["CustomerID"];

    $sql = "INSERT INTO booking(Date, Time, CustomerID, ProviderID, AddressID)
            VALUES (?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssiii", $date, $timeBooking, $customerID, $providerId,$addressID);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
</head>
<body>
    <?php
        if($stmt->execute()) {
            $sql = "SELECT * FROM address WHERE AddressID = {$addressID}";
            $result = $mysqli->query($sql);
            $address = $result->fetch_assoc();
            $streetAddress = $address["StreetAddress"];
            $formattedDate = date("F j, Y", strtotime($date));
            
            echo "<h2>Booking Successfull</h2>";
            echo"<p>Your booking take place at {$streetAddress}, located at {$time} on {$formattedDate}</p>";
            echo "<a href='customer.php'>Confirm</a>";
        }
    ?>
</body>
</html>