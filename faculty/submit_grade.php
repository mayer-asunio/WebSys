<?php
require "../config/db.php";
if(session_status() == PHP_SESSION_NONE) session_start();


if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

if(isset($_POST['save'])){
    $eid = intval($_POST['eid']); 
    $grade = $conn->real_escape_string($_POST['grade']);
    $conn->query("INSERT INTO grades (enrollment_id, grade) VALUES ($eid, '$grade')");
    $message = "Grade submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Grades | Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Submit Grade</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Enrollment ID</label>
                    <input type="number" name="eid" class="form-control" placeholder="Enter Enrollment ID" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Grade</label>
                    <input type="text" name="grade" class="form-control" placeholder="Enter Grade" required>
                </div>
                <div class="col-12">
                    <button type="submit" name="save" class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
