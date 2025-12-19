<?php
session_start();
require_once "config/db.php";

// Only advisers can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'adviser') {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];

// Handle form submission
if (isset($_POST['update'])) {
    $profile = $_FILES['profile']['name'];
    $signature = $_FILES['signature']['name'];

    $uploadDir = "uploads/";

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Profile picture upload
    if ($profile) {
        $profilePath = $uploadDir . basename($profile);
        move_uploaded_file($_FILES['profile']['tmp_name'], $profilePath);
    }

    // Signature upload
    if ($signature) {
        $signaturePath = $uploadDir . basename($signature);
        move_uploaded_file($_FILES['signature']['tmp_name'], $signaturePath);
    }

    // Update user record
    $stmt = $conn->prepare("UPDATE users SET profile_pic=?, signature=? WHERE id=?");
    $stmt->bind_param("ssi", $profilePath, $signaturePath, $user['id']);
    $stmt->execute();

    // Refresh session user data
    $_SESSION['user']['profile_pic'] = $profilePath;
    $_SESSION['user']['signature'] = $signaturePath;

    $success = "Profile updated successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Profile | Adviser</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="adviser_dashboard.php">Adviser Dashboard</a>
    <div class="d-flex">
      <span class="me-3"><?= htmlspecialchars($user['name']) ?></span>
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h3>Update Profile</h3>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile" class="form-control">
            <?php if(!empty($user['profile_pic'])): ?>
                <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile" width="100" class="mt-2">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Signature</label>
            <input type="file" name="signature" class="form-control">
            <?php if(!empty($user['signature'])): ?>
                <img src="<?= htmlspecialchars($user['signature']) ?>" alt="Signature" width="150" class="mt-2">
            <?php endif; ?>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
        <a href="adviser_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
