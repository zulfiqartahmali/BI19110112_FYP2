<?php
// Include database connection
require 'include/db/db_connection.php'; // Assuming you have your database connection here

// Check if UserID is provided in the URL
if (!isset($_GET['UserID']) || empty($_GET['UserID'])) {
    die("<p>Error: UserID is missing or invalid. <a href='manage_users.php'>Go back to User List</a></p>");
}

$userID = (int) $_GET['UserID'];

// Fetch user details for confirmation
try {
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("<p>Error: User not found. <a href='manage_users.php'>Go back to User List</a></p>");
    }

    $user = $result->fetch_assoc();
} catch (Exception $e) {
    die("<p>Error fetching user: " . $e->getMessage() . ". <a href='manage_users.php'>Go back to User List</a></p>");
}

// Handle delete confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<p>User deleted successfully. <a href='manage_users.php'>Return to User List</a></p>";
        } else {
            echo "<p>Error: Could not delete the user. <a href='manage_-users.php'>Return to User List</a></p>";
        }

        // Stop further execution after deletion
        exit;
    } catch (Exception $e) {
        die("<p>Error deleting user: " . $e->getMessage() . ". <a href='manage_users.php'>Return to User List</a></p>");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <main>
        <h1>Delete User</h1>
        <p>Are you sure you want to delete the following user?</p>
        <ul>
            <li><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
        </ul>
        <form action="delete_user.php?UserID=<?php echo $userID; ?>" method="POST">
            <button type="submit" name="confirm_delete" class="btn-submit">Confirm Delete</button>
            <a href="manage_users.php" class="btn-cancel">Cancel</a>
        </form>
    </main>
</body>
</html>
