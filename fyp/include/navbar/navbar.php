<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav id="navbar">
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="view_items.php">View Items</a>
    <a href="review.php">Review & Feedback</a>
    <a href="booking.php">Booking</a>
    <a href="report.php">Reports</a>
    <a href="logout.php" class="nav-button">Logout</a>
    
    <?php if (isset($_SESSION['username'])): ?>
        <span class="navbar-username">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    <?php endif; ?>
</nav>
