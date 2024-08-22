<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch student information
$student_id = $_SESSION['id'];

// Fetch the courses the student is enrolled in
$sql = "SELECT courses.id, courses.title, progress.completed, progress.total
        FROM enrollments
        JOIN courses ON enrollments.course_id = courses.id
        LEFT JOIN progress ON courses.id = progress.course_id AND enrollments.student_id = progress.student_id
        WHERE enrollments.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$courses = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/student_dashboard.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
        <nav>
            <a href="student_dashboard.php">Dashboard</a>
            <a href="view_courses.php">Courses</a>
            <a href="profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <section class="enrolled-courses">
        <h2>Your Enrolled Courses</h2>
        <?php if ($courses->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Completed</th>
                        <th>Total</th>
                        <th>Progress (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($course['completed'] ?? 0, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($course['total'] ?? 0, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo ($course['total'] > 0) ? number_format(($course['completed'] / $course['total']) * 100, 2) : 0; ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You are not enrolled in any courses yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>
