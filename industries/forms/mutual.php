<?php
session_start();
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../../index.php?session_timeout=1");
    exit();
}
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$investmentTypes = [
    'stock' => 'Stocks',
    'bond' => 'Bonds',
    'real_estate' => 'Real Estate',
    'mutual' => 'Mutual Funds',
    'etf' => 'ETFs',
    'commodities' => 'Commodities',
    'crypto' => 'Cryptocurrencies'
];

$page = basename(__FILE__, ".php");
$title = $investmentTypes[$page] ?? 'Investment';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> Investment</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title); ?> Investment</h1>
        <form action="process_investment.php" method="POST">
            <input type="hidden" name="investmentType" value="<?php echo htmlspecialchars($page); ?>">
            <label for="amount">Investment Amount</label>
            <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
            <label for="duration">Investment Duration</label>
            <select id="duration" name="duration" required>
                <option value="1 year">1 Year</option>
                <option value="3 years">3 Years</option>
                <option value="5 years">5 Years</option>
            </select>
            <input type="submit" class="submit-btn" value="Invest Now">
        </form>
    </div>
</body>
</html>
