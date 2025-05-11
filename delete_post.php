<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

if (!isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$post_id = $_GET['id'];

// Database connection
$servername = "localhost";
$dbname = "investus";
$dbusername = "root"; 
$dbpassword = ""; 

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the post
$stmt = $conn->prepare("DELETE FROM contents WHERE id = ? AND username = ?");
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: profile.php");
exit();
?>
