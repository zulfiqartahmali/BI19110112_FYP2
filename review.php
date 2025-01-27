<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review & Feedback</title>
    <link rel="stylesheet" href="include/style/style.css">
    <style>
        /* General styles */
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #a49d9d;
    color: #333;
}

        header, footer {
            text-align: center;
            padding: 10px 0;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        p {
            text-align: center;
            color: #555;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input, form textarea, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form button {
            background-color: #0073e6;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #005bb5;
        }

        .feedback-list {
            list-style-type: none;
            padding: 0;
        }

        .feedback-list li {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .feedback-list h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }

        .feedback-list p {
            margin: 5px 0;
            font-size: 1rem;
            color: #555;
        }

        .feedback-list small {
            color: #999;
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
        <h1>Review & Feedback</h1>
       
    </section>
<!-- Feedback Form -->
    <form action="submit_feedback.php" method="POST">
        <label for="review">Review:</label>
        <textarea id="review" name="review_text" rows="4" maxlength="2000" required></textarea>

        <label for="rating">Rating (1-5):</label>
        <select id="rating" name="rating" required>
            <option value="1">1 - Poor</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
        </select>

        <button type="submit">Submit Feedback</button>
    </form>

    <h2>Recent Feedback</h2>
    <ul class="feedback-list">
        <?php
        require 'include/db/db_connection.php';

        // Fetch reviews
        $query = "
            SELECT u.Name AS name, r.ReviewText AS review_text, r.Rating AS rating, r.CreatedAt AS created_at 
            FROM reviews r
            JOIN users u ON r.UserID = u.UserID
            ORDER BY r.CreatedAt DESC";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['review_text']) . "</p>";
                echo "<p>Rating: " . htmlspecialchars($row['rating']) . " / 5</p>";
                echo "<p><small>Submitted on: " . htmlspecialchars($row['created_at']) . "</small></p>";
                echo "</li>";
            }
        } else {
            echo "<p>No feedback available. Be the first to submit a review!</p>";
        }

        $conn->close();
        ?>
    </ul>
</main>

<footer>
    <p>&copy; 2025 Barcode Asset Tracker. All rights reserved.</p>
</footer>

</body>
</html>
