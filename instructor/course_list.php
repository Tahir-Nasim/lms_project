<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch all courses
$sql = "SELECT courses.id, courses.title, courses.description, users.username AS instructor, courses.created_at FROM courses JOIN users ON courses.instructor_id = users.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course List</title>
    <link rel="stylesheet" href="../assets/css/course_list.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Course List</h1>

    <nav>
        <ul>
            <li><a href="instructor_dashboard.php">Dashboard</a></li>
            <li><a href="course_create.php">Create Course</a></li>
        </ul>
    </nav>

    <section class="courses">
        <h2>All Courses</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Instructor</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $course['id']; ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo htmlspecialchars($course['description']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($course['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <?php
    $conn->close();
    ?>
</body>
</html>
