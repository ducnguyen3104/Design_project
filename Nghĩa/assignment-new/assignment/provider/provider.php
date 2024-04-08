<?php
    session_start();

    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider</title>
</head>
<body>
    <h1>Hi Provider <?php echo htmlspecialchars($_SESSION["user_name"])?></h1>
    <p><a href="/assignment/account/account_php/logout.php">Log out</a></p>

    <p><a href="subscriptionpack.php">Subscription Pack</a></p>
    <p><a href="service.php">Services</a></p>
    <p><a href="viewCustomerBooking.php">View Booking</a></p>


    
</body>
</html>