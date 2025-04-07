<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$job_id = $_GET['id'];
$client_id = $_SESSION['user_id'];

// Fetch job info
$sql = "SELECT * FROM jobs WHERE id = ? AND client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Job not found.";
    exit();
}

$job = $result->fetch_assoc();

// Update on POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['job_title'];
    $description = $_POST['job_description'];
    $category = $_POST['category'];
    $budget = $_POST['budget'];
    $deadline = $_POST['deadline'];
    $job_type = $_POST['job_type'];
    $experience = $_POST['experience_level'];

    $update = "UPDATE jobs SET job_title=?, job_description=?, category=?, budget=?, deadline=?, job_type=?, experience_level=? WHERE id=? AND client_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssisssii", $title, $description, $category, $budget, $deadline, $job_type, $experience, $job_id, $client_id);

    if ($stmt->execute()) {
        header("Location: my_posted_jobs.php");
        exit();
    } else {
        echo "Failed to update job.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/clientjobpost.css"> <!-- your custom CSS file -->
</head>
<body>

    <div class="container">
        <h2>Edit Job</h2>
        <form method="POST">
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required><br>
            <textarea name="job_description" required><?php echo htmlspecialchars($job['job_description']); ?></textarea><br>
            <input type="text" name="category" value="<?php echo htmlspecialchars($job['category']); ?>" required><br>
            <input type="number" name="budget" value="<?php echo htmlspecialchars($job['budget']); ?>" required><br>
            <input type="date" name="deadline" value="<?php echo htmlspecialchars($job['deadline']); ?>" required><br>
            <input type="text" name="job_type" value="<?php echo htmlspecialchars($job['job_type']); ?>" required><br>
            <input type="text" name="experience_level" value="<?php echo htmlspecialchars($job['experience_level']); ?>" required><br>
            <button type="submit">Update Job</button>
        </form>
    </div>

</body>
</html>
