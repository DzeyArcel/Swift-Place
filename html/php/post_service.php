<?php
session_start();
if (!isset($_SESSION['freelancer_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a Service</title>
    <link rel="stylesheet" href="../css/post-service.css">
    <style>
        .rating-stars span {
            font-size: 24px;
            color: gold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Post a New Service</h2>
        <form action="submit_service.php" method="post" enctype="multipart/form-data">
            <input type="text" name="service_title" placeholder="Service Title" required><br>

            <label for="category">Category:</label><br>
            <select name="category" required>
                <option value="">--Select Category--</option>
                <option value="Web Development">Web Development</option>
                <option value="Graphic Design">Graphic Design</option>
                <option value="Writing">Writing</option>
                <option value="Video Editing">Video Editing</option>
                <!-- Add more categories as needed -->
            </select><br>

            <label for="expertise">Expertise Level:</label><br>
            <select name="expertise" required>
                <option value="">--Select Level--</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Expert">Expert</option>
            </select><br>

            <textarea name="description" placeholder="Description" required></textarea><br>
            <input type="text" name="skills" placeholder="Skills (comma separated)" required><br>
            <input type="text" name="delivery_time" placeholder="Delivery Time (e.g. 3 days)" required><br>
            <input type="text" name="tags" placeholder="Tags (comma separated)" required><br>
            <input type="number" step="0.01" name="price" placeholder="Price ($)" required><br>

            <label>Upload Media:</label><br>
            <input type="file" name="media"><br><br>

            <div class="rating-stars">
                <label>Client Rating (Preview):</label><br>
                <span>★ ★ ★ ★ ☆</span>
            </div><br>

            <button type="submit">Post Service</button>
        </form>
    </div>
</body>
</html>
