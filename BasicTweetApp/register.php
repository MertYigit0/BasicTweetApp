

<?php


//database connection php file connection
include 'db_connection.php';


$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "project348";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}


if (!empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['password'])) {
// POST isteğiyle gönderilen verileri al
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];




// Veriyi veritabanına ekle
$sql = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
  if ($conn->query($sql) === TRUE) {
    echo "<h2>Registration is successful.</h2>";
  } else {
    echo "<h2>Error on registration: " . $conn->error . "</h2>";
  }


} else {
    echo "<h2>Error: Please fill in all the required fields.</h2>";
}

// Veritabanı bağlantısını kapat

?>
