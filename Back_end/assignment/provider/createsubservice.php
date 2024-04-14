<?php
    session_start();

    $add_success = false;

    if (!isset($_SESSION["user_name"])) {
        header("Location: /assignment/account/account_php/logout.php");
        exit; 
    }
    $mysqli = require __DIR__ . "/../account/account_php/database.php";

    $sql = "SELECT ProviderID FROM provider WHERE UserID = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);


    $providerRow = $result->fetch_assoc();
    $providerID = $providerRow['ProviderID'];

    $sql = "SELECT * FROM service WHERE ServiceName = ? AND Description = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $_SESSION["service_name"], $_SESSION["description"]);
    $stmt->execute();
    $service = $stmt->get_result();
    $service = $service->fetch_assoc();
    $serviceID = $service["ServiceID"];
    

    $sql = "SELECT * FROM subservice WHERE ServiceID = $serviceID";
    $result = $mysqli->query($sql);
    $subservice = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        if(isset($subservice)) {
            $serviceID = $_POST["service"];
        }

       //Check if exist subservice
       $sqlCheck = "SELECT SubServiceID FROM subservice 
                    WHERE SubserviceName = ? AND
                          Description = ? AND
                          ServiceId = ?";
       $stmtCheck = $mysqli->prepare($sqlCheck);
       $stmtCheck->bind_param("ssi", $_POST["sub-service-name"], $_POST["sub-description"], $serviceID);
       $stmtCheck->execute();
       $stmtCheck->store_result();

       if($stmtCheck->num_rows > 0) {
           $stmtCheck->bind_result($existingSubserviceID);
           $subserviceID = $existingSubserviceID;
       } else {
           $sql = "INSERT INTO subservice(SubServiceName, Description, ServiceID)
                   VALUES (?,?,?)";
           $stmt = $mysqli->prepare($sql);
           $stmt->bind_param("ssi", $_POST["sub-service-name"], $_POST["sub-description"], $serviceID);
           if($stmt->execute()){
               $subserviceID = $mysqli->insert_id;
           } else {
               echo "Error: " . $stmtInsert->error;
           }
       }


       //Check if exist providerservicelist
       $sqlCheck = "SELECT ProviderId FROM providerservicelist WHERE
                   ProviderID = ? AND SubServiceID = ?";
       $stmtCheck = $mysqli->prepare($sqlCheck);
       $stmtCheck->bind_param("ii", $providerID, $subserviceID);
       $stmtCheck->execute();
       $stmtCheck->store_result();

       if(!$stmtCheck->num_rows > 0) {
           $sql = "INSERT INTO providerservicelist VALUES (?,?,?)";
           $stmt = $mysqli->prepare($sql);
           $price = doubleval($_POST["price"]);
           $stmt->bind_param("iid", $providerID, $subserviceID, $price);

           if($stmt->execute()){
               $add_success = true;
           } else {
               echo "Error: " . $stmt->error;
           }

       }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create subservice</title>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="js/subservice-validation.js" defer></script>
</head>

<body>
    <?php if ($add_success) { ?>
        <h1>Your subservice added successfully <a href='provider.php'>Back to your page</a></h1>
    <?php } else { ?>

        <h2>Create subservice</h2>
        <form action="" id="createSubService" method="post">
            <div>
                <label for="sub-serivce-name">Sub service name</label>
                <input type="text" name="sub-service-name" id="sub-service-name">
            </div>

            <div>
                <label for="price">Price(EUR)</label>
                <input type="text" name="price" id="price">
            </div>

            <div>
                <p><label for="sub-description">Description</label></p>
                <textarea name="sub-description" id="sub-description" cols="30" rows="10"></textarea>
            </div>

    
            <?php
                if(isset($subservice)) {
                    $mysqli->refresh(MYSQLI_REFRESH_TABLES);
                
                    $sqlServices = "SELECT DISTINCT s.ServiceID, s.ServiceName 
                                    FROM service s 
                                    INNER JOIN subservice ss ON s.ServiceID = ss.ServiceID
                                    INNER JOIN providerservicelist psl ON ss.SubServiceID = psl.SubServiceID
                                    WHERE psl.ProviderID = ?";
                
                    $stmtServices = $mysqli->prepare($sqlServices);
                
                    if ($stmtServices) {
                        $stmtServices->bind_param("i", $providerID);
                        $stmtServices->execute();
                        $resultServices = $stmtServices->get_result();
                
                        if ($resultServices->num_rows > 0) {
                            echo "<div>";
                            echo "<label for='service'>Select existing service:</label>";
                            echo "<select name='service' id='service'>";
                            while ($row = $resultServices->fetch_assoc()) {
                                echo "<option value='{$row['ServiceID']}'>{$row['ServiceName']}</option>";
                            }
                            echo "</select>";
                            echo "</div>";
                        }
                
                        $stmtServices->close();
                    } else {
                        echo "Failed to prepare statement: " . $mysqli->error;
                    }
                }
                
            ?>

            <button>Create</button>
        </form>
    <?php } ?>
</body>

</html>
