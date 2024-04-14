<?php
    session_start();

    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }
    
    if(isset($_SESSION["user_id"])) {

        $mysqli = require __DIR__ ."/../account/account_php/database.php";
        $sql = "SELECT SubscriptionPackID FROM provider WHERE UserID = {$_SESSION["user_id"]}";

        $result = $mysqli->query($sql);
        $provider = $result->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Pack</title>

    <script src="js/confirm-pack.js"></script>
</head>
<body>
    <?php
        if(isset($provider["SubscriptionPackID"])) {
            $sql = "SELECT * FROM subscriptionpack WHERE SubscriptionPackID = {$provider["SubscriptionPackID"]}";
            $result = $mysqli->query($sql);
            $providerPack = $result->fetch_assoc();

            $sql2 = "SELECT * FROM subscriptiontier WHERE SubscriptionTierID = {$providerPack["SubscriptionTierID"]}";
            $result = $mysqli->query($sql2);
            $packInfo = $result->fetch_assoc();


            echo "<p></p>Current use: " .$packInfo["TierName"]. " - Provider can provide " .$packInfo["ServiceQuantityLimit"]. " Service(s) - Fee per month(EUR): " .$packInfo["FeePerMonth(EUR)"]. "</p>";  
            echo"<p><a href='provider.php'>Back to your page</a></p>";
        } 
         else { ?>
            <h1>Review our Subscription Packs</h1>
            <p><a href="provider.php">Back to your page</a></p>
            <ul>
                <?php
                    $sql = "SELECT * FROM subscriptiontier";
                    $result = $mysqli->query($sql);

                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<li><strong>" . $row["TierName"] . "</strong>: Service Quantity Limit: " . $row["ServiceQuantityLimit"] . ", Fee Per Month: " . $row["FeePerMonth(EUR)"] . " EUR, Description: " . $row["Description"] . "</li>";
                        }
                    }
                ?>
            </ul>
            
            <form action="registerpack.php" id="registerPack" method="post">
                <label for="subscription">Choose a subscription pack:</label>
                <select name="subscription_tier" id="subscription_tier">
                    <?php
                        $sql = "SELECT * FROM subscriptiontier";
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["SubscriptionTierID"] . "'>" . $row["TierName"] . "</option>";
                            }
                        }

                    ?>
                </select>
                <input type="submit" value="Confirm">
            </form>
        <?php } ?>




</body>
</html>