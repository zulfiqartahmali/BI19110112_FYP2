<?php
// Include the database connection
require 'include/db/db_connection.php';

// Initialize error and success variables
$error = $success = '';

// Handle form submission for reporting an issue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assetID = $_POST['AssetID'];
    $userID = $_POST['UserID'];  // Assuming user is logged in, the user ID would be fetched from session or database
    $issueDescription = $_POST['IssueDescription'];

    // Insert the reported issue into the database
    $query = "INSERT INTO issues (AssetID, UserID, IssueDescription, Status) VALUES (?, ?, ?, 'Reported')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $assetID, $userID, $issueDescription);

    if ($stmt->execute()) {
        $success = "Issue reported successfully.";
    } else {
        $error = "Error reporting the issue: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue</title>
    <link rel="stylesheet" href="include/style/style.css">
</head>

<body>
  <header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Reports</a>
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
            <h1>Report an Issue</h1>
            <p>If you're facing an issue with an asset, please provide the details below.</p>
        </section>

        <div class="form-container">
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php elseif ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="booking-form">
                <label for="AssetID">Asset ID:</label>
                <input type="number" id="AssetID" name="AssetID" required>

                <label for="UserID">Your User ID:</label>
                <input type="number" id="UserID" name="UserID" required> <!-- For simplicity, using UserID directly -->

                <label for="IssueDescription">Issue Description:</label>
                <textarea id="IssueDescription" name="IssueDescription" rows="4" required></textarea>

                <button type="submit" class="submit-button">Report Issue</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Asset Management System. All rights reserved.</p>
    </footer>
</body>

</html>
