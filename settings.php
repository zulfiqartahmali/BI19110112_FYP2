<?php
require 'include/db/session_check.php';

// Allow only admins to access this page
//if (!checkRole('admin')) {
   // echo "Access denied. You do not have permission to access this page.";
    //exit;
//}

// Example: Settings options (you can customize these)
$settings = [
    "Site Title" => "My Website",
    "Email Notifications" => "Enabled",
    "User Registration" => "Open",
    "System Status" => "Online",
];
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Settings</a>
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
                    <th>Setting</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings as $key => $value): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($key); ?></td>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><a href="edit_settings.php">Edit Settings</a></p>
    </main>

    <footer>
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>
</body>
</html>
