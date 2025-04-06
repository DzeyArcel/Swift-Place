<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post a Job</title>
  <link rel="stylesheet" href="../css/post_job.css">
</head>
<body>
  <div class="container">
    <h2>Post a New Job</h2>
    <form action="submit_job.php" method="POST">
      <label for="title">Job Title</label>
      <input type="text" id="title" name="job_title" required>

      <label for="description">Job Description</label>
      <textarea id="description" name="job_description" rows="4" required></textarea>

      <label for="category">Category</label>
      <input type="text" id="category" name="category" required>

      <label for="budget">Budget ($)</label>
      <input type="number" id="budget" name="budget" required>

      <label for="deadline">Deadline</label>
      <input type="date" id="deadline" name="deadline" required>

      <label for="skills">Required Skill</label>
      <input type="text" id="skills" name="required_skill" required>

      <label for="type">Job Type</label>
      <select id="type" name="job_type" required>
        <option value="Hourly">Hourly</option>
        <option value="Fixed">Fixed</option>
      </select>

      <label for="experience">Experience Level</label>
      <select id="experience" name="experience_level" required>
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Expert">Expert</option>
      </select>

      <button type="submit">Post Job</button>
    </form>
  </div>
</body>
</html>
