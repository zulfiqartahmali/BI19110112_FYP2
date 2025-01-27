<?php
require 'include/db/session_check.php'; // Include session and role-based access control
require 'include/db/db_connection.php'; // Include database connection

// Allow only admins to access this page
//if (!checkRole('admin')) {
//    echo "Access denied. You do not have permission to access this page.";
//    exit;
//}

// Handle booking status updates
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = (int)$_POST['booking_id'];
    $action = htmlspecialchars($_POST['action']);

    // Validate the action
    if (!in_array($action, ['Approved', 'Rejected'])) {
        echo "Invalid action.";
        exit;
    }

    // Update booking status
    $stmt = $conn->prepare("UPDATE booking SET Status = ? WHERE BookingID = ?");
    $stmt->bind_param("si", $action, $booking_id); // "si" means string, integer
    if ($stmt->execute()) {
        $success = "Booking status updated successfully.";
    } else {
        $error = "Error updating booking status: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all bookings
$query = "SELECT b.BookingID, b.AssetID, b.UserID, b.BookingDate, b.ReturnDate, b.Status, u.username 
          FROM booking b 
          JOIN users u ON b.UserID = u.UserID 
          ORDER BY b.BookingDate DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching bookings: " . $conn->error);
}

$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Manage Bookings</a>
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
                        <th>Booking ID</th>
                        <th>Asset ID</th>
                        <th>User Name</th>
                        <th>Booking Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['BookingID']); ?></td>
                                <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                <td><?php echo htmlspecialchars($booking['AssetID']); ?></td>
                                <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                <td><?php echo htmlspecialchars($booking['BookingDate']); ?></td>
                                <td><?php echo htmlspecialchars($booking['ReturnDate']); ?></td>
                                <td><?php echo htmlspecialchars($booking['Status']); ?></td>
                                <td>
                                    <?php if ($booking['Status'] === 'Pending'): ?>
                                        <form action="manage_bookings.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['BookingID']; ?>">
                                            <button type="submit" name="action" value="Approved" class="confirm-btn">Approve</button>
                                        </form>
                                        <form action="manage_bookings.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['BookingID']; ?>">
                                            <button type="submit" name="action" value="Rejected" class="cancel-btn">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <?php echo ucfirst($booking['Status']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <button onclick="window.print()">Print Report</button>
    </main>

    <footer>
        <p>&copy; 2024 Barcode Asset Tracker. All rights reserved.</p>
    </footer>

    <style>
        .confirm-btn {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .confirm-btn:hover {
            background-color: #3a8d42;
        }
        .cancel-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .cancel-btn:hover {
            background-color: #d32f2f;
        }
    </style>
     
</body>
</html>
