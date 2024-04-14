<?php
    session_start();

    if(isset($_SESSION["user_id"])) {

        $mysqli = require __DIR__ ."/account/account_php/database.php";

        $sql = "SELECT * FROM user WHERE UserID = {$_SESSION["user_id"]}";
        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc(); 

        $sql2 = "SELECT * FROM role WHERE RoleID = {$user["RoleID"]}";
        $result2 = $mysqli->query($sql2);
        $role = $result2->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <?php if(isset($user)) {
        if($role["RoleName"] == "customer") {
            $_SESSION["user_name"] = $user["Username"];
            header("Location: customer/customer.php");
            exit;
        } elseif ($role["RoleName"] == "provider") {
            $_SESSION["user_name"] = $user["Username"];
            header("Location: provider/provider.php");
            exit;
        }
    } ?>
        <h1>Home</h1>
        <p><a href="account/account_php/login.php">Log in</a> Or <a href="account/signup.html">Sign up</a></p>
    
</body>
</html>