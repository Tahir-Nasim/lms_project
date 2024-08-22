<?php
// Start session
session_start();

// Check if the user is logged in as a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['id'];
    $course_id = intval($_POST['course_id']);

    // Check if the student is already enrolled
    $check_sql = "SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $student_id, $course_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        // Enroll the student in the course
        $enroll_sql = "INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)";
        $enroll_stmt = $conn->prepare($enroll_sql);
        $enroll_stmt->bind_param("ii", $student_id, $course_id);
        $enroll_stmt->execute();

        if ($enroll_stmt->affected_rows > 0) {
            header("Location: student_dashboard.php?msg=Enrolled successfully!");
        } else {
            echo "Error enrolling in course.";
        }

        $enroll_stmt->close();
    } else {
        header("Location: course_details.php?id=$course_id&msg=Already enrolled.");
    }

    $check_stmt->close();
}

$conn->close();
?>
