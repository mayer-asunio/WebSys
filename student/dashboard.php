<?php
require "../config/db.php";

// Check if student is logged in
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit;
}

// Fetch student info
$student_id = $_SESSION['uid'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i",$student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Fetch enrolled subjects
$enrollments = $conn->query("
    SELECT s.id, s.code, s.name, e.status
    FROM enrollments e
    JOIN subjects s ON e.subject_id = s.id
    WHERE e.student_id = $student_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">

        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="../uploads/profiles/<?php echo $student['profile_pic']; ?>" class="rounded-circle mb-3" width="100" height="100" alt="Profile">
                    <h4><?php echo $student['fullname']; ?></h4>
                    <p class="text-muted"><?php echo ucfirst($student['role']); ?></p>
                    <img src="../uploads/signatures/<?php echo $student['signature']; ?>" class="img-fluid mt-2" alt="Signature">
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Enrolled Subjects</h4>

                    <?php if($enrollments->num_rows > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($sub = $enrollments->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $sub['code']; ?></td>
                                        <td><?php echo $sub['name']; ?></td>
                                        <td>
                                            <?php if($sub['status'] == 'completed'): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Enrolled</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">You have not enrolled in any subjects yet.</p>
                    <?php endif; ?>

                    <a href="enroll.php" class="btn btn-primary mt-3">Enroll in New Subject</a>
                    <a href="../auth/logout.php" class="btn btn-secondary mt-3">Logout</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
