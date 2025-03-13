<?php
session_start();
include 'db.php';

if (!isset($_SESSION['freelancer_id'])) {
    header("Location: freelancer_login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];
$phone = $address = $skills = $experience = $bio = "";
$profile_picture = "html/uploads/no-profile-picture-icon-35.png";

// Fetch freelancer info
$stmt = $conn->prepare("SELECT first_name, last_name, email FROM freelancers WHERE id = ?");
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();

// Fetch profile details
$stmt = $conn->prepare("SELECT phone, address, skills, experience, bio, profile_picture FROM freelancer_profile WHERE freelancer_id = ?");
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($phone, $address, $skills, $experience, $bio, $profile_picture);
    $stmt->fetch();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Profile</title>
    <link rel="stylesheet" href="../css/freelanceprofile.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-pic">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
        </div>
        <h2><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h2>
        <p><?php echo htmlspecialchars($email); ?></p>

        <ul>
        <li><a href="freelancer_dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>
            <li><a href="freelancer_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="freelanceedit_profile.php"><i class="fas fa-edit"></i> Edit Profile</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <h1>Freelancer Profile</h1>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone ?: 'Not set'); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address ?: 'Not set'); ?></p>
        <p><strong>Skills:</strong> <?php echo htmlspecialchars($skills ?: 'Not set'); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($experience ?: 'Not set'); ?></p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($bio ?: 'Not set'); ?></p>

        <a href="freelanceedit_profile.php" class="edit-btn"><i class="fas fa-edit"></i> Edit Profile</a>
    </div>
</div>


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
