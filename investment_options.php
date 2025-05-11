<?php
include 'config.php'; 

session_start();
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$business_username = $_SESSION['username']; 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_options = $_POST['investments'] ?? [];

    // Delete existing selections for this user 
    $conn->query("DELETE FROM investment_options WHERE business_username = '$business_username'");

    // Insert new selections for the username
    $stmt = $conn->prepare("INSERT INTO investment_options (business_username, investment_type) VALUES (?, ?)");
    foreach ($selected_options as $option) {
        $stmt->bind_param("ss", $business_username, $option);
        $stmt->execute();
    }

    echo "<script>alert('Investment options updated successfully!'); window.location.href='Bbrowse.php';</script>";
}
// Fetch current investment options for display
$selected_options_display = [];
$result = $conn->query("SELECT investment_type FROM investment_options WHERE business_username = '$business_username'");
while ($row = $result->fetch_assoc()) {
    $selected_options_display[] = $row['investment_type'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Options</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: left;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        button {
            display: block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .dark-mode-toggle {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #1da1f2;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

body.dark-mode {
    background-color: #121212;
    color: #e0e0e0;
}

body.dark-mode .container {
    background: #1e1e1e;
    color: #e0e0e0;
}

body.dark-mode table {
    background-color: #1e1e1e;
}

body.dark-mode th, body.dark-mode td {
    color: #e0e0e0;
    border-bottom: 1px solid #444;
}

body.dark-mode a {
    color: #1da1f2;
}

body.dark-mode a:hover {
    color: #ffffff;
    text-decoration: underline;
}

body.dark-mode .delete-btn {
    color: #ff6347;
}

body.dark-mode .delete-btn:hover {
    text-decoration: underline;
}

body.dark-mode h2 {
    color: #ffffff; 
}

body.dark-mode h1 {
    color: #ffffff;
}
body.dark-mode div[style*="position: absolute"] {
    background-color: #1e1e1e;
    color: #e0e0e0;
    box-shadow: 0 0 5px rgba(255,255,255,0.2);
}
body.dark-mode strong,li{
    color:rgb(20, 0, 0); 

}

    </style>

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
<div style="position: absolute; top: 20px; right: 160px; background-color: #ffffff; padding: 10px 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.2); font-size: 14px;">
    <strong>Your Current Options:</strong>
    <ul style="margin: 5px 0 0 0; padding-left: 15px;">
        <?php
        if (empty($selected_options_display)) {
            echo "<li><em>None selected</em></li>";
        } else {
            foreach ($selected_options_display as $option) {
                echo "<li>" . ucfirst(str_replace('_', ' ', $option)) . "</li>";
            }
        }
        ?>
    </ul>
</div>

    <div class="container">
        <h2>Select Your Investment Options</h2>
        <form method="post">
            <?php
            // Define the investment options
            $investment_types = [
                'stock' => 'Stocks',
                'bond' => 'Bonds',
                'real_estate' => 'Real Estate',
                'mutual' => 'Mutual Funds',
                'etf' => 'ETFs',
                'commodities' => 'Commodities',
                'crypto' => 'Cryptocurrencies'
            ];

            // Loop through each investment option and create a checkbox for each
            foreach ($investment_types as $key => $label) {
                echo "<label><input type='checkbox' name='investments[]' value='$key'> $label</label><br>";
            }
            ?>
            <button type="submit">Save Preferences</button>
        </form>
    </div>
</body>
</html>
