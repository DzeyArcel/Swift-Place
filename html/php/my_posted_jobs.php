<?php
session_start();
include 'db.php'; // your DB connection file

// Check if client is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

$sql = "SELECT * FROM jobs WHERE client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Posted Jobs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/clientjobpost.css"> <!-- your CSS file -->
</head>
<body>
    <header>
<div class="navbar">
            <div class="logo-container">
                <img src="../photos/Logos-removebg-preview.png" alt="Logo" class="logo-img">
            </div>
            
            <nav>
                
            <a href="client-dashboard.php">Dashboard</a>
               <a href="../php/my_posted_jobs.php">Youre Posted Jobs</a>
                <a href="../php/client_profile.php">Profile</a>
                <a href="../php/logout.php">Logout</a>
            </nav>
        </div>
        </header>

    <h2>My Posted Jobs</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="jobs-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Budget:</strong> $<?php echo htmlspecialchars($row['budget']); ?></p>
                    <p><strong>Deadline:</strong> <?php echo htmlspecialchars($row['deadline']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['job_type']); ?></p>
                    <p><strong>Experience Level:</strong> <?php echo htmlspecialchars($row['experience_level']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['job_description'])); ?></p>

                    <!-- Optional: Edit/Delete links -->
                   <a class="edit-btn" href="edit_job.php?id=<?php echo $row['id']; ?>">Edit</a>
<a class="delete-btn" href="delete_job.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>

                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>You haven't posted any jobs yet.</p>
    <?php endif; ?>

</body>
</html>
