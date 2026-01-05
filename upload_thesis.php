<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $keywords = $_POST['keywords'];
    $adviser_id = $_POST['adviser_id'];

    $file_path = null;
    if (isset($_FILES['thesis_file']) && $_FILES['thesis_file']['error'] === 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $file_name = time() . "_" . basename($_FILES['thesis_file']['name']);
        $file_path = $uploadDir . $file_name;
        move_uploaded_file($_FILES['thesis_file']['tmp_name'], $file_path);
    }

    $stmt = $conn->prepare("INSERT INTO thesis (title, abstract, keywords, student_id, adviser_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiis", $title, $abstract, $keywords, $user['id'], $adviser_id, $file_path);
    $stmt->execute();

    $success = "Thesis uploaded successfully!";
}

// Fetch advisers for dropdown
$advisers = $conn->query("SELECT id, name FROM users WHERE role='adviser'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Thesis</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Upload Thesis</h3>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Abstract</label>
            <textarea name="abstract" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Keywords</label>
            <input type="text" name="keywords" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Adviser</label>
            <select name="adviser_id" class="form-select" required>
                <option value="">Select Adviser</option>
                <?php foreach($advisers as $a): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Thesis File (PDF)</label>
            <input type="file" name="thesis_file" class="form-control" accept=".pdf" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Upload</button>
        <a href="student_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
