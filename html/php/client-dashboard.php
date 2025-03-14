<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.html"); // Redirect if not logged in
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="../css/clientdash.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-container">
                <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
            </div>
            <input type="text" placeholder="Search for services...">
            <nav>
                <a href="#">Orders</a>
                <a href="#">Messages</a>
                <a href="#">Profile</a>
                <a href="logout.php">Logout</a>
               
            </nav>
        </div>
    </header>
    
    <section class="hero">
        <div class="hero-content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?>!</h1>
     
            <h1>Meet Top Freelancers</h1>
            <p>Hire professionals to get your work done efficiently.</p>
            <button>Start Hiring</button>
        </div>
    </section>
    
    <section class="recommendations">
        <h2>Recommended For You</h2>
        <div class="service-cards">
            <div class="card">Service 1</div>
            <div class="card">Service 2</div>
            <div class="card">Service 3</div>
        </div>
    </section>
    
    <section class="browsing-history">
        <h2>Based on Your Browsing</h2>
        <div class="service-cards">
            <div class="card">Freelancer 1</div>
            <div class="card">Freelancer 2</div>
            <div class="card">Freelancer 3</div>
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
