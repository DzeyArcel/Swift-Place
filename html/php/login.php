<?php
ob_start();
include 'db.php';


session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $first_name;
            header("Location: client-dashboard.php");
            exit();
            

        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
    $conn->close();
}
ob_end_flush();

?>
