<?php
session_start();

// Include database connection
require_once 'includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } else {
        // Prepare and execute query to fetch user data
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to respective dashboard based on role
                if ($user['role'] === 'student') {
                    header("Location: index.php");
                } elseif ($user['role'] === 'instructor') {
                    header("Location: instructor/instructor_dashboard.php");
                } elseif ($user['role'] === 'admin') {
                    header("Location: admin/admin_dashboard.php");
                }
                exit;
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No account found with that username.';
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
