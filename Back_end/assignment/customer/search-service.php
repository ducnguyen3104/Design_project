<?php

$mysqli = require __DIR__ ."/../account/account_php/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $search_query = $_POST["service_name"];

   
    $sql = "SELECT provider.ProviderID, provider.EnterpriseName, service.ServiceID, service.ServiceName, subservice.SubServiceID, subservice.SubServiceName, providerservicelist.`ServicePrice(EUR)`
            FROM providerservicelist
            INNER JOIN provider ON providerservicelist.ProviderID = provider.ProviderID
            INNER JOIN subservice ON providerservicelist.SubServiceID = subservice.SubServiceID
            INNER JOIN service ON subservice.ServiceID = service.ServiceID
            WHERE service.ServiceName LIKE ?";
    
    $stmt = $mysqli->prepare($sql);
    $search_query = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

   
    $search_results = array();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }

    
    header("Location: customer.php?search_result=" . urlencode(json_encode($search_results)));
    exit();
}
?>
