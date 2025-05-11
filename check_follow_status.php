<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["following" => false]);
    exit();
}

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    echo json_encode(["following" => false]);
    exit();
}

$follower = $_SESSION['username'];
$following = isset($_GET['following']) ? trim($_GET['following']) : '';

if (empty($following)) {
    echo json_encode(["following" => false]);
    exit();
}

// Check if user exists in followers table
$stmt = $conn->prepare("SELECT id FROM followers WHERE follower = ? AND following = ?");
$stmt->bind_param("ss", $follower, $following);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["following" => true]);
} else {
    echo json_encode(["following" => false]);
}

$stmt->close();
$conn->close();
?>
