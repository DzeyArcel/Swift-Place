<?php
session_start();
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: ../freelancer_login.html");
    exit();
}

// Get freelancer name from session
$freelancer_name = isset($_SESSION["freelancer_name"]) ? $_SESSION["freelancer_name"] : "Freelancer";

// Database connection
$conn = new mysqli("localhost", "root", "", "swiftplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get freelancer ID
$freelancer_id = $_SESSION['freelancer_id'];

// Fetch unread notifications count
$notif_count_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($notif_count_query);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_notifications = $row['unread_count'];
$stmt->close();

// Mark notifications as read when viewed
if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];
    $update_query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $notification_id, $freelancer_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all notifications
$notif_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($notif_query);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
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
                <a href="my_services.php">Your Posted Services</a>
                <a href="../php/freelance_profile.php">Profile</a>
                <a href="../php/logout.php">Logout</a>

                <!-- Notification icon with modal -->
                <div class="notification-icon" id="notification-icon">
                    <a href="javascript:void(0)" onclick="openNotificationModal()">
                        <i class="fa fa-bell"></i>
                        <?php if ($unread_notifications > 0) { ?>
                            <span class="notification-count"><?php echo $unread_notifications; ?></span>
                        <?php } ?>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Notification Modal -->
    <div id="notification-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeNotificationModal()">&times;</span>
            <h3>Notifications</h3>
            <?php if ($result->num_rows > 0) { ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <li class="notification-item <?php echo $row['is_read'] == 0 ? 'unread' : ''; ?>">
                            <a href="freelancer_notification.php?notification_id=<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </a>
                            <span class="timestamp"><?php echo htmlspecialchars($row['created_at']); ?></span>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No notifications yet.</p>
            <?php } ?>
        </div>
    </div>

    <script>
        // Function to open the notification modal
        function openNotificationModal() {
            document.getElementById('notification-modal').style.display = 'block';
        }

        // Function to close the notification modal
        function closeNotificationModal() {
            document.getElementById('notification-modal').style.display = 'none';
        }

        // Close modal if user clicks outside of the modal content
        window.onclick = function(event) {
            if (event.target == document.getElementById('notification-modal')) {
                closeNotificationModal();
            }
        }
    </script>
    


    <section class="hero">
        <div class="hero-content">
            <h1>Welcome, <?php echo htmlspecialchars($freelancer_name); ?>!</h1>
            <p>Find the best freelance projects and start working today.</p>
            <button><a href="post_service.php">Post Services</a></button>
        </div>
    </section>



    <section class="job-listings">
    <h2>Available Jobs</h2>
    <div class="job-cards">
        <?php
        $conn = new mysqli("localhost", "root", "", "swiftplace");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Adjust the SQL query to use 'client_id' from the jobs table
        $sql = "SELECT jobs.*, CONCAT(users.first_name, ' ', users.last_name) AS poster_name
                FROM jobs 
                JOIN users ON jobs.client_id = users.id 
                ORDER BY jobs.created_at DESC";

        $result = $conn->query($sql);

        if (!$result) {
            // Output the SQL error if the query fails
            echo "Error: " . $conn->error;
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='job-post'>";
                echo "<h3>" . htmlspecialchars($row['job_title']) . "</h3>";
                echo "<p class='desc'>" . htmlspecialchars($row['job_description']) . "</p>";
                echo "<p><strong>Skills:</strong> " . htmlspecialchars($row['required_skill']) . "</p>";
                echo "<p><strong>Budget:</strong> $" . htmlspecialchars($row['budget']) . "</p>";
                echo "<p><strong>Deadline:</strong> " . htmlspecialchars($row['deadline']) . "</p>";
                echo "<p><strong>Type:</strong> " . htmlspecialchars($row['job_type']) . "</p>";
                echo "<p><strong>Experience:</strong> " . htmlspecialchars($row['experience_level']) . "</p>";
                // Display the name of the client who posted the job
                echo "<p><strong>Posted by:</strong> " . htmlspecialchars($row['poster_name']) . "</p>";
                // Add Apply button
                echo "<form action='Contact.php' method='post'>
                        <input type='hidden' name='job_id' value='" . $row['id'] . "'>
                        <button class='apply-btn' type='submit'>Contact</button>
                      </form>";
                echo "</div>";
            }
        }

        $conn->close();
        ?>
    </div>
</section>


    
 
    <section class="service-listings">
    <h2>Explore Freelance Services</h2>
    <div class="service-cards">
        <?php
        include 'db.php';

        // Query to fetch service details with freelancer info
        $query = "SELECT s.*, CONCAT(f.first_name, ' ', f.last_name) AS freelancer_name
                  FROM services s
                  JOIN freelancers f ON s.freelancer_id = f.id
                  ORDER BY s.created_at DESC";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='service-card'>";
                
                // Displaying service image if available
                if (!empty($row['media_path'])) {
                    echo "<img src='" . htmlspecialchars($row['media_path']) . "' alt='Service Image' class='service-img'>";
                } else {
                    echo "<div class='placeholder-img'>No Image</div>";
                }

                echo "<div class='service-info'>";
                echo "<h4>" . htmlspecialchars($row['freelancer_name']) . "</h4>";
                echo "<p class='title'>" . htmlspecialchars($row['service_title']) . "</p>";
                echo "<p class='desc'>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p class='category'>Category: " . htmlspecialchars($row['category']) . "</p>";
                echo "<p class='skills'>Skills: " . htmlspecialchars($row['skills']) . "</p>";
                
                // Displaying expertise, delivery time, and tags
                echo "<p class='expertise'>Expertise: " . htmlspecialchars($row['expertise']) . "</p>";
                echo "<p class='delivery-time'>Delivery Time: " . htmlspecialchars($row['delivery_time']) . " days</p>";

                // Displaying tags as a comma-separated list
                $tags = htmlspecialchars($row['tags']);
                echo "<p class='tags'>Tags: " . $tags . "</p>";

                echo "<p class='price'>Price: $" . number_format($row['price'], 2) . "</p>";
                
                // Displaying rating if available
                if (isset($row['rating']) && $row['rating'] !== null) {
                    echo "<p class='rating'>Rating: ★ " . number_format($row['rating'], 1) . "</p>";
                } else {
                    echo "<p class='rating'>No ratings yet</p>";
                }
                
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

<?php
$stmt->close();
$conn->close();
?>