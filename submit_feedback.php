<?php
// Include the database connection file
require 'include/db/db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $reviewText = trim($conn->real_escape_string($_POST['review_text']));
    $rating = intval($_POST['rating']); // Ensure the rating is an integer

    // Replace with the actual user ID of the logged-in user
    $userId = 1; // Placeholder: Update this with the session user ID or authentication system

    // Validate input
    if (empty($reviewText) || $rating < 1 || $rating > 5) {
        echo "Invalid input. Please provide a valid review and rating.";
        exit;
    }

    // Insert the feedback into the `reviews` table
    $sql = "
        INSERT INTO reviews (UserID, ReviewText, Rating, CreatedAt) 
        VALUES ('$userId', '$reviewText', '$rating', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the feedback page with success message
        header("Location: feedback.php?success=1");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
