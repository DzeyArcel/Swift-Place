<?php
// db.php - Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "swiftplace";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>