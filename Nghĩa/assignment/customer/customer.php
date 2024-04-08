<?php

    session_start();

    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }

    $mysqli = require __DIR__ ."/../account/account_php/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
    <script src="js/booking.js"></script>
</head>
<body>
    <h1>Hi Customer <?php echo htmlspecialchars($_SESSION["user_name"])?></h1>
    <p><a href="/assignment/account/account_php/logout.php">Log out</a></p>
    <p><a href="viewBookingHistory.php">View booking history</a></p>
    <h1>Search Service</h1>
    <form action="search-service.php" method="POST">
        <input type="text" name="service_name" placeholder="Enter service name">
        <button type="submit">Search</button>
    </form>

    <?php
    
    if (isset($_GET['search_result'])) {
        $search_results = json_decode(urldecode($_GET['search_result']), true);
        if (!empty($search_results)) {
            echo "<h2>Search Results:</h2>";
            foreach ($search_results as $row) {
                echo "<div id='result_{$row['SubServiceID']}'>";
                echo "<p>Provider Name: {$row['EnterpriseName']}</p>";
                echo "<p>Service Name: {$row['ServiceName']}</p>";
                echo "<p>Subservice Name: {$row['SubServiceName']}</p>";
                echo "<p>Price (EUR): {$row['ServicePrice(EUR)']}</p>";
                // Display button to show booking form
                echo "<button onclick='toggleBookingForm({$row['SubServiceID']})'>Book</button>";
                // Booking form for each sub-service
                echo "<div id='booking_form_{$row['SubServiceID']}' style='display: none;'>";
                echo "<form action='booking.php' method='POST'>";
                echo "<input type='hidden' name='provider_id' value='{$row['ProviderID']}'>";
                echo "<input type='hidden' name='service_id' value='{$row['ServiceID']}'>";
                echo "<input type='hidden' name='subservice_id' value='{$row['SubServiceID']}'>";
                echo "<label for='date_{$row['SubServiceID']}'>Date:</label>";
                echo "<input type='date' id='date_{$row['SubServiceID']}' name='date' required><br><br>";
                echo "<label for='time_{$row['SubServiceID']}'>Time:</label>";
                echo "<input type='time' id='time_{$row['SubServiceID']}' name='time' required><br><br>";

                $sql = "SELECT * FROM provider WHERE ProviderID =  {$row['ProviderID']}";
                $result = $mysqli->query($sql);
                $user = $result->fetch_assoc();
                $userID = $user["UserID"];

                $sql = "SELECT * FROM user WHERE UserID = $userID";
                $result = $mysqli->query($sql);
                $user = $result->fetch_assoc();
                $providerAddressID = $user["AddressID"];

                $sql = "SELECT * FROM address WHERE AddressID = $providerAddressID";
                $result = $mysqli->query($sql);
                $address = $result->fetch_assoc();
                $providerAddressName = $address["StreetAddress"];

                $sql = "SELECT * FROM user WHERE UserID = {$_SESSION["user_id"]}";
                $result = $mysqli->query($sql);
                $user = $result->fetch_assoc();
                $customerAddressID = $user["AddressID"];

                $sql = "SELECT * FROM address WHERE AddressID = $customerAddressID";
                $result = $mysqli->query($sql);
                $address = $result->fetch_assoc();
                $customerAddressName = $address["StreetAddress"];

                echo "<select name='address' id='address'><option value='".$providerAddressID."'>{$providerAddressName}</option><option value='".$customerAddressID."'>{$customerAddressName}</option></select>";
                echo"<br>";
                echo "<button type='submit'>Book</button>";
                echo "</form></div>";
                echo "</div>";
            }
        } else {
            echo "No providers offer the specified service.";
        }
    }
    ?>
</body>
</html>






