<?php
session_start();
include 'db.php';

// Check if freelancer is logged in
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];

// Fetch posted services
$sql = "SELECT s.*, CONCAT(f.first_name, ' ', f.last_name) AS freelancer_name 
        FROM services s 
        JOIN freelancers f ON s.freelancer_id = f.id 
        WHERE s.freelancer_id = ? ORDER BY s.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posted Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/freelancerservicepost.css"> <!-- Your custom styles -->
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

    <section class="my-services">
        <h2>My Posted Services</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="services-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="service-card">
                        <!-- Check for media (image) path -->
                        <?php if (!empty($row['media_path']) && file_exists($row['media_path'])): ?>
                            <img src="<?= $row['media_path']; ?>" alt="Service Image" class="service-img">
                        <?php else: ?>
                            <div class="placeholder-img">No Image</div>
                        <?php endif; ?>

                        <div class="service-info">
                            <h4><?= htmlspecialchars($row['freelancer_name']); ?></h4>
                            <p class="title"><?= htmlspecialchars($row['service_title']); ?></p>
                            <p class="desc"><?= nl2br(htmlspecialchars($row['description'])); ?></p>
                            <p class="category"><strong>Category:</strong> <?= htmlspecialchars($row['category']); ?></p>
                            <p class="expertise"><strong>Expertise:</strong> <?= htmlspecialchars($row['skills']); ?></p>
                            <p class="price"><strong>Price:</strong> $<?= number_format($row['price'], 2); ?></p>
                            <p class="rating"><strong>Rating:</strong> ★ <?= number_format($row['rating'], 1); ?></p>
                            <a href="edit_service.php?id=<?= $row['id']; ?>" class="edit-link">Edit</a> |
                            <a href="delete_service.php?id=<?= $row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You haven't posted any services yet.</p>
        <?php endif; ?>
    </section>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
