<?php
session_start();
require_once "config/db.php";

// Only advisers
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='adviser'){
    header("Location: auth/login.php"); exit;
}

if(isset($_GET['id'], $_GET['action'])){
    $thesis_id = intval($_GET['id']);
    $action = $_GET['action'];

    if(!in_array($action,['approved','rejected'])) die("Invalid action.");

    $stmt = $conn->prepare("UPDATE thesis SET status=? WHERE id=?");
    $stmt->bind_param("si",$action,$thesis_id);
    $stmt->execute();

    header("Location: view_student_submissions.php");
    exit;
}
