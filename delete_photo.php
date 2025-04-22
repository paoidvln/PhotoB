<?php
session_start();
if (!$_SESSION['logged_in']) {
  http_response_code(403);
  exit("Not authorized");
}

$id = $_GET['id'];
$conn = new mysqli("localhost", "user", "pass", "photob");

$res = $conn->query("SELECT filename FROM photos WHERE id=$id");
if ($res->num_rows) {
  $file = $res->fetch_assoc()['filename'];
  if (file_exists($file)) unlink($file);
  $conn->query("DELETE FROM photos WHERE id=$id");
  echo "Photo deleted!";
} else {
  echo "Photo not found.";
}
?>
