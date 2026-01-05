<?php
require "../config/db.php";
if(session_status() == PHP_SESSION_NONE) session_start();

// Check if faculty is logged in
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'faculty'){
    header("Location: ../auth/login.php");
    exit;
}

// Handle assigning subjects
if(isset($_POST['assign'])){
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];

    // Prevent duplicate enrollment
    $check = $conn->prepare("SELECT * FROM enrollments WHERE student_id=? AND subject_id=?");
    $check->bind_param("ii", $student_id, $subject_id);
    $check->execute();
    $res_check = $check->get_result();

    if($res_check->num_rows == 0){
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, subject_id, status) VALUES (?, ?, 'enrolled')");
        $stmt->bind_param("ii", $student_id, $subject_id);
        $stmt->execute();
        $message = "Subject assigned successfully!";
    } else {
        $error = "Student is already enrolled in this subject.";
    }
}

// Fetch students
$students = $conn->query("SELECT * FROM users WHERE role='student'");

// Fetch subjects
$subjects = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class List | Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .signature { width: 120px; border: 1px solid #ccc; padding: 2px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Class List</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Signature</th>
                        <th>Assign Subject</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><img src="../uploads/profiles/<?php echo $student['profile_pic']; ?>" class="profile"></td>
                            <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                            <td><img src="../uploads/signatures/<?php echo $student['signature']; ?>" class="signature"></td>
                            <td>
                                <form method="post" class="d-flex gap-2">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                    <select name="subject_id" class="form-select" required>
                                        <option value="">Select Subject</option>
                                        <?php
                                        $subjects->data_seek(0); // reset pointer
                                        while($sub = $subjects->fetch_assoc()):
                                            // Fetch prerequisite name if exists
                                            $prereq_name = "None";
                                            if($sub['prerequisite_id']){
                                                $pr = $conn->prepare("SELECT name FROM subjects WHERE id=?");
                                                $pr->bind_param("i", $sub['prerequisite_id']);
                                                $pr->execute();
                                                $res_pr = $pr->get_result();
                                                if($r = $res_pr->fetch_assoc()){
                                                    $prereq_name = $r['name'];
                                                }
                                            }
                                        ?>
                                            <option value="<?php echo $sub['id']; ?>">
                                                <?php echo $sub['code'] . " - " . $sub['name'] . " (Prerequisite: " . $prereq_name . ")"; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="submit" name="assign" class="btn btn-primary btn-sm">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
