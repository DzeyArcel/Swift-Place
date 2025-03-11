<?php
ob_start();  // Start output buffering
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $query = "SELECT * FROM userclient WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];

            // Check if profile exists
            $profile_query = "SELECT * FROM client_profiles WHERE user_email = ?";
            $profile_stmt = $conn->prepare($profile_query);
            $profile_stmt->bind_param("s", $email);
            $profile_stmt->execute();
            $profile_stmt->store_result();

            if ($profile_stmt->num_rows > 0) {
                header("Location: http://localhost/Swift%20Place/html/php/clientdashboard.php"); // Profile exists, go to dashboard
            } else {
                header("Location: http://localhost/Swift%20Place/html/php/clientprofile.php");

 // No profile, go to setup
            }
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }
}
ob_end_flush();  // End output buffering
?>
