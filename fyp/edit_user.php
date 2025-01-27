<?php
// Include database connection
require 'include/db/db_connection.php'; // Assuming you have your database connection here

// Fetch all users from the database
try {
    $stmt = $conn->prepare("SELECT UserID, username FROM users");
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all users
    $users = $result->fetch_all(MYSQLI_ASSOC);

    // If no users found
    if (empty($users)) {
        die("No users found in the database.");
    }
} catch (Exception $e) {
    die("Error fetching users: " . $e->getMessage());
}

// Handle dropdown selection
$userID = isset($_POST['UserID']) ? (int)$_POST['UserID'] : $users[0]['UserID'];

// Fetch selected user details
try {
    $stmt = $conn->prepare("SELECT UserID, username, email, role FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Error: User not found.");
    }

    // Fetch user data
    $user = $result->fetch_assoc();
} catch (Exception $e) {
    die("Error fetching user: " . $e->getMessage());
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Update user information
        try {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE UserID = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $userID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success = "User updated successfully.";
            } else {
                $success = "No changes made.";
            }
        } catch (Exception $e) {
            $error = "Error updating user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
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
        <h1>Edit Users</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <!-- User Selection Dropdown -->
        <form action="edit_users.php" method="POST">
            <label for="UserID">Select User:</label>
            <select id="UserID" name="UserID" onchange="this.form.submit()" required>
                <?php foreach ($users as $item): ?>
                    <option value="<?php echo $item['UserID']; ?>" <?php echo ($item['UserID'] == $userID) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($item['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- User Edit Form -->
        <form action="edit_users.php" method="POST" class="form-style">
            <input type="hidden" name="UserID" value="<?php echo $user['UserID']; ?>">

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="form-input">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="form-input">
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required class="form-input">
                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="staff" <?php echo ($user['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                </select>
            </div>

            <button type="submit" name="update_user" class="btn-submit">Update User</button>
        </form>
    </main>
</body>
</html>
