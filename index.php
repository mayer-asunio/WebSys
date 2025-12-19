<?php
require_once __DIR__ . "/config/db.php";
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

// Check if redirected from registration
$registered_email = isset($_GET['email']) ? $_GET['email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Thesis Archive System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    min-height: 100vh;
}
.login-card {
    border-radius: 15px;
}
</style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="col-md-4 col-sm-10">
<div class="card login-card shadow-lg p-4">

<h4 class="text-center mb-3 fw-bold">Thesis Archive System</h4>

<!-- Error Message -->
<?php if($error): ?>
<div class="alert alert-danger text-center">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required
        value="<?= htmlspecialchars($registered_email) ?>">
  </div>

  <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
  </div>

  <button class="btn btn-primary w-100 py-2 fw-bold">Login</button>
</form>

<hr>
<div class="text-center">
    <span class="text-muted">No account yet?</span>
    <a href="register.php" class="fw-bold">Register here</a>
</div>

</div>
</div>

<!-- Optional: Bootstrap success modal if you want -->
<?php if(isset($_GET['registered'])): ?>
<script>
$(document).ready(function() {
    alert('Registration successful! Please login with your registered credentials.');
});
</script>
<?php endif; ?>

</body>
</html>
