<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch existing profile data
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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $bio = $_POST['bio'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Handle profile picture upload
    if (!empty($_FILES["profile_pic"]["name"])) {
        $profile_pic = basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], "../uploads/" . $profile_pic);
    }

    // Check if profile exists
    $stmt = $conn->prepare("SELECT id FROM client_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update profile
        $stmt = $conn->prepare("UPDATE client_profiles SET bio=?, contact=?, address=?, profile_pic=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $bio, $contact, $address, $profile_pic, $user_id);
    } else {
        // Insert new profile
        $stmt = $conn->prepare("INSERT INTO client_profiles (user_id, bio, contact, address, profile_pic) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $bio, $contact, $address, $profile_pic);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: client_profile.php");
    exit();
}

// Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM client_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: client_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client Profile</title>
    <link rel="stylesheet" href="../css/clientedit.css">
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
<div class="container">
    <h2>Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Bio:</label>
        <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>">

        <label>Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">

        <label>Profile Picture:</label>
        <input type="file" name="profile_pic">

        <button type="submit" name="update" class="update-btn">Save Changes</button>
        <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete your profile?');">Delete Profile</button>
    </form>
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
