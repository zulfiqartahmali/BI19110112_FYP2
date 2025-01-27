<?php
require 'include/db/session_check.php'; // Include session handling functions
require 'include/db/db_connection.php'; // Include database connection

// Redirect to login if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get the logged-in user's role
$role = $_SESSION['role'];

// Allow access only for staff or student roles
if ($role !== 'staff' && $role !== 'student') {
    // Redirect admins or other roles to their respective dashboard
    header("Location: admin_dashboard.php"); 
    exit;
}

// Get the logged-in user's ID
$userID = $_SESSION['UserID'];

try {
    // Fetch bookings for the logged-in user
    $query = "SELECT b.BookingID, a.AssetName, b.BookingDate, b.ReturnDate, b.Status 
              FROM booking b 
              INNER JOIN asset a ON b.AssetID = a.AssetID 
              WHERE b.UserID = :UserID 
              ORDER BY b.BookingDate DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':UserID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="include/style/style.css"> <!-- Add your CSS file here -->
</head>
<body>
    <header>
        <?php include 'include/navbar/navbar.php'; ?> <!-- Include the navbar -->
        <h1>My Bookings</h1>
    </header>

    <main>
        <?php if (!empty($bookings)): ?>
            <div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Asset Name</th>
                            <th>Booking Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['BookingID']); ?></td>
                                <td><?php echo htmlspecialchars($booking['AssetName']); ?></td>
                                <td><?php echo htmlspecialchars($booking['BookingDate']); ?></td>
                                <td><?php echo htmlspecialchars($booking['ReturnDate']); ?></td>
                                <td><?php echo htmlspecialchars($booking['Status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Your Website. All rights reserved.</p>
    </footer>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        h1 {
            margin: 20px 0;
        }

        main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        .styled-table th, .styled-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .styled-table th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }

        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px 0;
            margin-top: 20px;
        }
    </style>
</body>
</html>
