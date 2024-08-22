<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch all courses
$sql = "SELECT courses.id, courses.title, users.username AS instructor, courses.created_at FROM courses JOIN users ON courses.instructor_id = users.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../assets/css/manage_courses.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Manage Courses</h1>

    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <section class="courses">
        <h2>Course List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Instructor</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $course['id']; ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($course['created_at']); ?></td>
                        <td>
                            <a href="edit_course.php?id=<?php echo $course['id']; ?>">Edit</a> | 
                            <a href="delete_course.php?id=<?php echo $course['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                        </td>
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
