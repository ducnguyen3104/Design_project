<?php
    if(empty($_POST["uname"])) {
        die("Userame is required");
    }

    if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Valid email is required");
    }

    if (strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters");
    }

    if (!preg_match("/[a-z]/i", $_POST["password"])) {
        die("Password must contain at least one letter");
    }

    if (!preg_match("/[0-9]/i", $_POST["password"])) {
        die("Password must contain at least one number");
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        die("Passwords must match");
    }

    if(!preg_match("/^[0-9]{3,14}$/", $_POST["phone"])) {
        die("Invalid phone number format");
    }

    if(empty($_POST["street_address"])) {
        die("Street address is required");
    }

    if(empty($_POST["city_name"])) {
        die("City name is required");
    }

    if(empty($_POST["region_name"])) {
        die("Region name is required");
    }

    if(empty($_POST["postal_code_id"])) {
        die("Postal code ID is required");
    }

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $mysqli = require __DIR__ ."/database.php";

    //Add to address

    // Check if the city name already exists in the city table
    $cityName = $_POST["city_name"];
    $checkQuery = "SELECT CityID FROM city WHERE CityName = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $cityName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // City already exists, retrieve its ID
        $stmt->bind_result($cityID);
        $stmt->fetch();
    } else {
        // City does not exist, insert it into the database
        $insertQuery = "INSERT INTO city (CityName) VALUES (?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param("s", $cityName);
        if ($stmt->execute()) {
            // Get the ID of the newly inserted city
            $cityID = $stmt->insert_id;
            echo "City added successfully. New City ID: " . $cityID;
        } else {
            echo "Error adding city: " . $stmt->error;
        }
    }

        // Check if the postcode already exists
    $postcode = $_POST["postal_code_id"]; 
    $checkQuery = "SELECT PostalCodeID FROM postalcode WHERE PostCode = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $postcode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Postcode already exists, retrieve its ID
        $stmt->bind_result($postalcodeID);
        $stmt->fetch();
    } else {
        // Postcode does not exist, insert it into the database
        $insertQuery = "INSERT INTO postalcode (PostCode) VALUES (?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param("s", $postcode);
        if ($stmt->execute()) {
            // Get the ID of the newly inserted postcode
            $postalcodeID = $stmt->insert_id;
        } else {
            echo "Error adding postcode: " . $stmt->error;
        }
}

    // Check if the region already exists
    $regionName = $_POST["region_name"]; 
    $checkQuery = "SELECT RegionID FROM region WHERE RegionName = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $regionName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Region already exists, retrieve its ID
        $stmt->bind_result($regionID);
        $stmt->fetch();
    } else {
        // Region does not exist, insert it into the database
        $insertQuery = "INSERT INTO region (RegionName) VALUES (?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param("s", $regionName);
        if ($stmt->execute()) {
            // Get the ID of the newly inserted region
            $regionID = $stmt->insert_id;
        } else {
            echo "Error adding region: " . $stmt->error;
        }
    }

    //Check if address exist
    $checkQuery = "SELECT AddressID FROM address WHERE StreetAddress = ? AND CityID = ? AND RegionID = ? AND PostalCodeID = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param("siii", $_POST["street_address"], $cityID, $regionID, $postalcodeID);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $stmtCheck->bind_result($existingAddressID);
        $stmtCheck->fetch();
        $addressID = $existingAddressID;
    } else {
        $insertQuery = "INSERT INTO address (StreetAddress, CityID, RegionID, PostalCodeID) VALUES (?, ?, ?, ?)";
        $stmtInsert = $mysqli->prepare($insertQuery);
        $stmtInsert->bind_param("siii", $_POST["street_address"], $cityID, $regionID, $postalcodeID);
        if ($stmtInsert->execute()) {
            // Get the ID of the newly inserted address
            $addressID = $stmtInsert->insert_id;
        } else {
            echo "Error adding address: " . $stmtInsert->error;
        }
    }
    
    //add to role
    // Check if the role already exists
    $roleName = $_POST["role"]; // Assuming the role name is submitted via POST
    $checkQuery = "SELECT RoleID FROM role WHERE RoleName = ?";
    $stmt = $mysqli->prepare($checkQuery);
    $stmt->bind_param("s", $roleName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Role already exists, retrieve its ID
        $stmt->bind_result($roleID);
        $stmt->fetch();
    } else {
        // Role does not exist, insert it into the database
        $insertQuery = "INSERT INTO role (RoleName) VALUES (?)";
        $stmt = $mysqli->prepare($insertQuery);
        $stmt->bind_param("s", $roleName);
        if ($stmt->execute()) {
            // Get the ID of the newly inserted role
            $roleID = $mysqli->insert_id;
        } else {
            echo "Error adding role: " . $stmt->error;
        }
}

    //add to user
    $sql = "INSERT INTO user (Username, Password, Phone, Email, AddressID, RoleID) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssii", 
                                $_POST["uname"],
                                $password_hash,
                                $_POST["phone"],
                                $_POST["email"],
                                $addressID,
                                $roleID);
    
    if($stmt->execute()){
        session_start();
        $userID = $stmt->insert_id;
        $_SESSION["user_id"] = $userID;
        if($_POST["role"] == "customer") {
            header("Location: /assignment/account/customer-signup.html");
        } else {
            header("Location: /assignment/account/provider-signup.html");
        }
    } else {
        if ($mysqli->errno === 1062) {
            die("email or username already taken");
        }   else {
                die($mysqli->error . " " . $mysqli->errno);
        }
    }

?>