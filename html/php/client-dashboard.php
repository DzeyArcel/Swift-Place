<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Redirect if not logged in
    exit();
}

// Get user's first name (Avoid Undefined Index)
$user_name = $_SESSION["user_name"] ?? "Guest";
$client_id = $_SESSION['user_id']; // Get the client_id from the session

// Database connection
include 'db.php';

// Fetch unread notifications count for the logged-in client
$notif_count_query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($notif_count_query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_notifications = $row['unread_count'];
$stmt->close();;

// Mark notifications as read when viewed
if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];
    $update_query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $notification_id, $client_id);
    $stmt->execute();
    $stmt->close();
}



// Fetch all notifications
$notif_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($notif_query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/clientdash.css"> <!-- Link to external CSS file -->
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-container">
                <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
            </div>
            <input type="text" placeholder="Search for services...">
            <nav>
                <a href="../php/my_posted_jobs.php">Your Posted Jobs</a>
                <a href="../php/client_profile.php">Profile</a>
                <a href="../php/logout.php">Logout</a>

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

    <div id="notification-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeNotificationModal()">&times;</span>
            <h3>Notifications</h3>
            <?php if ($result->num_rows > 0) { ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <li class="notification-item <?php echo $row['is_read'] == 0 ? 'unread' : ''; ?>">
                            <a href="client_notification.php?notification_id=<?php echo $row['id']; ?>">
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
            <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <h1>Meet Top Freelancers</h1>
            <p>Hire professionals to get your work done efficiently.</p>
            <button id="post"><a href="post_job.php">Post Jobs</a></button>
        </div>
    </section>



   <section class="recommendations">
    <h2>Freelancer's Services</h2>
    <div class="service-cards">

        <?php
        include 'db.php';  // Include your database connection

        // Query to fetch all services, ordered by creation date
        $query = "SELECT * FROM services ORDER BY created_at DESC";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()):
            // Ensure you get the correct image path or use a default image
            $imagePath = !empty($row['media_path']) ? '../uploads/' . $row['media_path'] : 'default-service.jpg'; // Assuming media_path column holds image name

        ?>
            <div class="card">
                <!-- Displaying service image -->
                <img class="service-img" src="<?= htmlspecialchars($imagePath) ?>" alt="Service Image">

                <div class="card-content">
                    <h3><?= htmlspecialchars($row['service_title']) ?></h3>
                    <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                    <p><strong>Expertise:</strong> <?= htmlspecialchars($row['expertise']) ?></p>
                    <p><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Rating:</strong> <?= number_format($row['rating'], 1) ?> ⭐</p>

                    <!-- Rating Form -->
                    <form action="php/rate_service.php" method="post" class="rating-form">
                        <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
                        <div class="stars">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" id="star<?= $i . '_' . $row['id'] ?>" value="<?= $i ?>">
                                <label for="star<?= $i . '_' . $row['id'] ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <button type="submit">Rate</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
</section>




<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id']; // You still need this for checking session

$conn = new mysqli("localhost", "root", "", "swiftplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify query to fetch all jobs, remove the filter by client_id
$sql = "SELECT jobs.*, CONCAT(users.first_name, ' ', users.last_name) AS poster_name 
        FROM jobs 
        JOIN users ON jobs.client_id = users.id 
        ORDER BY jobs.created_at DESC"; // No WHERE clause anymore
$result = $conn->query($sql);
?>

<!-- 👇 JOB LIST DESIGN SECTION -->
<section class="job-section">
    <h2>Explore Jobs</h2>
    <div class="job-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-card">
                <h3><?= htmlspecialchars($row['job_title']) ?></h3>
                <p><strong>Poster/Client:</strong> <?= htmlspecialchars($row['poster_name']) ?></p> <!-- Display Job Poster Full Name -->
                <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                <p><strong>Budget:</strong> $<?= number_format($row['budget'], 2) ?></p>
                <p><strong>Deadline:</strong> <?= htmlspecialchars($row['deadline']) ?></p>
                <p><strong>Skills:</strong> <?= htmlspecialchars($row['required_skill']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($row['job_type']) ?></p>
                <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience_level']) ?></p>
                <p class="desc"><?= nl2br(htmlspecialchars($row['job_description'])) ?></p>
                <!-- You can add an Apply button here if required -->
            </div>
        <?php endwhile; ?>
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
