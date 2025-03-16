<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (first name, last name, email)
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();

// Fetch client profile details
$bio = $contact = $address = $profile_pic = "";
$stmt = $conn->prepare("SELECT bio, contact, address, profile_pic FROM client_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($bio, $contact, $address, $profile_pic);
    $stmt->fetch();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile</title>
    <link rel="stylesheet" href="../css/clientprofile.css">
</head>
<body>

<header class="topbar">
    <div class="logo-container">
        <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
    </div>
    <nav class="nav-links">
        <ul>
            <li><a href="client-dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="client_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="clientedit_profile.php"><i class="fas fa-edit"></i> Edit Profile</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<section class="profile-content">
    <h1>Client Profile</h1>
    <div class="profile-header">
        <h2><?php echo htmlspecialchars($first_name . " " . $last_name); ?></h2>
       
    </div>
    
    <div class="profile-image">
        <img src="../uploads/<?php echo htmlspecialchars($profile_pic ?: 'default.jpg'); ?>" alt="Profile Picture">
    </div>

    <div class="profile-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($bio)); ?></p>
    </div>
</section>

<footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="#">Pricing</a>
                <a href="#">About Us</a>
                <a href="#">Features</a>
                <a href="#">Help Center</a>
                <a href="#">Contact Us</a>
                <a href="#">FAQ</a>
                <a href="#">Careers</a>
            </div>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
            <p>&copy; 2024 Swift Place - Privacy - Terms - Sitemap</p>
        </div>
    </footer>

</body>
</html>
