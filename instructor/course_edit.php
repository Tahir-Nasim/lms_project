<?php
session_start();

// Check if the user is logged in and is an instructor or admin
if (!isset($_SESSION['id']) || ($_SESSION['role'] !== 'instructor' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Check if the course ID is provided
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);

    // Fetch the course data
    $sql = "SELECT title, description FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($title, $description);
    $stmt->fetch();
    $stmt->close();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_title = $_POST['title'];
        $new_description = $_POST['description'];

        // Update the course data in the database
        $sql = "UPDATE courses SET title = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_title, $new_description, $course_id);

        if ($stmt->execute()) {
            header("Location: course_list.php?msg=Course updated successfully");
        } else {
            echo "Error updating course: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    header("Location: course_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../assets/css/course_edit.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Edit Course</h1>

    <form action="" method="post">
        <label for="title">Course Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

        <label for="description">Course Description:</label>
        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>

        <button type="submit">Update Course</button>
    </form>
</body>
</html>
