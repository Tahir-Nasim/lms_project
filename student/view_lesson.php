<?php
session_start();
require_once 'includes/db_connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] != "student") {
    header("location: login.php");
    exit;
}


if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $lesson_id = trim($_GET["id"]);
    $user_id = $_SESSION["id"];

    // Fetch lesson details
    $sql = "SELECT title, content FROM lessons WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $lesson_id);
        $stmt->execute();
        $lesson = $stmt->get_result()->fetch_assoc();
    }
} else {
    header("location: student_dashboard.php");
    exit;
}

// Mark lesson as complete
if ($stmt_progress = $conn->prepare("INSERT INTO progress (user_id, course_id, lesson_id, completed_at) VALUES (?, ?, ?, NOW())")) {
    $stmt_progress->bind_param("iii", $user_id, $course_id, $lesson_id);
    $stmt_progress->execute();
    $stmt_progress->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lesson</title>
    <link rel="stylesheet" href="assets/css/view_lesson_styles.css">
</head>
<body>
    <div class="wrapper">
        <h2><?php echo htmlspecialchars($lesson['title']); ?></h2>
        <p><?php echo htmlspecialchars($lesson['content']); ?></p>
    </div>
</body>
</html>
