<?php
// Include database connection
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role']; // Get the selected role

    // Validate input
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                echo "Registration successful!";
                // Redirect based on role, or to login page
                header("Location: login.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Include your CSS file -->
</head>
<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <label for="role">Select Role:</label><br>
        <select id="role" name="role" required>
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
