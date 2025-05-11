<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $username = $_SESSION['username'];

    // Move notification to dismissed_requests table
    $sqlCopy = "INSERT INTO dismissed_requests SELECT * FROM done_requests WHERE id = ? AND username = ?";
    $stmtCopy = $conn->prepare($sqlCopy);
    $stmtCopy->bind_param("is", $id, $username);
    
    if ($stmtCopy->execute()) {
        // Delete notification from done_requests
        $sqlDelete = "DELETE FROM done_requests WHERE id = ? AND username = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("is", $id, $username);
        $stmtDelete->execute();
        
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to move notification"]);
    }
    
    $stmtCopy->close();
    $stmtDelete->close();
}

$conn->close();
?>
