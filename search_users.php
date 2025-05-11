<?php
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

// Get the query from the URL
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if (!empty($query)) {
    $searchTerm = "%" . $query . "%";
    $suggestions = [];

    // Search in users table
    $stmt = $conn->prepare("SELECT username FROM users WHERE username LIKE ? LIMIT 5");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($username);

    while ($stmt->fetch()) {
        $suggestions[] = $username;
    }
    $stmt->close();

    // Search in business_users table
    $stmt = $conn->prepare("SELECT username FROM business_users WHERE username LIKE ? LIMIT 5");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($username);

    while ($stmt->fetch()) {
        $suggestions[] = $username;
    }
    $stmt->close();

    // Display suggestions
    if (count($suggestions) > 0) {
        foreach ($suggestions as $suggestion) {
            echo '<div class="suggestion-item"><a href="view_profile.php?username=' . urlencode($suggestion) . '">' . htmlspecialchars($suggestion) . '</a></div>';
        }
    } else {
        echo '<div>No users found</div>';
    }
}

$conn->close();
?>