<?php
session_start(); // Oturumu başlat

include 'db_connection.php';

$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "project348";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM USERS WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];

        $stmt->close();
        $conn->close();

        header("Location: homepage.php");
        exit;
    } else {
        $errorMessage = "Geçersiz kullanıcı adı veya şifre. Lütfen tekrar deneyin.";

        $stmt->close();
        $conn->close();
    }
} else {
    $errorMessage = "Kullanıcı adı veya şifre eksik. Lütfen tüm alanları doldurun.";
}
?>
