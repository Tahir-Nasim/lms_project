<?php
session_start();

// Check if the user is logged in and is an instructor or admin
if (!isset($_SESSION['id']) || ($_SESSION['role'] !== 'instructor' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_id = $_SESSION['id'];

    // Prepare the SQL insert statement
    $sql = "INSERT INTO courses (title, description, instructor_id, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $description, $instructor_id);

    if ($stmt->execute()) {
        header("Location: instructor_dashboard.php?msg=Course created successfully");
    } else {
        echo "Error creating course: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Course</title>
    <link rel="stylesheet" href="../assets/css/course_create.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Create New Course</h1>

    <form action="" method="post">
        <label for="title">Course Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Course Description:</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <button type="submit">Create Course</button>
    </form>
</body>
</html>
