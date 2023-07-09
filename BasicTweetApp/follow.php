<?php
include 'db_connection.php';
// Database connection
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "project348";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// Start the session
session_start();

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["followedUserId"])) {
        $followedUserId = $_POST["followedUserId"];

        // Check if the user is already followed
        $checkQuery = "SELECT * FROM follows WHERE follower_id = $userId AND followed_id = $followedUserId";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo "User is already followed.";
        } else {
            // Insert a new row into the follows table
            $insertQuery = "INSERT INTO follows (follower_id, followed_id) VALUES ($userId, $followedUserId)";
            if ($conn->query($insertQuery) === TRUE) {
                // Update the user table - increment the followers count of the followed user
                $updateFollowedQuery = "UPDATE users SET followers = followers + 1 WHERE user_id = $followedUserId";
                $conn->query($updateFollowedQuery);

                // Update the user table - increment the following count of the follower user
                $updateFollowerQuery = "UPDATE users SET following = following + 1 WHERE user_id = $userId";
                $conn->query($updateFollowerQuery);

                // Redirect to homepage
                header("Location: homepage.php");
                exit;
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Invalid request.";
    }
}

// Close the database connection
$conn->close();
?>
