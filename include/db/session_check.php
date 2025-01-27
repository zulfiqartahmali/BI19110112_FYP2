<<?php
// Start the session if it hasn't already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if the current user has the required role
function checkRole($requiredRole) {
    // Verify that the session variable 'role' is set and matches the required role
    return isset($_SESSION['role']) && $_SESSION['role'] === $requiredRole;
}

// Function to check if the user is logged in
function isLoggedIn() {
    // Check if the 'user_id' session variable is set (indicating the user is logged in)
    return isset($_SESSION['user_id']);
}

// Function to set user role during login
// This would normally be part of your login logic
function setUserRole($userId, $role) {
    // Assign user ID and role to the session
    $_SESSION['user_id'] = $userId; // Store user ID in session
    $_SESSION['role'] = $role;     // Store role in session
}

// Function to redirect users to the appropriate dashboard based on their role
function redirectToDashboard() {
    if (!isLoggedIn()) {
        // Redirect to login page if the user is not logged in
        header("Location: login.php");
        exit;
    }

    // Redirect to the correct dashboard based on the user's role
    switch ($_SESSION['role']) {
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
            // Unknown role, clear session and redirect to login
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
    }
}

// Function to log out the user
function logout() {
    session_unset();   // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to the login page
    exit;
}
?>
