<?php
session_start();
require_once 'includes/db_connect.php';

// Check if user is logged in and is an admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] != "admin") {
    header("location: login.php");
    exit;
}

$username = $role = "";
$username_err = $role_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty(trim($_POST["role"]))) {
        $role_err = "Please select a role.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Check for errors and insert into the database
    if (empty($username_err) && empty($role_err)) {
        $password_hash = password_hash('defaultpassword', PASSWORD_DEFAULT); // Set a default password or implement a way to set it

        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $username, $password_hash, $role);

            if ($stmt->execute()) {
                header("location: admin_dashboard.php");
            } else {
                echo "Something went wrong. Please try again.";
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
    <title>Create User</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard_styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Create User</h2>
        <p>Please fill this form to create a new user.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="instructor">Instructor</option>
                    <option value="student">Student</option>
                </select>
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create User">
                <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
