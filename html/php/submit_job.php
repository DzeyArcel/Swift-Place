<?php
session_start();
include 'db.php'; // your database connection

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

    $conn = new mysqli("localhost", "root", "", "swiftplace");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the job
    $stmt = $conn->prepare("INSERT INTO jobs (client_id, job_title, job_description, category, budget, deadline, required_skill, job_type, experience_level) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdssss", $client_id, $title, $description, $category, $budget, $deadline, $skills, $type, $experience);

    if ($stmt->execute()) {
        $job_id = $stmt->insert_id;

        // ✅ Notify all freelancers
        $freelancers = $conn->query("SELECT id FROM freelancers");
        if ($freelancers) {
            while ($row = $freelancers->fetch_assoc()) {
                $freelancer_id = $row['id'];
                $message = "New job posted: $title";
                $link = "job-details.php?id=$job_id"; // Update path if needed

                $notify = $conn->prepare("INSERT INTO notifications (user_id, type, message, link) VALUES (?, 'freelancer', ?, ?)");
                $notify->bind_param("iss", $freelancer_id, $message, $link);
                $notify->execute();
                $notify->close();
            }
        }

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
