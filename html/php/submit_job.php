<?php
session_start();
include 'db.php'; // include your database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_SESSION['user_id'];
    $title = $_POST['job_title'];
    $description = $_POST['job_description'];
    $category = $_POST['category'];
    $budget = $_POST['budget'];
    $deadline = $_POST['deadline'];
    $skills = $_POST['required_skill'];
    $type = $_POST['job_type'];
    $experience = $_POST['experience_level'];

    // Use prepared statement to prevent SQL injection
    $conn = new mysqli("localhost", "root", "", "swiftplace");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO jobs (client_id, job_title, job_description, category, budget, deadline, required_skill, job_type, experience_level) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssdssss", $client_id, $title, $description, $category, $budget, $deadline, $skills, $type, $experience);

    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href = 'client-dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: post_job.php");
    exit();
}
?>
