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

// Retrieve and display user information
$userQuery = "SELECT * FROM users WHERE user_id = $userId";
$userResult = $conn->query($userQuery);

if ($userResult && $userResult->num_rows > 0) {
    $userRow = $userResult->fetch_assoc();
    $username = $userRow['username'];
   // $followers = $userRow['followers'];
    //$following = $userRow['following'];
    
    $followingQuery = "SELECT COUNT(*) AS following FROM follows WHERE follower_id = $userId";
    $followingResult = $conn->query($followingQuery);

    if ($followingResult && $followingResult->num_rows > 0) {
        $followingRow = $followingResult->fetch_assoc();
        $following = $followingRow['following'];
    } else {
        $following = 0;
    }

// Retrieve the number of followers
$followersQuery = "SELECT COUNT(*) AS followers FROM follows WHERE followed_id = $userId";
$followersResult = $conn->query($followersQuery);

if ($followersResult && $followersResult->num_rows > 0) {
    $followersRow = $followersResult->fetch_assoc();
    $followers = $followersRow['followers'];
} else {
    $followers = 0;
}


    echo "Welcome, $username!<br>";
    echo "Followers: $followers<br>";
    echo "Following: $following<br>";
} else {
    echo "User not found.";
}

// Display user's tweets
$tweetsQuery = "CALL sp_GetProfileTweets($userId)";
$tweetsResult = $conn->query($tweetsQuery);

if ($tweetsResult && $tweetsResult->num_rows > 0) {
    echo "<h3>Your Tweets</h3>";
    while ($row = $tweetsResult->fetch_assoc()) {
       // $tweetId = $row['tweet_id'];
        $content = $row['content'];
        $creationDate = $row['created_at'];

       // echo "Tweet ID: $tweetId<br>";
        echo "Content: $content<br>";
        echo "Creation Date: $creationDate<br>";
        echo "<br>";
    }
} else {
    echo "No tweets available.";
}

// Close the database connection
$conn->close();
?>

<!-- Tweet Form -->
<form action="tweet.php" method="POST">
    <textarea name="tweetContent" placeholder="Write your tweet"></textarea>
    <button type="submit" name="tweetBtn">Tweet</button>
</form>
<!-- Homepage Button -->
<a href="homepage.php">Homepage</a>

<!-- Logout Button -->
<form action="logout.php" method="POST">
    <button type="submit" name="logoutBtn">Logout</button>
</form>