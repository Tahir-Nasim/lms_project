<?php
session_start();

// Check if the user is logged in and is an instructor
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch the instructor's courses
$instructor_id = $_SESSION['id'];
$sql = "SELECT id, title FROM courses WHERE instructor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$courses = $stmt->get_result();
$stmt->close();

// Fetch student progress if a course ID is provided
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$progress = [];

if ($course_id > 0) {
    $sql = "SELECT users.username, progress.completed, progress.total 
            FROM progress 
            JOIN users ON progress.student_id = users.id 
            WHERE progress.course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $progress = $stmt->get_result();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Progress</title>
    <link rel="stylesheet" href="../assets/css/student_progress.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Student Progress</h1>

    <form action="" method="get">
        <label for="course">Select Course:</label>
        <select id="course" name="course_id" onchange="this.form.submit()">
            <option value="">--Select a Course--</option>
            <?php while ($course = $courses->fetch_assoc()): ?>
                <option value="<?php echo $course['id']; ?>" <?php echo $course_id == $course['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($course['title']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($course_id > 0 && $progress): ?>
        <section class="progress">
            <h2>Progress for Course ID: <?php echo htmlspecialchars($course_id); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Completed</th>
                        <th>Total</th>
                        <th>Progress (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $progress->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['completed']); ?></td>
                            <td><?php echo htmlspecialchars($row['total']); ?></td>
                            <td><?php echo number_format(($row['completed'] / $row['total']) * 100, 2); ?>%</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    <?php elseif ($course_id > 0): ?>
        <p>No progress data available for this course.</p>
    <?php endif; ?>
</body>
</html>
