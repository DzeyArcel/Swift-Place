<?php
session_start();
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: ../freelancer_login.html");
    exit();
}

// Get freelancer name from session
$freelancer_name = isset($_SESSION["freelancer_name"]) ? $_SESSION["freelancer_name"] : "Freelancer";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="../css/freelancedash.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-container">
                <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
            </div>
            <input type="text" placeholder="Search for services...">
            <nav>
                <a href="#">My Jobs</a>
                <a href="../php/freelance_profile.php">Profile</a> <!-- Fixed path -->
                <a href="../php/logout.php">Logout</a>
            </nav>
        </div>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, <?php echo htmlspecialchars($freelancer_name); ?>!</h1>
            <p>Find the best freelance projects and start working today.</p>
            <button>Explore Jobs</button>
        </div>
    </section>

    <!-- Job Listings Section -->
    <section class="job-listings">
        <h2>Available Jobs</h2>
        <div class="job-cards">
            <!-- Job cards will be added here -->
        </div>
    </section>

    <section class="job-listings">
        <h2>Services</h2>
        <div class="job-cards">
            <!-- Services will be added here -->
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
            <p>&copy; 2024 SwiftPlace - Privacy - Terms - Sitemap</p>
        </div>
    </footer>
</body>
</html>
