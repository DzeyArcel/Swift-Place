<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "swiftplace";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'] ?? '';

if (!$user_email) {
    echo json_encode(["error" => "User not logged in."]);
    exit();
}

// Fetch client profile data
$sql = "SELECT * FROM client_profiles WHERE user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "No profile found."]);
}

$stmt->close();
$conn->close();
?>
