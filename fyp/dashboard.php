<?php
require 'include/db/session_check.php'; // Ensures session is started and role-based access control is applied

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the role is set in the session
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    // Redirect to the appropriate dashboard based on the role
    switch ($role) {
        case 'admin':
            header("Location: admin_dashboard.php");
            exit;
        case 'staff':
            header("Location: staff_dashboard.php");
            exit;
        case 'student':
            header("Location: student_dashboard.php");
            exit;
        default:
            // Log the unexpected role and redirect to login
            error_log("Unknown role encountered: " . $role);
            header("Location: login.php");
            exit;
    }
} else {
    // Log missing role and redirect to login
    error_log("No role found in session.");
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role-Based Redirection</title>
</head>
<body>
    <!-- This page will not render since all cases redirect -->
</body>
</html>
