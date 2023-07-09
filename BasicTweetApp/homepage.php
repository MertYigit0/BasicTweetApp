<?php
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

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Retrieve user information
$userQuery = "SELECT * FROM users WHERE user_id = $userId";
$userResult = $conn->query($userQuery);

if ($userResult && $userResult->num_rows > 0) {
    $userRow = $userResult->fetch_assoc();
    $username = $userRow['username'];
} else {
    echo "User not found.";
    exit;
}

// Query to retrieve tweets of the followed users
$tweetsQuery = "SELECT t.*, u.username FROM tweets AS t
                JOIN follows AS f ON t.user_id = f.followed_id
                JOIN users AS u ON t.user_id = u.user_id
                WHERE f.follower_id = $userId";
$tweetsResult = $conn->query($tweetsQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?></h1>

    <h2>Followed Users' Tweets</h2>
    <?php
    if ($tweetsResult && $tweetsResult->num_rows > 0) {
        while ($row = $tweetsResult->fetch_assoc()) {
            $tweetContent = $row['content'];
            $tweetCreationDate = $row['created_at'];
            $tweetUsername = $row['username'];

            echo "<p>@$tweetUsername</p>";
            echo "<p>Tweet Content: $tweetContent</p>";
            echo "<p>Creation Date: $tweetCreationDate</p>";
            echo "<br>";
        }
    } else {
        echo "No tweets available from the followed users.";
    }
    ?>

    <h2>Search for a User</h2>
    <form action="homepage.php" method="GET">
        <input type="text" name="searchUser" placeholder="Search for a user">
        <button type="submit" name="searchBtn">Search</button>
    </form>

    <?php
    // Check if a search query is submitted
    if (isset($_GET['searchBtn'])) {
        $searchUser = $_GET['searchUser'];

        // Perform search query
        $searchQuery = "SELECT * FROM users WHERE username LIKE '%$searchUser%'";
        $searchResult = $conn->query($searchQuery);

        if ($searchResult && $searchResult->num_rows > 0) {
            while ($row = $searchResult->fetch_assoc()) {
                $searchedUserId = $row['user_id'];
                $searchedUsername = $row['username'];

                // Check if the searched user is already followed
                $followQuery = "SELECT * FROM follows WHERE follower_id = $userId AND followed_id = $searchedUserId";
                $followResult = $conn->query($followQuery);

                if ($followResult && $followResult->num_rows > 0) {
                    echo "<p>$searchedUsername is already followed.</p>";
                } else {
                    echo "<p><strong>$searchedUsername</strong> - $searchedUserId</p>";
                    echo "<form action='follow.php' method='POST'>";
                    echo "<input type='hidden' name='followedUserId' value='$searchedUserId'>";
                    echo "<button type='submit' name='followBtn'>Follow</button>";
                    echo "</form>";
                }
            }
        } else {
            echo "<p>User not found.</p>";
        }
    }
    ?>

    <a href="profile.php">Profile</a>
    <form action="logout.php" method="POST">
        <button type="submit" name="logoutBtn">Logout</button>
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
