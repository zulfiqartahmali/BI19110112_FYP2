<?php
// Database connection
require 'include/db/db_connection.php';

// Fetch all assets from the database
$query = "SELECT AssetID, AssetName FROM asset";
$result = $conn->query($query);

// Check if there are any assets
if ($result->num_rows === 0) {
    die("No assets available to edit.");
}

// Handle form submission to fetch selected asset details
$asset = null;
if (isset($_POST['AssetID']) && !empty($_POST['AssetID'])) {
    $assetID = $_POST['AssetID'];

    // Fetch selected asset's details
    $query = "SELECT * FROM asset WHERE AssetID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $assetID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if asset exists
    if ($result->num_rows > 0) {
        $asset = $result->fetch_assoc();
    } else {
        die("Asset not found.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>

<body>
   <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Edit Assets</a>
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
            <h1>Edit Asset</h1>
            <p>Select an asset to edit from the list below.</p>
        </section>

        <form action="edit_assets.php" method="POST">
            <!-- Dropdown to select asset -->
            <label for="AssetID">Select Asset to Edit:</label>
            <select id="AssetID" name="AssetID" required>
                <option value="">-- Select Asset --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['AssetID']; ?>" <?php echo isset($asset) && $asset['AssetID'] == $row['AssetID'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['AssetName']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="submit-button">Load Asset Details</button>
        </form>

        <?php if ($asset): ?>
            <form action="update_asset.php" method="POST">
                <input type="hidden" name="AssetID" value="<?php echo $asset['AssetID']; ?>">
                <div>
                <label for="AssetName">Asset Name:</label>
                <input type="text" id="AssetName" name="AssetName" value="<?php echo htmlspecialchars($asset['AssetName']); ?>" required>
                </div>
                <div>
                <label for="AssetDescription">Asset Description:</label>
                <textarea id="AssetDescription" name="AssetDescription" required><?php echo htmlspecialchars($asset['AssetDescription']); ?></textarea>
                </div>
                <div>
                <label for="Location">Location:</label>
                <input type="text" id="Location" name="Location" value="<?php echo htmlspecialchars($asset['Location']); ?>" required>
                </div>
                <div>
                <label for="Status">Status:</label>
                <select id="Status" name="Status" required>
                    <option value="Available" <?php echo ($asset['Status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                    <option value="Booked" <?php echo ($asset['Status'] == 'Booked') ? 'selected' : ''; ?>>Booked</option>
                    <option value="Unavailable" <?php echo ($asset['Status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                </select>
                </div>
                <div>
                <label for="MaintenanceSchedule">Maintenance Schedule:</label>
                <input type="date" id="MaintenanceSchedule" name="MaintenanceSchedule" value="<?php echo ($asset['MaintenanceSchedule']) ? $asset['MaintenanceSchedule'] : ''; ?>">
                </div>
                <button type="submit" class="submit-button">Update Asset</button>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Asset Management System. All rights reserved.</p>
    </footer>
</body>

</html>
