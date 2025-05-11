<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $requestId = intval($_POST['id']);

    // Get request details
    $query = "SELECT * FROM requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    
    if ($request) {
        // Insert into done_requests
        $insertQuery = "INSERT INTO done_requests (username, company_name, industry, description, investment_type, logo_url, email, address, phone, status, submitted_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Completed', ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssssssss",
            $request['username'], 
            $request['company_name'], 
            $request['industry'], 
            $request['description'], 
            $request['investment_type'], 
            $request['logo_url'], 
            $request['email'], 
            $request['address'], 
            $request['phone'], 
            $request['submitted_at']
        );
        $insertStmt->execute();

        // Remove from requests
        $deleteQuery = "DELETE FROM requests WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $requestId);
        $deleteStmt->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Request not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>
