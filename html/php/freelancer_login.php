<?php
ob_start();
session_start([
    'cookie_lifetime' => 86400, // 1-day session
    'read_and_close' => false,
]);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

// Enable strict error reporting for MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT id, first_name, password FROM freelancers WHERE email = ?");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $hashed_password);
        $stmt->fetch();

        // Debug: Check if password verification works
        if (!password_verify($password, $hashed_password)) {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='login.php';</script>";
            exit();
        }

        // ✅ Store freelancer ID in session
        $_SESSION['freelancer_id'] = $id;
        $_SESSION['freelancer_name'] = $first_name;

        // ✅ Debugging - Ensure session is really set
        if (!isset($_SESSION['freelancer_id'])) {
            die("Session error: Freelancer ID not set. Check session storage.");
        }

        // ✅ Redirect to dashboard
        header("Location: freelancer_dashboard.php");
        exit();
    } else {
        echo "<script>alert('User not found. Please check your email.'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt->close();
}

$conn->close();
ob_end_flush();
?>
