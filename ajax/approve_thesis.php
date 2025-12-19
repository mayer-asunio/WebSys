<?php
require "../config/db.php";

$id = $_POST['id'];
$status = $_POST['status'];

$conn->query("UPDATE thesis SET status='$status' WHERE id=$id");
echo "Updated";
