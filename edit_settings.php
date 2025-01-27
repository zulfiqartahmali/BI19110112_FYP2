<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Settings</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>
<body>
    <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Edit Settings</a>
        </div>
       <header>
    <!-- Navigation Bar -->
    <?php include 'include/navbar/navbar.php'; ?>
</header
        </div>
    </div>
</header>

    <main>
        <div id="main-title">
            <h1>Edit Settings</h1>
            <p>Update system configurations below</p>
        </div>

        <form class="booking-form" action="process_edit_settings.php" method="POST">
            <label for="setting1">Setting 1:</label>
            <input type="text" id="setting1" name="setting1" value="">

            <label for="setting2">Setting 2:</label>
            <input type="text" id="setting2" name="setting2" value="">

            <button type="submit" class="submit-button">Save Changes</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Asset Management System</p>
    </footer>
</body>
</html>
