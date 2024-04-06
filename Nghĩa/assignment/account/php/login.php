<?php


    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $mysqli = require __DIR__ ."/database.php";

        $sql = sprintf("SELECT * FROM user WHERE Username = '%s'",
                        $mysqli->real_escape_string($_POST["uname"]));

        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();

        if($user) {
           if(password_verify($_POST["password"], $user["Password"])) {
                die("Login successful");
           } 
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
</head>
<body>
    <h1>Login</h1>

    <form action="" method="post">
        <div>
            <label for="uname">Username</label>
            <input type="text" name="uname" id="uname" value="<?= htmlspecialchars($_POST["uname"] ?? "") ?>">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <button>Log in</button>
    </form>
</body>
</html>