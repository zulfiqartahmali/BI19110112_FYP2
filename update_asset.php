<?php
// Database connection
require 'include/db/db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $assetID = $_POST['AssetID'];
    $assetName = $_POST['AssetName'];
    $assetDescription = $_POST['AssetDescription'];
    $location = $_POST['Location'];
    $status = $_POST['Status'];
    $maintenanceSchedule = $_POST['MaintenanceSchedule'];

    // Validate required fields
    if (empty($assetID) || empty($assetName) || empty($assetDescription) || empty($location) || empty($status)) {
        die("Error: Missing required fields.");
    }

    // Prepare the SQL statement to update the asset details
    $query = "UPDATE asset SET AssetName = ?, AssetDescription = ?, Location = ?, Status = ?, MaintenanceSchedule = ? WHERE AssetID = ?";
    
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind the parameters to the query
    $stmt->bind_param('sssssi', $assetName, $assetDescription, $location, $status, $maintenanceSchedule, $assetID);

    // Execute the query
    if ($stmt->execute()) {
        echo "Asset updated successfully.";
        // Redirect to assets list or another page
        header("Location: view_items.php"); // You can change this to the appropriate page
        exit;
    } else {
        die("Error updating asset: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Asset</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Update Assets</a>
        </div>
       <header>
    <!-- Navigation Bar -->
    <?php include 'include/navbar/navbar.php'; ?>
</header
        </div>
    </div>
</header>

    <main>
        <section id="main-title">
            <h1>Update Asset</h1>
            <p>Asset details updated successfully.</p>
        </section>

        <footer>
            <p>&copy; 2025 Asset Management System. All rights reserved.</p>
        </footer>
    </main>
</body>
</html>
