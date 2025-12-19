<?php
session_start();
require_once "config/db.php";

// Only students
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student'){
    header("Location: auth/login.php"); exit;
}

$user = $_SESSION['user'];

// Fetch student submissions
$result = $conn->query("SELECT * FROM thesis WHERE student_id=".$user['id']." ORDER BY submitted_at DESC");
$submissions = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Thesis Status</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Track Thesis Status</h3>

    <?php if(empty($submissions)): ?>
        <div class="alert alert-info">No submissions found.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Download File</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($submissions as $index=>$t): ?>
                <tr>
                    <td><?= $index+1 ?></td>
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
                    <td><?= date("M d, Y H:i", strtotime($t['submitted_at'])) ?></td>
                    <td>
                        <?php if(!empty($t['file_path'])): ?>
                            <a href="<?= htmlspecialchars($t['file_path']) ?>" target="_blank" class="btn btn-sm btn-info">Download</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="student_dashboard.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>
