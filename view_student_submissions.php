<?php
session_start();
require_once "config/db.php";

// Only advisers
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='adviser'){
    header("Location: auth/login.php"); exit;
}

// Fetch all theses with student info
$result = $conn->query("
    SELECT t.id, t.title, t.status, t.submitted_at, t.file_path, s.name AS student_name
    FROM thesis t
    JOIN users s ON t.student_id = s.id
    ORDER BY t.submitted_at DESC
");

$theses = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Student Submissions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Student Submissions</h3>
    <?php if(empty($theses)): ?>
        <div class="alert alert-info">No submissions found.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($theses as $index => $t): ?>
                <tr>
                    <td><?= $index+1 ?></td>
                    <td><?= htmlspecialchars($t['student_name']) ?></td>
                    <td><?= htmlspecialchars($t['title']) ?></td>
                    <td>
                        <?php
                        switch($t['status']){
                            case 'approved': echo '<span class="badge bg-success">Approved</span>'; break;
                            case 'rejected': echo '<span class="badge bg-danger">Rejected</span>'; break;
                            default: echo '<span class="badge bg-warning text-dark">Pending</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if(!empty($t['file_path'])): ?>
                            <a href="<?= htmlspecialchars($t['file_path']) ?>" target="_blank" class="btn btn-sm btn-info">Download</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($t['status'] === 'pending'): ?>
                            <a href="approve_submission.php?id=<?= $t['id'] ?>&action=approved" class="btn btn-sm btn-success">Approve</a>
                            <a href="approve_submission.php?id=<?= $t['id'] ?>&action=rejected" class="btn btn-sm btn-danger">Reject</a>
                        <?php else: ?>
                            <?= ucfirst($t['status']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="adviser_dashboard.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>
