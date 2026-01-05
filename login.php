<?php
$conn = new mysqli("localhost","root","","dtr_system");
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['type'] = $user['user_type'];
        header("Location: dashboard.php");
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
body { font-family: Arial; background:#f4f4f4; }
.box { width:300px; margin:100px auto; padding:20px; background:#fff; }
input, button { width:100%; padding:8px; margin:6px 0; }
button { background:#28a745; color:#fff; border:none; cursor:pointer; }
.error { color:red; }
</style>
</head>
<body>

<div class="box">
<h2>Login</h2>

<?php if(isset($error)): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>

<p>No account? <a href="register.php">Register</a></p>
</div>

</body>
</html>
