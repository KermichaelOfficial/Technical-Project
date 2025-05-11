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

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_symbol = $_POST['stock_symbol'];
    $shares = (int) $_POST['shares'];
    $price_per_share = (float) $_POST['price_per_share'];
    $buyer = $_POST['buyer'];
    $total_amount = $shares * $price_per_share;
    $seller = $_SESSION['username'];
    $sale_date = date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO stock_sales (seller, stock_symbol, shares, price_per_share, total_amount, buyer, sale_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiddss", $seller, $stock_symbol, $shares, $price_per_share, $total_amount, $buyer, $sale_date);
    
    if ($stmt->execute()) {
        $message = "Stock sale recorded successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Sales</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f8fa; text-align: center; }
        .container { max-width: 400px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ddd; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background-color: #1da1f2; color: white; cursor: pointer; }
        button:hover { background-color: #0d95e8; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Stock Sales Form</h2>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="post" action="">
            <input type="text" name="stock_symbol" placeholder="Stock Symbol" required>
            <input type="number" name="shares" placeholder="Number of Shares" required>
            <input type="text" name="price_per_share" placeholder="Price Per Share ($)" required>
            <input type="text" name="buyer" placeholder="Buyer Username" required>
            <button type="submit">Submit Sale</button>
        </form>
    </div>
</body>
</html>
