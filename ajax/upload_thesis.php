<?php
require "../config/db.php";

$title = $_POST['title'];
$abstract = $_POST['abstract'];
$keywords = $_POST['keywords'];
$user_id = $_SESSION['user']['id'];

$file = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];
move_uploaded_file($tmp, "../uploads/theses/".$file);

$conn->query("INSERT INTO thesis
(title, abstract, keywords, author_id, file)
VALUES
('$title','$abstract','$keywords','$user_id','$file')");

echo "Uploaded successfully";
