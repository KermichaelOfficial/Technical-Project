<?php
session_start();
$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

// Create a connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    exit("Unauthorized");
}

$username = $_SESSION['username'];
$content_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$comment_text = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($content_id > 0 && !empty($comment_text)) {
    // Check if the post exists in the contents table
    $stmt = $conn->prepare("SELECT id FROM contents WHERE id = ?");
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Insert the comment
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO comments (post_id, username, comment_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $content_id, $username, $comment_text);
        $stmt->execute();
        $stmt->close();
        echo "Comment added successfully";
    } else {
        echo "Invalid post ID";
    }
} else {
    echo "Invalid input";
}

$conn->close();
?>
