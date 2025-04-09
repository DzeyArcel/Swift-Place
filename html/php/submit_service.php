<?php
session_start();
include 'db.php'; // Include the database connection

if (!isset($_SESSION['freelancer_id'])) {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['freelancer_id'];
$title = $_POST['service_title'];
$category = !empty($_POST['category']) ? $_POST['category'] : null;
$description = $_POST['description'];
$skills = $_POST['skills'];
$delivery_time = $_POST['delivery_time'];
$tags = $_POST['tags'];
$expertise = $_POST['expertise'];
$price = $_POST['price'];
$rating = 0;
$media_path = null;

// Handle media upload if exists
if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $media_name = basename($_FILES['media']['name']);
    $media_path = $upload_dir . time() . "_" . $media_name;

    move_uploaded_file($_FILES['media']['tmp_name'], $media_path);
}

// Insert service into the database
$stmt = $conn->prepare("INSERT INTO services 
    (freelancer_id, service_title, category, expertise, description, skills, delivery_time, tags, media_path, price, rating, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("issssssssdd", $freelancer_id, $title, $category, $expertise, $description, $skills, $delivery_time, $tags, $media_path, $price, $rating);

if ($stmt->execute()) {
    $service_id = $stmt->insert_id;

    // ✅ Notify all clients about the new service posted by the freelancer
    $clients = $conn->query("SELECT id FROM users"); // Assuming 'users' table holds all clients
    if ($clients) {
        while ($row = $clients->fetch_assoc()) {
            $client_id = $row['id'];

            // Skip notifying the freelancer who posted the service
            if ($client_id == $freelancer_id) {
                continue; // Skip if the client is the same as the freelancer
            }

            $message = "New service/application posted by freelancer: $title";
            $link = "service-details.php?id=$service_id"; // Update path if needed

            // Insert notification for each client
            $notify = $conn->prepare("INSERT INTO notifications (user_id, type, message, link) VALUES (?, 'client', ?, ?)");
            $notify->bind_param("iss", $client_id, $message, $link);
            $notify->execute();
            $notify->close();
        }
    }

    echo "<script>alert('Service posted successfully!'); window.location.href = 'freelancer_dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
