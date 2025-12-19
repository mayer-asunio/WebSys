<?php
<<<<<<< HEAD
$conn = new mysqli("localhost","root","","enrollment_db");
if ($conn->connect_error) die("Database connection failed");
session_start();
?>
=======
// Database configuration
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "thesis_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
>>>>>>> 352e25f (upload)
