<?php
require "../config/db.php";
if(session_status() == PHP_SESSION_NONE) session_start();
if($_SESSION['role']!='admin') die("Access denied");

// Handle Add Subject
if(isset($_POST['add'])){
    $code = $conn->real_escape_string($_POST['code']);
    $name = $conn->real_escape_string($_POST['name']);
    $conn->query("INSERT INTO subjects (code,name,prerequisite_id) VALUES ('$code','$name',NULL)");
    $message = "Subject added successfully!";
}

// Fetch all subjects
$subjects = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Subjects</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add Subject Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="post" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Subject Code</label>
                    <input type="text" name="code" class="form-control" placeholder="Enter code" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add" class="btn btn-success w-100">Add Subject</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if($subjects->num_rows > 0): ?>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($s = $subjects->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($s['code']); ?></td>
                                <td><?php echo htmlspecialchars($s['name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No subjects found.</p>
            <?php endif; ?>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
