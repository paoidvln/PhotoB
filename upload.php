<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
  header("Location: login.html");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (for example, limit to 5MB)
    if ($_FILES["photo"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";

            // Insert file information into the database
            $conn = new mysqli("localhost", "your_user", "your_pass", "your_db");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Insert the file path into the database
            $filename = $conn->real_escape_string($target_file); // Escape the filename to avoid SQL injection
            $sql = "INSERT INTO photos (filename) VALUES ('$filename')";

            if ($conn->query($sql) === TRUE) {
                echo "File information saved to database!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
