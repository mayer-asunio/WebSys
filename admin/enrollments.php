<?php
require "../config/db.php";

// Check if admin is logged in
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

// Handle override (optional: mark completed or delete enrollment)
if(isset($_POST['update_status'])){
    $enroll_id = $_POST['enroll_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE enrollments SET status=? WHERE id=?");
    $stmt->bind_param("si",$new_status, $enroll_id);
    $stmt->execute();
    $message = "Enrollment status updated!";
}

if(isset($_POST['delete'])){
    $enroll_id = $_POST['enroll_id'];
    $stmt = $conn->prepare("DELETE FROM enrollments WHERE id=?");
    $stmt->bind_param("i",$enroll_id);
    $stmt->execute();
    $message = "Enrollment deleted!";
}

// Fetch all enrollments with student and subject info
$enrollments = $conn->query("
    SELECT e.id AS enroll_id, e.status, u.fullname, u.profile_pic, u.signature, s.code, s.name AS subject_name
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    JOIN subjects s ON e.subject_id = s.id
    ORDER BY u.fullname
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollments | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .signature { width: 120px; border: 1px solid #ccc; padding: 2px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">All Enrollments</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if($enrollments->num_rows > 0): ?>
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th>Signature</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($enr = $enrollments->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../uploads/profiles/<?php echo $enr['profile_pic']; ?>" class="profile"></td>
                                <td><?php echo $enr['fullname']; ?></td>
                                <td><img src="../uploads/signatures/<?php echo $enr['signature']; ?>" class="signature"></td>
                                <td><?php echo $enr['code']; ?></td>
                                <td><?php echo $enr['subject_name']; ?></td>
                                <td><?php echo ucfirst($enr['status']); ?></td>
                                <td>
                                    <form method="post" class="d-flex gap-1">
                                        <input type="hidden" name="enroll_id" value="<?php echo $enr['enroll_id']; ?>">
                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="enrolled" <?php if($enr['status']=='enrolled') echo 'selected'; ?>>Enrolled</option>
                                            <option value="completed" <?php if($enr['status']=='completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-success btn-sm">Update</button>
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No enrollments found.</p>
            <?php endif; ?>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
