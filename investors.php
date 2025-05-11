<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investors</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
            color: #333333;
            transition: background-color 0.3s, color 0.3s;
        }
        .container {
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .key-industries {
            margin-top: 30px;
        }
        .industries-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            gap: 20px;
            max-width: 600px;
            margin: 0 auto; 
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
        }
        .industry {
            background-color: #1da1f2;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .industry:hover {
            background-color: #0d95e8;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .industries-grid {
            background-color: #1e1e1e;
            border-color: #333333;
        }
        body.dark-mode .industry {
            background-color: #333333;
        }
        body.dark-mode .industry:hover {
            background-color: #444444;
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
        body.dark-mode h1 {
    color: #ffffff; 
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Investor Opportunities</h1>

        <div class="key-industries">
            <h2 style="text-align:center;">Key Industries</h2>
            <div class="industries-grid">
                <a href="industries/aerospace_defense.php" class="industry">Aerospace & Defense</a>
                <a href="industries/airlines.php" class="industry">Airlines</a>
                <a href="industries/automotive.php" class="industry">Automotive</a>
                <a href="industries/banking.php" class="industry">Banking</a>
                <a href="industries/biotech.php" class="industry">Biotech</a>
                <a href="industries/cannabis.php" class="industry">Cannabis</a>
                <a href="industries/energy.php" class="industry">Energy</a>
                <a href="industries/food.php" class="industry">Food & Beverage</a>
                <a href="industries/health.php" class="industry">Health Care</a>
                <a href="industries/internet.php" class="industry">Internet</a>
                <a href="industries/media.php" class="industry">Media</a>
                <a href="industries/metals.php" class="industry">Metals & Mining</a>
                <a href="industries/pharma.php" class="industry">Pharma</a>
                <a href="industries/real.php" class="industry">Real Estate/Construction</a>
                <a href="industries/retail.php" class="industry">Retail</a>
                <a href="industries/software.php" class="industry">Software</a>
                <a href="industries/telecom.php" class="industry">Telecom</a>
                <a href="industries/tech.php" class="industry">Technology</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Dark mode initialization
            if (localStorage.getItem('dark-mode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }

            // Toggle dark mode function
            window.toggleDarkMode = function() {
                document.body.classList.toggle('dark-mode');
                // Save the current state to localStorage
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('dark-mode', 'enabled');
                } else {
                    localStorage.removeItem('dark-mode');
                }
            };
        });
    </script>
</body>
</html>
