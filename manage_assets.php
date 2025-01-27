<?php
require 'include/db/session_check.php'; // Ensures session is started and role-based access control is applied

// Include database connection
require 'include/db/db_connection.php'; // Include your actual database connection file

// Allow only admins to access this page
// if (!checkRole('admin')) {
//     echo "Access denied. You do not have permission to access this page.";
//     exit;
// }

// Fetch assets from the database using mysqli
$sql = "SELECT AssetID, AssetName, AssetDescription, Barcode, Status, Location, MaintenanceSchedule FROM asset";
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Error fetching assets: " . $conn->error);
}

// Fetch all assets
$assets = $result->fetch_all(MYSQLI_ASSOC);
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assets</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Manage Assets</a>
        </div>
       <header>
    <!-- Navigation Bar -->
    <?php include 'include/navbar/navbar.php'; ?>
</header
        </div>
    </div>
</header>

    <main>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Asset Name</th>
                    <th>Description</th>
                    <th>Barcode</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Maintenance Schedule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assets)): ?>
                    <?php foreach ($assets as $asset): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($asset['AssetID']); ?></td>
                            <td><?php echo htmlspecialchars($asset['AssetName']); ?></td>
                            <td><?php echo htmlspecialchars($asset['AssetDescription']); ?></td>
                            <td><?php echo htmlspecialchars($asset['Barcode']); ?></td>
                            <td><?php echo htmlspecialchars($asset['Status']); ?></td>
                            <td><?php echo htmlspecialchars($asset['Location']); ?></td>
                            <td><?php echo htmlspecialchars($asset['MaintenanceSchedule']); ?></td>
                            <td>
                                <a href="edit_assets.php?id=<?php echo $asset['AssetID']; ?>">Edit</a>
                                <a href="delete_asset.php?id=<?php echo $asset['AssetID']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No assets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Your Website. All rights reserved.</p>
    </footer>
</body>
</html>
