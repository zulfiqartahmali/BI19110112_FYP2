<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

require 'include/db/session_check.php';
require 'include/db/db_connection.php';

// Get UserID from session
$UserID = $_SESSION['UserID'];



// Check if user exists in the users table
$checkUserQuery = "SELECT UserID FROM users WHERE UserID = ?";
$checkUserStmt = $conn->prepare($checkUserQuery);
$checkUserStmt->bind_param('i', $UserID);
$checkUserStmt->execute();
$checkUserResult = $checkUserStmt->get_result();

if ($checkUserResult->num_rows === 0) {
    // User does not exist, show error
    $error = "Invalid user. Please log in again.";
} else {
    // Proceed with the booking if user exists
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $AssetID = htmlspecialchars(trim($_POST['AssetID']));
        $booking_date = htmlspecialchars(trim($_POST['booking_date']));
        $return_date = htmlspecialchars(trim($_POST['return_date']));

        // Validate inputs
        if (empty($AssetID) || empty($booking_date) || empty($return_date)) {
            $error = "All fields are required.";
        } else {
            $conn->begin_transaction(); // Begin a transaction
            try {
                // Check asset availability
                $checkQuery = "SELECT Status FROM asset WHERE AssetID = ? FOR UPDATE";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bind_param('i', $AssetID);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                $asset = $checkResult->fetch_assoc();

                if (!$asset || $asset['Status'] !== 'Available') {
                    throw new Exception("Selected asset is unavailable.");
                }

                // Insert into booking table
                $insertQuery = "
                    INSERT INTO booking (AssetID, UserID, BookingDate, ReturnDate, Status) 
                    VALUES (?, ?, ?, ?, 'Pending')
                ";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param('iiss', $AssetID, $UserID, $booking_date, $return_date);
                $insertStmt->execute();

                // Update asset status to 'Booked'
                $updateQuery = "UPDATE asset SET Status = 'Booked' WHERE AssetID = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('i', $AssetID);
                $updateStmt->execute();

                // Commit the transaction
                $conn->commit();

                $success = "Booking submitted successfully. Awaiting admin approval.";
            } catch (Exception $e) {
                // Rollback the transaction on error
                $conn->rollback();
                $error = "Error submitting booking: " . $e->getMessage();
            }
        }
    }
}

// Fetch available assets
try {
    $assetsQuery = "SELECT AssetID, AssetName, Location, Status FROM asset WHERE Status = 'Available'";
    $assetsResult = $conn->query($assetsQuery);
    if (!$assetsResult) {
        throw new Exception("Error fetching assets: " . $conn->error);
    }
    $assets = $assetsResult->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die($e->getMessage());
}
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Asset</title>
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
        </header>
    </div>
</header>

<main>
<section id="main-title">
        <h1>Book an Asset</h1>
       
    </section>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="booking.php" method="POST">
        <label for="AssetID">Select Asset:</label>
        <select id="AssetID" name="AssetID" required>
            <option value="">-- Select an Asset --</option>
            <?php foreach ($assets as $asset): ?>
                <option value="<?php echo htmlspecialchars($asset['AssetID']); ?>">
                    <?php echo htmlspecialchars($asset['AssetName'] . " - " . $asset['Location'] . " (Status: " . $asset['Status'] . ")"); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="booking_date">Booking Date:</label>
        <input type="date" id="booking_date" name="booking_date" required><br><br>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" required><br><br>

        <button type="submit">Submit Booking</button>
    </form>

    <p><a href="view_items.php">Back to View Items</a></p>
</main>

<footer>
    <p>&copy; 2025 Barcode Asset Tracker. All rights reserved.</p>
</footer>
</body>
</html>
