<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';

// Check if the user ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Fetch the user data
    $sql = "SELECT username, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $role);
    $stmt->fetch();
    $stmt->close();

    // If the form is submitted, update the user data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_username = $_POST['username'];
        $new_email = $_POST['email'];
        $new_role = $_POST['role'];

        // Update the user data in the database
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $new_username, $new_email, $new_role, $user_id);

        if ($stmt->execute()) {
            header("Location: manage_users.php?msg=User updated successfully");
        } else {
            echo "Error updating user: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    header("Location: manage_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/edit_user.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Edit User</h1>

    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="instructor" <?php echo ($role == 'instructor') ? 'selected' : ''; ?>>Instructor</option>
            <option value="student" <?php echo ($role == 'student') ? 'selected' : ''; ?>>Student</option>
        </select>

        <button type="submit">Update User</button>
    </form>
</body>
</html>
