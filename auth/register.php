<?php
<<<<<<< HEAD
require "../config/db.php";

$profileDir = "../uploads/profiles/";
$signatureDir = "../uploads/signatures/";

// Create folders if they don't exist
if(!is_dir($profileDir)) mkdir($profileDir, 0777, true);
if(!is_dir($signatureDir)) mkdir($signatureDir, 0777, true);

if(isset($_POST['register'])){

    // Profile picture upload
    $pic_name = time() . "_" . $_FILES['pic']['name'];
    move_uploaded_file($_FILES['pic']['tmp_name'], $profileDir . $pic_name);

    // Signature upload
    $sig_name = time() . "_" . $_FILES['sig']['name'];
    move_uploaded_file($_FILES['sig']['tmp_name'], $signatureDir . $sig_name);

    // Password hash
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (role, fullname, email, password, profile_pic, signature) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['role'], $_POST['name'], $_POST['email'], $pass, $pic_name, $sig_name);
    $stmt->execute();

    $success = "User registered successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Register</h3>

                    <?php if(isset($success)) : ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="pic" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Signature</label>
                            <input type="file" name="sig" class="form-control" required>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                    </form>

                    <p class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
require_once __DIR__ . "/../config/db.php";

// Get POST data
$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$role     = $_POST['role'];
$password = $_POST['password'];
$confirm  = $_POST['confirm'];

// Validate passwords
if ($password !== $confirm) {
    die("Passwords do not match");
}

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    die("Email already registered");
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashed, $role);
$stmt->execute();

// Redirect back to register page with success query
header("Location: ../register.php?success=1&email=" . urlencode($email));
exit;

>>>>>>> 352e25f (upload)
