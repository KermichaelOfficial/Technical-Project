<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the investment type from the URL parameter
$investment_type = isset($_GET['investment_type']) ? $_GET['investment_type'] : '';

// Validate the investment type
$valid_investment_types = [
    'stocks' => 'forms/stock.php',
    'bonds' => 'forms/bond.php',
    'real_estate' => 'forms/real_estate.php',
    'mutual' => 'forms/mutual.php',
    'etf' => 'forms/etf.php',
    'commodities' => 'forms/commodities.php',
    'crypto' => 'forms/crypto.php'
];

// Check if the investment type is valid and redirect
if (array_key_exists(strtolower($investment_type), $valid_investment_types)) {
    // Redirect to the respective form
    header("Location: " . $valid_investment_types[strtolower($investment_type)]);
    exit();
} else {
    // If the investment type is invalid, redirect to a default or error page
    echo "Invalid investment type.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Process</title>
    <link rel="icon" type="image/png" href="../media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
        }
        .container {
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333333;
        }
        .investment-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e1e8ed;
            border-radius: 20px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        }
        .investment-form label {
            display: block;
            font-size: 16px;
            color: #333333;
            margin-bottom: 5px;
        }
        .investment-form input[type="number"],
        .investment-form input[type="submit"],
        .investment-form select {
            width: 100%;
            padding: 12px; 
            margin: 10px 0;
            border-radius: 20px; 
            border: 1px solid #e1e8ed;
            font-size: 16px;
            transition: border-color 0.3s; 
        }
        .investment-form input[type="number"]:focus,
        .investment-form select:focus {
            border-color: #007bff; 
            outline: none; 
        }
        .investment-form input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
        }
        .investment-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .investment-form {
            background-color: #1e1e1e;
            border: 1px solid #333333;
        }
        body.dark-mode .investment-form input[type="number"],
        body.dark-mode .investment-form input[type="submit"],
        body.dark-mode .investment-form select {
            background-color: #1e1e1e; 
            color: #e0e0e0; 
            border: 1px solid #333333; 
        }
        .dark-mode-toggle {
            margin: 20px;
            padding: 10px 20px;
            background-color: #333333;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .dark-mode-toggle:hover {
            background-color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Investment Process</h1>
        <div class="investment-form">
            <label for="investmentType">Select Your Investment Type</label>
            <select id="investmentType" name="investmentType" required onchange="redirectToInvestmentPage()">
                <option value="" disabled>Select investment type</option>
                <option value="forms/stock.php">Stocks: Own part of a company with potential growth and dividends</option>
                <option value="forms/bond.php">Bonds: Low-risk loans to companies or governments with steady returns</option>
                <option value="forms/real_estate.php">Real Estate: Property investment with income through rent or appreciation</option>
                <option value="forms/mutual.php">Mutual Funds: Diversified portfolio managed by professionals</option>
                <option value="forms/etf.php">ETFs: Track market indices, tradeable like stocks</option>
                <option value="forms/commodities.php">Commodities: Investment in raw materials, a hedge against inflation</option>
                <option value="forms/crypto.php">Cryptocurrencies: High-risk digital assets with high-reward potential</option>
            </select>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if the investment_type is available in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const investmentType = urlParams.get('investment_type');
            
            if (investmentType) {
                const selectElement = document.getElementById('investmentType');
                const options = selectElement.options;

                // Loop through options and select the one that matches the investment_type
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value.includes(investmentType.toLowerCase().replace(/ /g, '_'))) {
                        selectElement.selectedIndex = i;
                        break;
                    }
                }
            }
        });

        function redirectToInvestmentPage() {
            let selectedPage = document.getElementById('investmentType').value;
            if (selectedPage) {
                window.location.href = selectedPage;
            }
        }
    </script>
</body>
</html>