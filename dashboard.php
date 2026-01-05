<?php
<<<<<<< HEAD
$conn = new mysqli("localhost","root","","dtr_system");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_account'])) {
    $id = $_SESSION['id'];
    $conn->query("DELETE FROM users WHERE id=$id");
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['remove']) && $_SESSION['type'] == 'admin') {
    $conn->query("DELETE FROM users WHERE id=".$_GET['remove']);
}

$user = $conn->query(
    "SELECT * FROM users WHERE id=".$_SESSION['id']
)->fetch_assoc();

$search = $_GET['search'] ?? '';
$sort   = $_GET['sort'] ?? 'fullname';

$users = $conn->query("
    SELECT * FROM users
    WHERE fullname LIKE '%$search%'
    ORDER BY $sort
");
?>

<!DOCTYPE html>
<html>
<head>
<title>DTR Dashboard</title>
<style>
body {
    font-family: Arial, sans-serif;
    background:#f2f2f2;
}
.header {
    background:#333;
    color:white;
    padding:15px;
}
.header a {
    color:white;
    float:right;
    text-decoration:none;
    margin-left:15px;
}
.container {
    width:90%;
    margin:auto;
}
.profile {
    background:white;
    padding:20px;
    margin-top:20px;
    text-align:center;
}
.profile img {
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
}
.btn {
    display:inline-block;
    margin-top:10px;
    padding:8px 14px;
    background:#c82333;
    color:white;
    text-decoration:none;
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    background:white;
}
th, td {
    padding:10px;
    border:1px solid #ccc;
}
th a {
    text-decoration:none;
    color:black;
}
</style>
</head>

<body>

<div class="header">
    <strong>DTR System</strong>
    <a href="?logout">Logout</a>
</div>

<div class="container">

<div class="profile">
    <img src="upload/<?= htmlspecialchars($user['photo']) ?>">
    <h2><?= htmlspecialchars($user['fullname']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['user_type']) ?></p>

    <a href="?delete_account"
       onclick="return confirm('Are you sure you want to delete your account?')"
       class="btn">
       Delete Account
    </a>
</div>

<?php if ($_SESSION['type'] === 'admin'): ?>
<h3>Admin – User Management</h3>

<form method="get">
    <input name="search" placeholder="Search by name">
</form>

<table>
<tr>
    <th><a href="?sort=fullname">Name</a></th>
    <th><a href="?sort=email">Email</a></th>
    <th><a href="?sort=user_type">Role</a></th>
    <th>Action</th>
</tr>

<?php while($u = $users->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($u['fullname']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['user_type']) ?></td>
    <td>
        <?php if ($u['id'] != $_SESSION['id']): ?>
        <a href="?remove=<?= $u['id'] ?>"
           onclick="return confirm('Delete this user?')"
           class="btn">
           Delete
        </a>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>
<?php endif; ?>

</div>

</body>
</html>
=======
session_start();

// Protect page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Redirect based on role
$user = $_SESSION['user'];
if ($user['role'] === 'student') {
    header("Location: student_dashboard.php");
    exit;
} elseif ($user['role'] === 'adviser') {
    header("Location: adviser_dashboard.php");
    exit;
} elseif ($user['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Unknown role!";
    exit;
}
>>>>>>> e39c751b2861638d9eec7435696bae51f88369c0
