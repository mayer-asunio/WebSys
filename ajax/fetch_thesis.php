<?php
require "../config/db.php";

$q = $conn->query("SELECT * FROM thesis ORDER BY id DESC");

echo "<table class='table table-bordered'>";
echo "<tr><th>Title</th><th>Status</th><th>Action</th></tr>";

while($r = $q->fetch_assoc()){
echo "<tr>
<td>{$r['title']}</td>
<td><span class='badge bg-info'>{$r['status']}</span></td>
<td>
<a href='uploads/theses/{$r['file']}' class='btn btn-sm btn-primary'>Download</a>
</td>
</tr>";
}
echo "</table>";
