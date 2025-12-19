<?php
session_start();
require_once "config/db.php";

// Protect page: only students
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];

// Fetch student submissions
$stmt = $conn->prepare("SELECT * FROM thesis WHERE student_id=? ORDER BY submitted_at DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Student Dashboard</a>
    <div class="d-flex">
      <span class="me-3"><?= htmlspecialchars($user['name']) ?></span>
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h3>Welcome, <?= htmlspecialchars($user['name']) ?></h3>

    <div class="row mt-4">
        <!-- Upload Thesis -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Upload Thesis</h5>
                <p class="card-text">Submit your thesis file with metadata.</p>
                <a href="upload_thesis.php" class="btn btn-primary">Upload Thesis</a>
            </div>
        </div>

        <!-- Track Status -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Track Status</h5>
                <p class="card-text">View the status of your submitted theses.</p>
                <a href="track_status.php" class="btn btn-success">Track Status</a>
            </div>
        </div>

        <!-- Update Profile -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Profile</h5>
                <p class="card-text">Upload profile picture and signature.</p>
                <a href="student_profile.php" class="btn btn-warning text-white">Update Profile</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
