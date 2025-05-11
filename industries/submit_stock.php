<?php
session_start();
if (!isset($_SESSION['business_user_id'])) { 
    header("Location: login.php");
    exit();
}

require 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_user_id = $_SESSION['business_user_id']; 
    $company_name = $_POST['company_name'];
    $industry = $_POST['industry'];
    $investment_type = $_POST['investment_type'];
    $description = $_POST['description'];
    $logo_url = $_POST['logo_url'];

    $stmt = $conn->prepare("INSERT INTO investments (business_user_id, company_name, industry, investment_type, description, logo_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $business_user_id, $company_name, $industry, $investment_type, $description, $logo_url);

    if ($stmt->execute()) {
        header("Location: investments.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
