<?php
// Include database connection code here
$mysqli = require __DIR__ ."/../account/account_php/database.php";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the service name entered by the customer
    $search_query = $_POST["service_name"];

    // Prepare and execute SQL query to search for providers offering the specified service
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

    // Store search results in an array
    $search_results = array();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }

    // Encode search results array to JSON and redirect back to customer.php
    header("Location: customer.php?search_result=" . urlencode(json_encode($search_results)));
    exit();
}
?>
