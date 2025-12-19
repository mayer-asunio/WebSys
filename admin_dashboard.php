<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Thesis Archive</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Thesis Archive</a>
    <div class="d-flex">
        <span class="me-3">Hello, <?= htmlspecialchars($user['name']) ?></span>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h3>Admin Dashboard</h3>
    <p>Welcome, <?= htmlspecialchars($user['name']) ?>. You can manage users, departments, programs, archives, and approve theses.</p>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Manage Users</h5>
                <p class="card-text">Add, edit, or remove system users.</p>
                <a href="#" class="btn btn-primary">Manage Users</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Manage Programs & Departments</h5>
                <p class="card-text">Update program and department information.</p>
                <a href="#" class="btn btn-success">Manage Programs</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm p-3">
                <h5 class="card-title">Approve Theses</h5>
                <p class="card-text">Review and approve student thesis submissions.</p>
                <a href="#" class="btn btn-warning text-white">Approve Theses</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
