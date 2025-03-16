<?php
session_start();
include 'db.php';

if (!isset($_SESSION['freelancer_id'])) {
    header("Location: freelancer_login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];
$phone = $address = $skills = $experience = $bio = "";

// Fetch current profile details
$stmt = $conn->prepare("SELECT phone, address, skills, experience, bio FROM freelancer_profile WHERE freelancer_id = ?");
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($phone, $address, $skills, $experience, $bio);
    $stmt->fetch();
}
$stmt->close();

// Handle Create or Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    $bio = $_POST['bio'];

    if (!empty($phone) && !empty($address)) {
        $checkStmt = $conn->prepare("SELECT freelancer_id FROM freelancer_profile WHERE freelancer_id = ?");
        $checkStmt->bind_param("i", $freelancer_id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            // Update existing profile
            $stmt = $conn->prepare("UPDATE freelancer_profile SET phone = ?, address = ?, skills = ?, experience = ?, bio = ? WHERE freelancer_id = ?");
            $stmt->bind_param("sssssi", $phone, $address, $skills, $experience, $bio, $freelancer_id);
        } else {
            // Insert new profile
            $stmt = $conn->prepare("INSERT INTO freelancer_profile (freelancer_id, phone, address, skills, experience, bio) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $freelancer_id, $phone, $address, $skills, $experience, $bio);
        }

        $stmt->execute();
        $stmt->close();
        header("Location: freelance_profile.php");
        exit();
    }
}

// Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM freelancer_profile WHERE freelancer_id = ?");
    $stmt->bind_param("i", $freelancer_id);
    $stmt->execute();
    $stmt->close();
    header("Location: freelancer_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/freelanceedit.css">
</head>
<body>

<header class="topbar">
    <div class="logo-container">
        <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
    </div>
    <nav class="nav-links">
        <ul>
            <li><a href="freelancer_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="freelance_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="freelanceedit_profile.php"><i class="fas fa-edit"></i> Edit Profile</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<section class="Edit">
    <!-- Profile Edit Section -->
    <div class="profile-content">
        <h1>Edit Profile</h1>

        <form method="POST">
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>

            <label>Skills:</label>
            <input type="text" name="skills" value="<?php echo htmlspecialchars($skills); ?>" required>

            <label>Experience:</label>
            <textarea name="experience" required><?php echo htmlspecialchars($experience); ?></textarea>

            <label>Bio:</label>
            <textarea name="bio" required><?php echo htmlspecialchars($bio); ?></textarea>

            <button type="submit" name="save">Save Profile</button>

            <?php if (!empty($phone) && !empty($address)) : ?>
                <button type="submit" name="delete" class="delete-btn">Delete Profile</button>
            <?php endif; ?>
        </form>
    </div>
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
