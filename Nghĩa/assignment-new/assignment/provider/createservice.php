<?php
    session_start();
    if(!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $_SESSION["service_name"] = $_POST["service-name"];
        $_SESSION["description"] = $_POST["description"];

        $mysqli = require __DIR__ . "/../account/account_php/database.php";

        //check if exist service
       $sqlCheck = "SELECT ServiceID FROM service WHERE ServiceName = ? AND Description = ?";
       $stmtCheck = $mysqli->prepare($sqlCheck);
       $stmtCheck->bind_param("ss", $_SESSION["service_name"], $_SESSION["description"]);
       $stmtCheck->execute();
       $stmtCheck->store_result();

       if($stmtCheck->num_rows > 0) {
           $stmtCheck->bind_result($exsitingServiceID);
           $serviceID = $exsitingServiceID;
       } else {
           $sql = "INSERT INTO service(ServiceName, Description) 
                   VALUES(?,?)";
           $stmt = $mysqli->prepare($sql);
           $stmt->bind_param("ss", $_SESSION["service_name"], $_SESSION["description"]);
           if($stmt->execute()){
               $serviceID = $mysqli->insert_id;
           } else {
               echo "Error: " . $stmtInsert->error;
           }
       }
        header("Location: createsubservice.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Service</title>

    <script
            src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="js/service-validation.js" defer></script>
</head>
<body>

    <h2>Create a service</h2>
    <form action="" id="createService" method="post">
        <div>
            <label for="serivce-name">Service name</label>
            <input type="text" name="service-name" id="service-name">
        </div>

        <div>
            <p><label for="description">Description</label></p>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
        </div>

        <button>Create</button>
    </form>

</body>
</html>