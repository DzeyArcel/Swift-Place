<?php
session_start();
include 'db.php'; // adjust if needed

if (!isset($_SESSION['freelancer_id'])) {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];
$title = $_POST['service_title'];

// Check if a custom category was entered, else use the dropdown category
$category = !empty($_POST['category']) ? $_POST['category'] : null;

$description = $_POST['description'];
$skills = $_POST['skills'];
$delivery_time = $_POST['delivery_time'];
$tags = $_POST['tags'];
$expertise = $_POST['expertise'];
$price = $_POST['price'];
$rating = 0;

$media_path = null;

// Handle media upload if exists
if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $media_name = basename($_FILES['media']['name']);
    $media_path = $upload_dir . time() . "_" . $media_name;

    move_uploaded_file($_FILES['media']['tmp_name'], $media_path);
}

// Prepare SQL query to insert data into services table
$stmt = $conn->prepare("INSERT INTO services 
    (freelancer_id, service_title, category, expertise, description, skills, delivery_time, tags, media_path, price, rating, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("issssssssdd", $freelancer_id, $title, $category, $expertise, $description, $skills, $delivery_time, $tags, $media_path, $price, $rating);

// Execute and redirect on success
if ($stmt->execute()) {
    header("Location: freelancer_dashboard.php?success=1");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
