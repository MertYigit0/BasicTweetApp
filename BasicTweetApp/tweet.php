<?php
// Database connection
include 'db_connection.php';
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

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Check if tweet content is provided

if (isset($_POST['tweetContent']) ) {
    $tweetContent = $_POST['tweetContent'];
    $tweetDate = date('Y-m-d H:i:s'); // Geçerli tarihi/datetime'i alır

    // Insert the tweet into the tweet table
    $insertQuery = "INSERT INTO tweets (user_id, content, created_at) VALUES ('$userId', '$tweetContent', '$tweetDate')";
    $insertResult = $conn->query($insertQuery);

    if ($insertResult) {
        echo "Tweet posted successfully!";
    } else {
        echo "Error posting the tweet.". $conn->error;;
    }
} else {
    echo "Tweet content or date is missing.";
}

// Close the database connection
$conn->close();
?>

