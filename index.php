<?php
// Start session
session_start();

// Function to check user role and redirect to the respective dashboard
function redirect_based_on_role() {
    if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'student') {
            header("Location: student/student_dashboard.php");
            exit;
        } elseif ($_SESSION['role'] === 'instructor') {
            header("Location: instructor/instructor_dashboard.php");
            exit;
        } elseif ($_SESSION['role'] === 'admin') {
            header("Location: admin/admin_dashboard.php");
            exit;
        }
    }
}

// Call the function to redirect based on role
redirect_based_on_role();

// Include database connection file
require_once 'includes/db_connect.php';

// Fetch available courses from the database
$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS Home</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome to the Learning Management System</h1>
        <nav>
            <?php if (isset($_SESSION['username'])): ?>
                <span>You are logged in as: <strong><?php echo ucfirst($_SESSION['role']); ?></strong></span>
                <a href="logout.php">Logout</a>
                <a href="<?php echo $_SESSION['role'] === 'admin' ? 'admin/admin_dashboard.php' : ($_SESSION['role'] === 'instructor' ? 'instructor/instructor_dashboard.php' : 'student/student_dashboard.php'); ?>">Dashboard</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h2>Available Courses</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="course-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="course-item">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <a href="course_details.php?id=<?php echo $row['id']; ?>">View Course</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No courses available at the moment. Please check back later.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Learning Management System</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
