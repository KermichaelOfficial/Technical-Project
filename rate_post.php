<?php
session_start();

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_id = intval($_POST['post_id']);
    $rating = intval($_POST['rating']);

    $username = $_SESSION['username'] ?? null;
    if (!$username) {
        echo json_encode(["success" => false, "message" => "User not logged in"]);
        exit;
    }

    // Check if user has already rated this post
    $stmt = $conn->prepare("SELECT id FROM ratings WHERE post_id = ? AND username = ?");
    $stmt->bind_param("is", $post_id, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Remove existing rating 
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM ratings WHERE post_id = ? AND username = ?");
        $stmt->bind_param("is", $post_id, $username);
        $stmt->execute();
        $stmt->close();
        $removed = true;
    } else {
        // Insert new rating
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO ratings (post_id, username, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $post_id, $username, $rating);
        $stmt->execute();
        $stmt->close();
        $removed = false;
    }

    // Get updated average rating
    $stmt = $conn->prepare("SELECT COALESCE(AVG(rating), 0) FROM ratings WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($avg_rating);
    $stmt->fetch();
    $stmt->close();

    echo json_encode([
        "success" => true,
        "removed" => $removed,
        "average" => round($avg_rating, 1)
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>
