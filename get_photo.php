<?php
session_start();
if (!$_SESSION['logged_in']) {
  http_response_code(403);
  exit("Not authorized");
}

$conn = new mysqli("localhost", "user", "pass", "photob");
$res = $conn->query("SELECT id, filename, uploaded_at FROM photos ORDER BY uploaded_at DESC");

$photos = [];
while ($row = $res->fetch_assoc()) {
  $photos[] = $row;
}
echo json_encode($photos);
?>
