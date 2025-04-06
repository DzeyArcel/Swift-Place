<?php
ob_start();
session_start();
include 'db.php';

// Limit login attempts
$max_attempts = 5;
$lockout_time = 15 * 60; // 15 minutes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location='login.php';</script>";
        exit();
    }

    // Check if user is locked out
    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $max_attempts) {
        $remaining_time = $_SESSION['lockout_time'] - time();
        if ($remaining_time > 0) {
            echo "<script>alert('Too many failed login attempts. Try again in " . ceil($remaining_time / 60) . " minutes.'); window.location='login.php';</script>";
            exit();
        } else {
            // Reset lockout after timeout
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);
        }
    }

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
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $first_name;

            // Set session security flags
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.use_only_cookies', 1);

            // Reset login attempts on successful login
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);

            // Redirect to dashboard
            header("Location: client-dashboard.php");
            exit();
        } else {
            // Track login attempts
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time() + $lockout_time;
            }
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
