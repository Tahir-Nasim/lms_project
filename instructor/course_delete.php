<?php
session_start();

// Check if the user is logged in and is an instructor or admin
if (!isset($_SESSION['id']) || ($_SESSION['role'] !== 'instructor' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Check if the course ID is provided
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);

    // Prepare the SQL delete statement
    $sql = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        // Redirect to the course list page with a success message
        header("Location: instructor_dashboard.php?msg=Course deleted successfully");
    } else {
        // Redirect to the course list page with an error message
        header("Location: course_list.php?msg=Error deleting course");
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the course list page if no course ID is provided
    header("Location: course_list.php");
}
exit;
