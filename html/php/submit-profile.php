<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION['user_email'];
    $company_name = $_POST['company_name'];
    $industry = $_POST['industry'];
    $job_type = $_POST['job_type'];
    $payment_type = $_POST['payment_type'];
    $experience_level = $_POST['experience_level'];
    $category = $_POST['category'];
    $preferred_communication = $_POST['preferred_communication'];
    $budget = $_POST['budget'];
    $payment_method = $_POST['payment_method'];

    // Handle file upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $profile_picture = $_FILES["profile_picture"]["name"];
    $profile_picture_tmp = $_FILES["profile_picture"]["tmp_name"];
    $profile_picture_path = $target_dir . basename($profile_picture);

    if (!move_uploaded_file($profile_picture_tmp, $profile_picture_path)) {
        die("File upload failed. Check folder permissions.");
    }

    // Insert profile data
    $sql = "INSERT INTO client_profiles (user_email, company_name, industry, job_type, payment_type, experience_level, category, preferred_communication, budget, payment_method, profile_picture) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $user_email, $company_name, $industry, $job_type, $payment_type, $experience_level, $category, $preferred_communication, $budget, $payment_method, $profile_picture_path);

    if ($stmt->execute()) {
        header("Location: http://localhost/php/clientdashboard.php");
exit();
 // Redirect to dashboard after setup
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
}
?>