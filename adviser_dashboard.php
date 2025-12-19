<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'adviser') {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Adviser Dashboard | Thesis Archive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background-color: #f8f9fa;}
.card-custom {border-radius: 12px;}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Thesis Archive System</a>
    <div class="d-flex">
        <span class="me-3">Hello, <?= htmlspecialchars($user['name']) ?></span>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h3>Adviser Dashboard</h3>
    <p>Welcome, <?= htmlspecialchars($user['name']) ?>. You can review, approve/reject theses, and manage student submissions.</p>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card card-custom shadow-sm p-3">
                <h5 class="card-title">View Student Submissions</h5>
                <p class="card-text">Check the latest thesis submissions from all students.</p>
                <a href="view_student_submissions.php" class="btn btn-primary">View Submissions</a>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card card-custom shadow-sm p-3">
                <h5 class="card-title">Approve/Reject Thesis</h5>
                <p class="card-text">Approve or reject submitted theses and leave comments.</p>
                <a href="view_student_submissions.php" class="btn btn-success">Manage Approvals</a>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card card-custom shadow-sm p-3">
                <h5 class="card-title">Profile</h5>
                <p class="card-text">Upload your profile picture and signature for identification.</p>
                <a href="adviser_profile.php" class="btn btn-warning text-white">Update Profile</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
