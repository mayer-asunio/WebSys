<?php
session_start();

// Protect page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Redirect based on role
$user = $_SESSION['user'];
if ($user['role'] === 'student') {
    header("Location: student_dashboard.php");
    exit;
} elseif ($user['role'] === 'adviser') {
    header("Location: adviser_dashboard.php");
    exit;
} elseif ($user['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Unknown role!";
    exit;
}
