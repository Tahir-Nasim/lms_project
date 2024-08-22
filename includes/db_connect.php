<?php
$servername = "localhost";
$username = "root"; // Default username in XAMPP/WAMP
$password = ""; // Default password is usually empty
$dbname = "lms_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
