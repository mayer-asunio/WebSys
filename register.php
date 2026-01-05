<?php
<<<<<<< HEAD
$conn = new mysqli("localhost","root","","dtr_system");
session_start();

if (isset($_POST['register'])) {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $type = $_POST['user_type'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "upload/$photo");

    $conn->query("INSERT INTO users (fullname,email,password,user_type,photo)
                  VALUES ('$name','$email','$pass','$type','$photo')");

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body { font-family: Arial; background:#f4f4f4; }
.box { width:350px; margin:80px auto; padding:20px; background:#fff; }
input, select, button { width:100%; padding:8px; margin:6px 0; }
button { background:#007bff; color:#fff; border:none; cursor:pointer; }
a { text-decoration:none; }
</style>
</head>
<body>

<div class="box">
<h2>Register</h2>
<form method="post" enctype="multipart/form-data">
<input name="fullname" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<select name="user_type">
<option value="faculty">Faculty</option>
<option value="admin">Admin</option>
</select>

<input type="file" name="photo" required>
<button name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>
</div>

=======
$registered_email = isset($_GET['email']) ? $_GET['email'] : '';
$show_success_modal = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Thesis Archive System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
body {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    min-height: 100vh;
}
.card-register {
    border-radius: 15px;
}
</style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="col-md-5 col-sm-10">
<div class="card card-register shadow-lg p-4">

<h4 class="text-center mb-3 fw-bold">Create Account</h4>

<form action="auth/register.php" method="POST">
  <div class="mb-3">
      <input class="form-control" name="name" placeholder="Full Name" required>
  </div>
  <div class="mb-3">
      <input class="form-control" name="email" type="email" placeholder="Email" required>
  </div>
  <div class="mb-3">
      <select class="form-control" name="role" required>
        <option value="">Select Role</option>
        <option value="student">Student</option>
        <option value="adviser">Adviser</option>
      </select>
  </div>
  <div class="mb-3">
      <input class="form-control" name="password" type="password" placeholder="Password" required>
  </div>
  <div class="mb-3">
      <input class="form-control" name="confirm" type="password" placeholder="Confirm Password" required>
  </div>
  <button class="btn btn-success w-100 py-2 fw-bold">Register</button>
</form>

<hr>
<div class="text-center">
Already have an account? <a href="index.php">Login here</a>
</div>

</div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registration Successful</h5>
      </div>
      <div class="modal-body">
        Your account has been created successfully.
      </div>
      <div class="modal-footer">
        <button type="button" id="okButton" class="btn btn-primary">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    var showModal = <?= $show_success_modal ? 'true' : 'false'; ?>;
    var email = "<?= htmlspecialchars($registered_email) ?>";

    if(showModal) {
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();

        $('#okButton').click(function() {
            window.location.href = 'index.php?email=' + encodeURIComponent(email);
        });
    }
});
</script>

>>>>>>> e39c751b2861638d9eec7435696bae51f88369c0
</body>
</html>
