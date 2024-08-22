<?php
session_start();

// Check if the user is logged in and has the instructor role
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch courses created by the logged-in instructor
$instructor_id = $_SESSION['id'];
$sql = "SELECT * FROM courses WHERE instructor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$courses = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/instructor.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

    <nav>
        <ul>
            <li><a href="course_create.php">Create New Course</a></li>
            <li><a href="course_list.php">Courses List</a></li>
            <li><a href="student_progress.php">View Student Progress</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <h2>Your Courses</h2>
    <?php if ($courses->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                    <td>
                        <a href="course_edit.php?id=<?php echo $course['id']; ?>">Edit</a> | 
                        <a href="course_delete.php?id=<?php echo $course['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any courses yet. <a href="course_create.php">Create a new course</a></p>
    <?php endif; ?>

    <!-- Additional sections for viewing student progress, course analytics, etc., can be added here -->

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
