<?php
ob_start();
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare the statement
    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $first_name; // Optional

            // Redirect to dashboard
            header("Location: client-dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found. Please check your email.'); window.location='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
ob_end_flush();
?>
