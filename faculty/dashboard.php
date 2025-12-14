<?php
require "../config/db.php";

// Check if faculty is logged in
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

// Fetch faculty info
$faculty_id = $_SESSION['uid'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i",$faculty_id);
$stmt->execute();
$faculty = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">

        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="../uploads/profiles/<?php echo $faculty['profile_pic']; ?>" class="rounded-circle mb-3" width="100" height="100" alt="Profile">
                    <h4><?php echo $faculty['fullname']; ?></h4>
                    <p class="text-muted"><?php echo ucfirst($faculty['role']); ?></p>
                    <img src="../uploads/signatures/<?php echo $faculty['signature']; ?>" class="img-fluid mt-2" alt="Signature">
                </div>
            </div>
        </div>

        <!-- Dashboard Actions -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Faculty Dashboard</h4>
                    
                    <div class="d-grid gap-3">
                        <a href="class_list.php" class="btn btn-primary btn-lg">View Class Lists</a>
                        <a href="submit_grade.php" class="btn btn-success btn-lg">Submit Grades</a>
                        <a href="../auth/logout.php" class="btn btn-secondary btn-lg">Logout</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
