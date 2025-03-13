<?php
session_start();
if (!isset($_SESSION['freelancer'])) {
    header("Location: ../freelancer_login.html");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="../css/freelancedash.css">
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
                <a href="#">Profile</a>
                <a href="../php/logout.php">Logout</a>
            </nav>
        </div>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["freelancer"]); ?>!</h1>
            <p>Find the best freelance projects and start working today.</p>
            <button>Explore Jobs</button>
        </div>
    </section>

    <!-- Job Listings Section -->
    <section class="job-listings">
        <h2>Available Jobs</h2>
        <div class="job-cards">
            
        </div>
    </section>

    <section class="job-listings">
        <h2>Services</h2>
        <div class="job-cards">
            
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
