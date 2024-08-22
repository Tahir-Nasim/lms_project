<?php
// Start session
session_start();

// Include database connection
require_once '../includes/db_connect.php';

// Check if course ID is provided
if (!isset($_GET['id'])) {
    header("Location: student_dashboard.php");
    exit;
}

$course_id = intval($_GET['id']);
$student_id = $_SESSION['id']; // Assuming the student's ID is stored in the session

// Fetch course details, including instructor's name
$sql = "SELECT courses.*, users.username AS instructor_name 
        FROM courses 
        JOIN users ON courses.instructor_id = users.id 
        WHERE courses.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Course not found!";
    exit;
}

$course = $result->fetch_assoc();

// Check if the student is already enrolled in the course
$enroll_sql = "SELECT * FROM enrollments WHERE course_id = ? AND student_id = ?";
$enroll_stmt = $conn->prepare($enroll_sql);
$enroll_stmt->bind_param("ii", $course_id, $student_id);
$enroll_stmt->execute();
$enroll_result = $enroll_stmt->get_result();

$is_enrolled = $enroll_result->num_rows > 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($course['title']); ?></h1>
    </header>
    <main>
        <p><?php echo htmlspecialchars($course['description']); ?></p>
        <p>Instructor: <?php echo isset($course['instructor_name']) ? htmlspecialchars($course['instructor_name']) : 'Unknown Instructor'; ?></p>

        <!-- Enrollment section -->
        <?php if ($is_enrolled): ?>
            <p>You are already enrolled in this course.</p>
        <?php else: ?>
            <form action="enroll.php" method="post">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <button type="submit">Enroll in this Course</button>
            </form>
        <?php endif; ?>

        <!-- Button to redirect to the student dashboard -->
        <p>
            <a href="student_dashboard.php" class="button">Back to Dashboard</a>
        </p>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Learning Management System</p>
    </footer>
</body>
</html>

<?php
$enroll_stmt->close();
$stmt->close();
$conn->close();
?>
