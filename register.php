<?php
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

</body>
</html>
