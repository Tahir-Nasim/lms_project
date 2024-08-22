<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Check if the user ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Include database connection
    require_once '../includes/db_connect.php';

    // Prepare the SQL delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the manage users page with a success message
        header("Location: manage_users.php?msg=User deleted successfully");
    } else {
        // Redirect to the manage users page with an error message
        header("Location: manage_users.php?msg=Error deleting user");
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the manage users page if no user ID is provided
    header("Location: manage_users.php");
}
exit;
