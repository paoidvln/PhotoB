<?php
$data = json_decode(file_get_contents("php://input"), true);
$image = $data['image'] ?? '';

if (!$image) {
  http_response_code(400);
  exit("No image data.");
}

$imgData = explode(',', $image);
$imgBinary = base64_decode($imgData[1]);
$filename = 'uploads/photo_' . time() . '.png';
file_put_contents($filename, $imgBinary);

// Save to DB
$conn = new mysqli("localhost", "user", "pass", "photob");
if ($conn->connect_error) {
  http_response_code(500);
  exit("DB error");
}
$stmt = $conn->prepare("INSERT INTO photos (filename) VALUES (?)");
$stmt->bind_param("s", $filename);
$stmt->execute();
$stmt->close();
$conn->close();

echo "Photo uploaded successfully!";
?>
