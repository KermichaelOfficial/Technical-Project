<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$follower = $_SESSION['username'];
$following = isset($_POST['following']) ? trim($_POST['following']) : '';

if (empty($following)) {
    echo json_encode(["status" => "error", "message" => "No user specified"]);
    exit();
}

// Check if user exists in either 'users' or 'business_users'
$user_exists = false;
$tables = ['users', 'business_users'];
foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT username FROM $table WHERE username = ?");
    $stmt->bind_param("s", $following);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $user_exists = true;
        break;
    }
    $stmt->close();
}

if (!$user_exists) {
    echo json_encode(["status" => "error", "message" => "User does not exist"]);
    exit();
}

// Check if already following
$stmt = $conn->prepare("SELECT id FROM followers WHERE follower = ? AND following = ?");
$stmt->bind_param("ss", $follower, $following);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Unfollow the user
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower = ? AND following = ?");
    $stmt->bind_param("ss", $follower, $following);
    $stmt->execute();
    echo json_encode(["status" => "unfollowed"]);
} else {
    // Follow the user
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO followers (follower, following) VALUES (?, ?)");
    $stmt->bind_param("ss", $follower, $following);
    $stmt->execute();
    echo json_encode(["status" => "followed"]);
}

$stmt->close();
$conn->close();
?>
