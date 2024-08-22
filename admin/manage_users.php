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
$sql = "SELECT id, username, email, role FROM users";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/manage_users.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Manage Users</h1>

    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="manage_courses.php">Manage Courses</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>

    <section class="users">
        <h2>User List</h2>
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
                <?php while ($user = $result->fetch_assoc()): ?>
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

    <?php
    $conn->close();
    ?>
</body>
</html>
