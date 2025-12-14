<?php
require "../config/db.php";
session_start();  
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}
 
$admin_id = $_SESSION['uid'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
 
if(!$admin){
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

 
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Enrollment System</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white">
                <?php echo htmlspecialchars($admin['fullname']); ?>
            </span>
            <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="mb-4">Admin Dashboard</h3>

    <div class="row g-4">
 
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">View, delete, and manage Students, Faculty, and Admins.</p>
                    <a href="users.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Subjects</h5>
                    <p class="card-text">Add, edit, delete subjects and assign prerequisites.</p>
                    <a href="subjects.php" class="btn btn-success">Go</a>
                </div>
            </div>
        </div>
 
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">View Enrollments</h5>
                    <p class="card-text">View all student enrollments and override statuses if needed.</p>
                    <a href="enrollments.php" class="btn btn-warning">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
