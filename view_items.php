<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Update as needed
$password = ""; // Update as needed
$dbname = "asset_management"; // Update as needed

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch assets
$sql_assets = "SELECT 
    AssetID, 
    AssetName, 
    AssetDescription, 
    Location, 
    Barcode, 
    Status, 
    MaintenanceSchedule 
    FROM asset";
$result_assets = $conn->query($sql_assets);

// Fetch bookings with student and asset details
$sql_bookings = "
    SELECT 
        booking.BookingID,
        users.Name AS StudentName,  -- Use users table instead of student
        asset.AssetName,
        booking.BookingDate,
        booking.ReturnDate,
        booking.Status
    FROM booking
    INNER JOIN users ON booking.UserID = users.UserID  -- Use UserID from users table
    INNER JOIN asset ON booking.AssetID = asset.AssetID";
$result_bookings = $conn->query($sql_bookings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management System</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
<header>
    <div id="navbar">
        <div class="logo">
            <a href="#"></a>
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
        <h1>Items List</h1>
       
    </section>
    <!-- Asset Table -->
    <section>
        <h1>Assets</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Barcode</th>
                    <th>Status</th>
                    <th>Maintenance Schedule</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_assets->num_rows > 0) {
                    while ($row = $result_assets->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['AssetID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['AssetName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['AssetDescription']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Barcode']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['MaintenanceSchedule']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No assets found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Booking Table -->
    <section>
        <h1>Bookings</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Student Name</th>
                    <th>Asset Name</th>
                    <th>Booking Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_bookings->num_rows > 0) {
                    while ($row = $result_bookings->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['BookingID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['StudentName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['AssetName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['BookingDate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ReturnDate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
     <button onclick="window.print()">Print Report</button>
</main>
<footer>
    <p>&copy; 2025 Residential College Asset Management System. All rights reserved.</p>
</footer>
</body>
</html>
