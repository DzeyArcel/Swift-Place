<?php
include 'db.php';

$email = $_GET['email'];

$sql = "SELECT profile_picture FROM client_profiles WHERE user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['profile_picture'];
} else {
    echo "../html/photos/default_profile.png"; // Default image if no profile picture
}
?>
