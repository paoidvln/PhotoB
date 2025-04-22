<?php
session_start();
if ($_POST['user'] === 'admin' && $_POST['pass'] === 'secret') {
  $_SESSION['logged_in'] = true;
  header("Location: admin.html");
} else {
  echo "Login failed!";
}
?>
