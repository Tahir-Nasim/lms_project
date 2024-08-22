<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Get student ID
$student_id = $_SESSION['id'];

// Fetch student progress
$sql = "SELECT courses.title, progress.status, progress.last_updated 
        FROM progress 
        JOIN courses ON progress.course_id = courses.id 
        WHERE progress.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
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

    <section class="progress">
        <table>
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
