<?php
require 'include/db/session_check.php'; // Ensures session is started and role-based access control is applied

// Allow only admins to access this page
//if (!checkRole('admin')) {
   // echo "Access denied. You do not have permission to access this page.";
   // exit;
//}

// Include database connection
require 'include/db/db_connection.php'; // Replace with your actual database connection file

// Example: Fetch users from the database
try {
    // Prepare the SQL statement
    $query = "SELECT UserID, username, email, role FROM users"; // Adjusted to use UserID

    // Execute the query
    $result = $conn->query($query);

    // Check for query execution errors
    if (!$result) {
        throw new Exception("Error fetching users: " . $conn->error);
    }

    // Fetch all results as an associative array
    $users = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die($e->getMessage());
}
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
   <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Manage Users</a>
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
                    <th>UserID</th> <!-- Adjusted column header -->
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['UserID']); ?></td> <!-- Adjusted to use UserID -->
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['UserID']; ?>">Edit</a> <!-- Adjusted to use UserID -->
                            <a href="delete_user.php?id=<?php echo $user['UserID']; ?>" onclick="return confirm('Are you sure?')">Delete</a> <!-- Adjusted to use UserID -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>
</body>
</html>
