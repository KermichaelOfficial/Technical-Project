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
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More Options</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        .dark-mode-toggle {
            background-color: #1da1f2;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .dark-mode-toggle:hover {
            background-color: #0d95e8;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
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
    <div class="container">
        <h2>More Options</h2>
        <button class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    </div>
</body>
</html>
