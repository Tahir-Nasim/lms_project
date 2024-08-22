<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Fetch all users
$sql_users = "SELECT id, username, email, role FROM users";
$result_users = $conn->query($sql_users);

// Fetch all courses
$sql_courses = "SELECT id, title, instructor_id, created_at FROM courses";
$result_courses = $conn->query($sql_courses);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Admin Dashboard</h1>

    <nav>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <section class="overview">
        <h2>Overview</h2>
        <p>Total Users: <?php echo $result_users->num_rows; ?></p>
        <p>Total Courses: <?php echo $result_courses->num_rows; ?></p>
    </section>

    <section class="users">
        <h2>Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> | 
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <section class="courses">
        <h2>Courses</h2>
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
                <?php while ($course = $result_courses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $course['id']; ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor_id']); ?></td>
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










<!-- <?php
session_start();
require_once 'includes/db_connect.php';

// Check if user is logged in and is an admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] != "admin") {
    header("location: login.php");
    exit;
}

// Fetch users from the database
$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard_styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Admin Dashboard</h2>
        <p>Welcome, Admin! Manage users below.</p>
        <a href="create_user.php" class="btn btn-primary">Create New User</a>

        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?> -->
