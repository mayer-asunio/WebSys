<?php
session_start();
<<<<<<< HEAD
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
=======

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to login page
header("Location: index.php");
exit;
>>>>>>> 352e25f (upload)
