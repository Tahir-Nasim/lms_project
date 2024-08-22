<?php
// Start session
session_start();

// Include database connection file
require_once '../includes/db_connect.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Fetch student details from the database
$userId = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$error = '';
$success = '';

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    
    if (empty($username) || empty($email)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Update user details in the database
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $email, $userId);

        if ($stmt->execute()) {
            $success = 'Profile updated successfully.';
            // Update session variables
            $_SESSION['username'] = $username;
        } else {
            $error = 'Failed to update profile. Please try again later.';
        }
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Your Profile</h1>
        <nav>
            <a href="student_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Profile Information</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="profile.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Learning Management System</p>
    </footer>
</body>
</html>
