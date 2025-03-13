<?php
ob_start(); 
include 'db.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $job_category = $_POST['job_category'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $portfolio_link = !empty($_POST['portfolio_link']) ? $_POST['portfolio_link'] : NULL;

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert freelancer into database
    $sql = "INSERT INTO freelancers (first_name, last_name, email, password, job_category, experience, skills, portfolio_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $first_name, $last_name, $email, $password, $job_category, $experience, $skills, $portfolio_link);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../freelancer_login.html"); // Redirect to login page after signup
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
ob_end_flush(); 
?>
