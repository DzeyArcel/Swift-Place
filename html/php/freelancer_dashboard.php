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
                <a href="#">Earnings</a>
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

    <section class="earnings">
        <h2>Your Earnings</h2>
        <p>Total Earnings: <strong>$1,200</strong></p>
        <button>Withdraw Funds</button>
    </section>

    <footer>
        <p>&copy; 2024 SwiftPlace - Freelancer Platform</p>
    </footer>
</body>
</html>
