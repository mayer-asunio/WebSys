<?php
require "../config/db.php";

 
if(!isset($_SESSION['uid']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

 
if(isset($_POST['delete_user'])){
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $_POST['user_id']);
    $stmt->execute();
    $message = "User deleted successfully!";
}
 
$users = $conn->query("SELECT * FROM users ORDER BY role, fullname");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .signature { width: 120px; border: 1px solid #ccc; padding: 2px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">All Users</h3>

    <?php if(isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
 
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if($users->num_rows > 0): ?>
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Signature</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><img src="../uploads/profiles/<?php echo $user['profile_pic']; ?>" class="profile"></td>
                                <td><?php echo $user['fullname']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo ucfirst($user['role']); ?></td>
                                <td><img src="../uploads/signatures/<?php echo $user['signature']; ?>" class="signature"></td>
                                <td>
                                    <form method="post" class="d-flex gap-1">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No users found.</p>
            <?php endif; ?>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
