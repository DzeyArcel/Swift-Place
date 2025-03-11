<?php
$host = "localhost";  // Change if your database is hosted elsewhere
$username = "root";   // Default XAMPP username
$password = "";       // Default XAMPP password (leave blank)
$database = "swiftplace";  // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>