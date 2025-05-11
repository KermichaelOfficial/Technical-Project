<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = trim($_POST['company_name']);
    $industry = trim($_POST['industry']);
    $description = trim($_POST['description']);
    $investment_type = trim($_POST['investment_type']);
    $logo_url = trim($_POST['logo_url']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "investus");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into requests table
    $stmt = $conn->prepare("INSERT INTO requests (username, company_name, industry, description, investment_type, logo_url, email, address, phone, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssssssss", $_SESSION['username'], $company_name, $industry, $description, $investment_type, $logo_url, $email, $address, $phone);

    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully!'); window.location.href='Bbrowse.php';</script>";
    } else {
        echo "<script>alert('Error submitting request. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Investment Request</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body { 
    font-family: Arial, sans-serif; 
    margin: 0; 
    padding: 0; 
    background-color: #f5f8fa; 
}

.container { 
    max-width: 600px; 
    margin: 40px auto; 
    background-color: #ffffff; 
    padding: 20px; 
    border-radius: 10px; 
    border: 1px solid #e1e8ed; 
    text-align: center; 
}

input, select, textarea { 
    width: 100%; 
    padding: 10px; 
    margin: 10px 0; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
    box-sizing: border-box; 
}

textarea {
    resize: vertical; 
}

.submit-btn { 
    background-color: #1da1f2; 
    color: #ffffff; 
    padding: 10px; 
    border: none; 
    border-radius: 5px; 
    font-size: 16px; 
    cursor: pointer; 
}

.submit-btn:hover { 
    background-color: #0d95e8; 
}

body.dark-mode {
    background-color: #121212;
    color: #e0e0e0;
}

.dark-mode .container {
    background-color: #1e1e1e;
    color: #e0e0e0;
    border-color: #333;
}

.dark-mode .notification {
    background-color: #1e1e1e;
    border-bottom: 1px solid #333;
}

.dark-mode .notification:hover {
    background-color: #333;
}

.dark-mode .popup {
    background-color: #1e1e1e;
    color: #e0e0e0;
    border-color: #333;
}

.dark-mode .popup button {
    background-color: #333;
    color: #e0e0e0;
    border: 1px solid #555;
}

.dark-mode .popup .close-btn { 
    background-color: red; 
}

.dark-mode .popup .go-btn { 
    background-color: #1da1f2; 
}

.dark-mode .popup .thanks-btn { 
    background-color: green; 
}

.dark-mode-toggle {
    background-color: #1da1f2;
    color: #ffffff;
    border: none;
    padding: 10px;
    border-radius: 20px;
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 20px;
}

.dark-mode-toggle:hover {
    background-color: #0d95e8;
}

    </style>
    <link rel="icon" type="image/png" href="media/Logo.png">

<script>
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('dark-mode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }

            window.toggleDarkMode = function() {
                document.body.classList.toggle('dark-mode');
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('dark-mode', 'enabled');
                } else {
                    localStorage.removeItem('dark-mode');
                }
            };
        });
    </script>

</head>
<body>
    <div class="container">
        <h1>Submit an Investment Request</h1>
        <form method="POST">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required>

            <label for="industry">Select Industry:</label>
            <select name="industry" id="industry" required>
            <option value="Aerospace & Defense">Aerospace & Defense</option>
            <option value="Airlines">Airlines</option>
            <option value="Automotive">Automotive</option>
            <option value="Banking">Banking</option>
            <option value="Biotech">Biotech</option>
            <option value="Cannabis">Cannabis</option>
            <option value="Energy">Energy</option>
            <option value="Food & Beverage">Food & Beverage</option>
            <option value="Health Care">Health Care</option>
            <option value="Internet">Internet</option>
            <option value="Media">Media</option>
            <option value="Metals & Mining">Metals & Mining</option>
            <option value="Pharma">Pharma</option>
            <option value="Real Estate/Construction">Real Estate/Construction</option>
            <option value="Retail">Retail</option>
            <option value="Software">Software</option>
            <option value="Telecom">Telecom</option>
            <option value="Technology">Technology</option>
            </select>

            <label for="description">Company Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="investment_type">Choose Investment Type:</label>
            <select name="investment_type" id="investment_type" required>
            <option value="stocks">Stocks: Own part of a company with potential growth and dividends</option>
            <option value="bonds">Bonds: Low-risk loans to companies or governments with steady returns</option>
            <option value="real_estate">Real Estate: Property investment with income through rent or appreciation</option>
            <option value="mutual_funds">Mutual Funds: Diversified portfolio managed by professionals</option>
            <option value="etfs">ETFs: Track market indices, tradeable like stocks</option>
            <option value="commodities">Commodities: Investment in raw materials, a hedge against inflation</option>
            <option value="cryptocurrencies">Cryptocurrencies: High-risk digital assets with high-reward potential</option>
            </select>

            <label for="logo_url">Upload Company Logo:</label>
            <input type="file" name="logo_url" id="logo_url" accept="image/*" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" required>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>