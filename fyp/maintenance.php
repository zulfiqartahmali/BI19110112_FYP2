<?php
require 'include/db/session_check.php'; // Include session and role-based access control
require 'include/db/db_connection.php'; // Include database connection

// Allow only admins to access this page
//if (!checkRole('admin')) {
//    echo "Access denied. You do not have permission to access this page.";
//    exit;
//}

// Handle maintenance schedule updates
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['asset_id'], $_POST['maintenance_date'])) {
    $asset_id = (int)$_POST['asset_id'];
    $maintenance_date = htmlspecialchars($_POST['maintenance_date']);

    // Validate the input
    if (empty($maintenance_date)) {
        $error = "Please provide a valid maintenance date.";
    } else {
        try {
            // Update maintenance schedule in the database using MySQLi
            $stmt = $conn->prepare("UPDATE asset SET MaintenanceSchedule = ? WHERE AssetID = ?");
            $stmt->bind_param('si', $maintenance_date, $asset_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success = "Maintenance schedule updated successfully.";
            } else {
                $error = "No asset found with the given Asset ID or no changes were made.";
            }
        } catch (mysqli_sql_exception $e) {
            $error = "Error updating maintenance schedule: " . $e->getMessage();
        }
    }
}

// Fetch all assets using MySQLi
try {
    $assetsQuery = "SELECT * FROM asset ORDER BY AssetName ASC";
    $assetsResult = $conn->query($assetsQuery);

    if (!$assetsResult) {
        throw new Exception("Error fetching assets: " . $conn->error);
    }

    $assets = $assetsResult->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Error fetching assets: " . $e->getMessage());
}
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Schedule</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Maintenance</a>
        </div>
       <header>
    <!-- Navigation Bar -->
    <?php include 'include/navbar/navbar.php'; ?>
</header
        </div>
    </div>
</header>
    <main>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Asset ID</th>
                        <th>Asset Name</th>
                        <th>Location</th>
                        <th>Status</th>
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
                                <td><?php echo htmlspecialchars($asset['Location']); ?></td>
                                <td><?php echo htmlspecialchars($asset['Status']); ?></td>
                                <td><?php echo htmlspecialchars($asset['MaintenanceSchedule']); ?></td>
                                <td>
                                    <form action="maintenance.php" method="POST">
                                        <input type="hidden" name="asset_id" value="<?php echo $asset['AssetID']; ?>">
                                        <input type="date" name="maintenance_date" required>
                                        <button type="submit" class="update-btn">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No assets found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Barcode Asset Tracker. All rights reserved.</p>
    </footer>

    <style>
        .update-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .update-btn:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html>
