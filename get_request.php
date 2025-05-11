<?php
$conn = new mysqli("localhost", "root", "", "investus");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$request_id = intval($_GET['id']);
$sql = "SELECT * FROM requests WHERE id = $request_id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo "<strong>Company:</strong> " . $row['company_name'] . "<br>";
    echo "<strong>Email:</strong> " . $row['email'] . "<br>";
    echo "<strong>Address:</strong> " . $row['address'] . "<br>";
    echo "<strong>Phone:</strong> " . $row['phone'] . "<br>";
    echo "<strong>Description:</strong> " . $row['description'] . "<br>";

    // Check if logo_url exists and is not empty
    if (!empty($row['logo_url'])) {
        // Construct the full path to the logo in the uploads directory
        $logo_path = "uploads/" . $row['logo_url'];

        // Check if the file exists in the uploads directory
        if (file_exists($logo_path)) {
            // Provide the logo as a downloadable link
            echo "<strong>Company Logo:</strong> <a href='" . $logo_path . "' download>Download Logo</a><br>";
        } else {
            // If the logo file is not found, display an error message
            echo "<p><strong>Error:</strong> Logo file not found at " . $logo_path . "</p>";
        }
    } else {
        echo "<p><strong>No logo available for this company.</strong></p>";
    }
}

$conn->close();
?>
