<?php
require 'include/db/session_check.php'; // Ensures session is started and role-based access control is applied

// Allow only students to access this page
//if ($_SESSION['role'] !== 'student') {
    //header("Location: unauthorized.php");
    //exit;
//}
?>//

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
    <section id="main-title">
        <h1>Student Dashboard</h1>
       
    </section>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! You have student privileges.</p>

        <div class="button-container">
            <button onclick="location.href='view_items.php'">View Assets</button>
            <button onclick="location.href='booking.php'">Request Booking</button>
            <button onclick="location.href='view_my_bookings.php'">View My Bookings</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        main {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .button-container button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-container button:hover {
            background-color: #218838;
        }

        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px 0;
            margin-top: 40px;
        }
    </style>
</body>
</html>
