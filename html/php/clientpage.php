<?php
session_start();
include 'db_connect.php';

// Fetch profile details
$sql = "SELECT * FROM client_profiles ORDER BY id DESC LIMIT 1"; // Adjust based on your logic
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No profile found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Profile</title>
    <link rel="stylesheet" href="../css/clientprof.css">
</head>
<body>
    <div class="profile-container">
        <img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture">
        <h2><?php echo $row['company_name']; ?></h2>
        <p>Industry: <?php echo $row['industry']; ?></p>
        <p>Job Type: <?php echo $row['job_type']; ?></p>
        <p>Experience Level: <?php echo $row['experience_level']; ?></p>
        <p>Budget: <?php echo $row['budget']; ?></p>
    </div>
</body>
</html>
