<?php
require_once 'includes/db_connect.php';

if ($conn) {
    echo "Connected successfully to the database!";
} else {
    echo "Failed to connect to the database.";
}
?>
