<?php
// Start session
session_start();

// Include database connection file
require_once '../includes/db_connect.php';

// Fetch available courses from the database
$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Available Courses</h1>
        <nav>
            <?php if (isset($_SESSION['username'])): ?>
                <span>You are logged in as: <strong><?php echo ucfirst($_SESSION['role']); ?></strong></span>
                <a href="../logout.php">Logout</a>
                <a href="../student/student_dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="../login.php">Login</a>
                <a href="../register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h2>Course List</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="course-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="course-item">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <a href="course_details.php?id=<?php echo $row['id']; ?>">View Details</a>
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
