<?php
session_start();
include 'db.php';

// Check if freelancer is logged in
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];

// Check if service ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "Service ID is missing.";
    exit();
}

$service_id = $_GET['id'];

// Delete the service from the database
$sql = "DELETE FROM services WHERE id = ? AND freelancer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $service_id, $freelancer_id);
$stmt->execute();

// Redirect to the service list page after deletion
header("Location: my_services.php");
exit();
?>
