<?php
require "../config/db.php";
if(session_status() == PHP_SESSION_NONE) session_start();

 
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'student'){
    header("Location: ../auth/login.php");
    exit;
}

$student_id = $_SESSION['uid'];

 
if(isset($_POST['enroll'])){
    $subject_id = intval($_POST['subject_id']);

 
    $stmt = $conn->prepare("
        SELECT prerequisite_id FROM subjects WHERE id=?
    ");
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $sub = $res->fetch_assoc();

    $canEnroll = true;
    $error = '';

    if($sub['prerequisite_id']){
 
        $pre_id = $sub['prerequisite_id'];
        $check = $conn->prepare("
            SELECT * FROM enrollments WHERE student_id=? AND subject_id=? AND status='completed'
        ");
        $check->bind_param("ii", $student_id, $pre_id);
        $check->execute();
        $res_check = $check->get_result();
        if($res_check->num_rows == 0){
            $canEnroll = false;
            $error = "You must complete the prerequisite subject first.";
        }
    }
 
    $check2 = $conn->prepare("SELECT * FROM enrollments WHERE student_id=? AND subject_id=?");
    $check2->bind_param("ii", $student_id, $subject_id);
    $check2->execute();
    $res_check2 = $check2->get_result();
    if($res_check2->num_rows > 0){
        $canEnroll = false;
        $error = "You are already enrolled in this subject.";
    }

   
    if($canEnroll){
        $stmt2 = $conn->prepare("INSERT INTO enrollments (student_id, subject_id, status) VALUES (?, ?, 'enrolled')");
        $stmt2->bind_param("ii", $student_id, $subject_id);
        $stmt2->execute();
        $message = "Enrolled successfully!";
    }
}

 
$subjects = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll Subjects | Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">
    <h3 class="mb-4 text-center">Enroll in Subjects</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        <?php
                        while($sub = $subjects->fetch_assoc()):
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
                            <?php echo $sub['code'].' - '.$sub['name'].' (Prerequisite: '.$prereq_name.')'; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" name="enroll" class="btn btn-primary w-100">Enroll</button>
                </div>
            </form>
        </div>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
