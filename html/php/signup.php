<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $country = $_POST['country'];

    // Insert into userclient table
    $sql = "INSERT INTO userclient (first_name, last_name, email, password, country) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $country);

    if ($stmt->execute()) {
        header("Location: ../login.html"); // Redirect to login
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
}
?>