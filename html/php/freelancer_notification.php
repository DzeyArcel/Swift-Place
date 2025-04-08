<?php
session_start();
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: ../freelancer_login.html");
    exit();
}

// Get freelancer ID from session
$freelancer_id = $_SESSION['freelancer_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "swiftplace");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If there's a notification ID in the query string, mark it as read
if (isset($_GET['notification_id'])) {
    $notification_id = $_GET['notification_id'];
    $update_query = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $notification_id, $freelancer_id);
    $stmt->execute();
    $stmt->close();
}

// If a notification is to be deleted
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $delete_id, $freelancer_id);
    $stmt->execute();
    $stmt->close();
    header("Location: freelancer_notification.php"); // Redirect to refresh the page
    exit();
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
    <link rel="stylesheet" href="../css/freelancernotif.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-container">
                <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
            </div>
            <nav>
                <a href="freelancer_dashboard.php">Dashboard</a>
                <a href="my_services.php">Your Posted Services</a>
                <a href="../php/freelance_profile.php">Profile</a>
                <a href="../php/logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <section class="notifications">
    <h2>All Notifications</h2>
    <?php if ($result->num_rows > 0) { ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <li class="notification-item <?php echo $row['is_read'] == 0 ? 'unread' : ''; ?>">
                    <div class="notification-content">
                        <a href="freelancer_notification.php?notification_id=<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['message']); ?>
                        </a>
                        <span class="timestamp"><?php echo htmlspecialchars($row['created_at']); ?></span>
                    </div>

                    <!-- Delete Notification Link positioned after timestamp -->
                    <a href="freelancer_notification.php?delete_id=<?php echo $row['id']; ?>" class="delete-notification" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No notifications yet.</p>
    <?php } ?>
</section>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
