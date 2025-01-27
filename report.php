<?php
require 'include/db/session_check.php';
require 'include/db/db_connection.php'; // Ensure this provides a valid MySQLi connection as $conn

// Fetch booking statistics (pending, approved, and rejected)
try {
    $statsQuery = "
        SELECT
            COUNT(CASE WHEN Status = 'Pending' THEN 1 END) AS pendingBookings,
            COUNT(CASE WHEN Status = 'Approved' THEN 1 END) AS approvedBookings,
            COUNT(CASE WHEN Status = 'Rejected' THEN 1 END) AS rejectedBookings
        FROM booking
    ";
    $statsResult = $conn->query($statsQuery);
    if (!$statsResult) {
        throw new Exception("Error fetching booking statistics: " . $conn->error);
    }
    $stats = $statsResult->fetch_assoc();
} catch (Exception $e) {
    die($e->getMessage());
}

// Fetch detailed bookings report
try {
    $sql_bookings = "
        SELECT
            booking.BookingID,
            booking.UserID,
            users.Name AS UserName,
            asset.AssetName,
            booking.BookingDate,
            booking.ReturnDate,
            booking.Status
        FROM booking
        INNER JOIN users ON booking.UserID = users.UserID
        INNER JOIN asset ON booking.AssetID = asset.AssetID
    ";
    $result_bookings = $conn->query($sql_bookings);
    if (!$result_bookings) {
        throw new Exception("Error fetching detailed bookings: " . $conn->error);
    }
    $bookings = $result_bookings->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die($e->getMessage());
}
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports and Analytics</title>
    <link rel="stylesheet" href="include/style/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
        }

        canvas {
            display: block;
            width: 100%;
            height: 400px;
        }
    </style>
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
<section id="main-title">
        <h1>Report</h1>
       
    </section>
    <!-- Overview Section -->
    <section id="report-overview">
        <div id="overview-stats">
            <div class="stat">
                <h2>Total Bookings</h2>
                <p><?php echo array_sum($stats); ?></p>
            </div>
            <div class="stat">
                <h2>Pending</h2>
                <p><?php echo $stats['pendingBookings'] ?? 0; ?></p>
            </div>
            <div class="stat">
                <h2>Approved</h2>
                <p><?php echo $stats['approvedBookings'] ?? 0; ?></p>
            </div>
            <div class="stat">
                <h2>Rejected</h2>
                <p><?php echo $stats['rejectedBookings'] ?? 0; ?></p>
            </div>
        </div>
    </section>

    <!-- Chart Section -->
    <section id="analytics-visualization">
        <h2>Booking Status Distribution</h2>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </section>

    <!-- Detailed Report Section -->
    <section id="detailed-reports">
        <h2>Detailed Bookings Report</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User Name</th>
                    <th>Asset Name</th>
                    <th>Booking Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['BookingID']); ?></td>
                        <td><?php echo htmlspecialchars($row['UserName']); ?></td>
                        <td><?php echo htmlspecialchars($row['AssetName']); ?></td>
                        <td><?php echo htmlspecialchars($row['BookingDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['ReturnDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="window.print()">Print Report</button>
    </section>
</main>

<footer>
    <p>&copy; 2025 Barcode Asset Tracker. All rights reserved.</p>
</footer>

<script>
    const pendingBookings = <?php echo $stats['pendingBookings'] ?? 0; ?>;
    const approvedBookings = <?php echo $stats['approvedBookings'] ?? 0; ?>;
    const rejectedBookings = <?php echo $stats['rejectedBookings'] ?? 0; ?>;

    const ctx = document.getElementById("statusChart").getContext("2d");
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["Pending", "Approved", "Rejected"],
            datasets: [{
                data: [pendingBookings, approvedBookings, rejectedBookings],
                backgroundColor: ["#f39c12", "#2ecc71", "#e74c3c"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" },
                title: { display: true, text: "Booking Status Distribution" }
            }
        }
    });
</script>

</body>
</html>
