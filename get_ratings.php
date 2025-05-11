<?php
session_start();

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // Get average rating for the post
    $stmt = $conn->prepare("SELECT COALESCE(AVG(rating), 0) as avg_rating FROM ratings WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($avg_rating);
    $stmt->fetch();
    $stmt->close();

    $user_rating = null;
    $has_rated = false;

    // If user is logged in, check if they have rated this post
    if ($username) {
        $stmt = $conn->prepare("SELECT rating FROM ratings WHERE post_id = ? AND username = ?");
        $stmt->bind_param("is", $post_id, $username);
        $stmt->execute();
        $stmt->bind_result($user_rating);
        if ($stmt->fetch()) {
            $has_rated = true;
        }
        $stmt->close();
    }

    echo json_encode([
        "average" => round($avg_rating, 1),
        "user_rating" => $user_rating,
        "can_rate" => !$has_rated
    ]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}

$conn->close();
?>
