<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$job_id = $_GET['id'];
$client_id = $_SESSION['user_id'];

$sql = "DELETE FROM jobs WHERE id = ? AND client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $client_id);

if ($stmt->execute()) {
    header("Location: my_posted_jobs.php");
    exit();
} else {
    echo "Failed to delete job.";
}
?>
