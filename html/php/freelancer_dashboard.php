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
            <button><a href="post_service.php">Post Services</a></button>
        </div>
    </section>

    <!-- Job Listings Section -->
    <section class="job-listings">
    <h2>Available Jobs</h2>
    <div class="job-cards">
        <?php
        $conn = new mysqli("localhost", "root", "", "swiftplace");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC");

        while ($row = $result->fetch_assoc()) {
            echo "<div class='job-post'>";
            echo "<h3>" . htmlspecialchars($row['job_title']) . "</h3>";
            echo "<p class='desc'>" . htmlspecialchars($row['job_description']) . "</p>";
            echo "<p><strong>Skills:</strong> " . htmlspecialchars($row['required_skill']) . "</p>";
            echo "<p><strong>Budget:</strong> $" . htmlspecialchars($row['budget']) . "</p>";
            echo "<p><strong>Deadline:</strong> " . htmlspecialchars($row['deadline']) . "</p>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($row['job_type']) . "</p>";
            echo "<p><strong>Experience:</strong> " . htmlspecialchars($row['experience_level']) . "</p>";
            echo "<form action='apply.php' method='post'>
                    <input type='hidden' name='job_id' value='" . $row['id'] . "'>
                    <button class='apply-btn' type='submit'>Apply</button>
                  </form>";
            echo "</div>";
        }

        $conn->close();
        ?>
    </div>
</section>

        </div>
    </section>

    <section class="service-listings">
    <h2>Explore Freelance Services</h2>
    <div class="service-cards">
        <?php
        include 'db.php';
        $query = "SELECT s.*, CONCAT(f.first_name, ' ', f.last_name) AS freelancer_name 
                  FROM services s 
                  JOIN freelancers f ON s.freelancer_id = f.id 
                  ORDER BY s.created_at DESC";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='service-card'>";
                if (!empty($row['media_path'])) {
                    echo "<img src='" . $row['media_path'] . "' alt='Service Image' class='service-img'>";
                } else {
                    echo "<div class='placeholder-img'>No Image</div>";
                }

                echo "<div class='service-info'>";
                echo "<h4>" . htmlspecialchars($row['freelancer_name']) . "</h4>";
                echo "<p class='title'>" . htmlspecialchars($row['service_title']) . "</p>";
                echo "<p class='desc'>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p class='category'>Category: " . htmlspecialchars($row['category']) . "</p>";
                echo "<p class='expertise'>Expertise: " . htmlspecialchars($row['skills']) . "</p>";
                echo "<p class='price'>Price: $" . number_format($row['price'], 2) . "</p>";
                echo "<p class='rating'>Rating: ★ " . number_format($row['rating'], 1) . "</p>";
                echo "</div></div>";
            }
        } else {
            echo "<p>No services posted yet.</p>";
        }
        ?>
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
