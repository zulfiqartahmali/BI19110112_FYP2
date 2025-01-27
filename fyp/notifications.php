<?php
// Include the database connection
include('include/db/db_connection.php');

// Fetch all unread notifications for a user (assuming a logged-in user)
session_start();
$userID = $_SESSION['UserID'];  // Assuming the user ID is stored in session

// Query to fetch unread notifications
$sql = "SELECT * FROM notification WHERE UserID = ? AND Status = 'Unread' ORDER BY Date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

// Fetch notifications
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Display the notifications
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
     <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link rel="stylesheet" href="include/style/style.css">
    <style>
        .notification {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .notification h4 {
            margin: 0;
            font-size: 18px;
        }
        .notification p {
            margin: 5px 0;
        }
        .notification .date {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Your Notifications</h1>
    
    <?php if (empty($notifications)): ?>
        <p>No new notifications.</p>
    <?php else: ?>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification">
                <h4><?php echo htmlspecialchars($notification['Message']); ?></h4>
                <p class="date"><?php echo $notification['Date']; ?></p>
                <p>Status: <?php echo $notification['Status']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
</body>
</html>
