<?php
session_start();
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only airlines investments
$sql = "SELECT * FROM admin_investments WHERE company_type = 'Metals & Mining' ORDER BY id DESC"; 
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airlines Investment Display</title>
    <link rel="icon" type="image/png" href="../media/Logo.png">
    <style>
        body.dark-mode h1 {
    color: #ffffff; 
}
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
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            position: relative;
            text-align: center;
        }
        .industry-logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 100px;  
            height: 80px; 
            object-fit: cover;
        }
        h1, h2 {
            text-align: center;
            color: #333333;
        }
        .investment-info p {
            font-size: 16px;
            color: #333333;
        }
        .invest-button {
            display: block;
            width: 20%;
            margin-top: 20px;
            padding: 10px;
            background-color: #1da1f2;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .invest-button:hover {
            background-color: #0d95e8;
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
        body.dark-mode .sidebar {
            background-color: #1e1e1e;
        }
        body.dark-mode .sidebar a {
            background-color: #333;
            color: #e0e0e0;
        }
        body.dark-mode .sidebar a:hover {
            background-color: #1da1f2;
            color: white;
        }
        body.dark-mode .main-content {
            background-color: #121212;
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

<h1>Metals & Mining Investment Opportunities</h1>

<?php if ($result->num_rows > 0): ?>
    <?php while ($investment = $result->fetch_assoc()): ?>
        <div class="container">
            <img src="<?php echo htmlspecialchars($investment['image_url']); ?>" class="industry-logo" alt="Investment Image">
            <div class="investment-info">
                <h2><?php echo htmlspecialchars($investment['header_text']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($investment['description'])); ?></p>
                <p><strong>Investment Type:</strong> <?php echo ucfirst(htmlspecialchars($investment['investment_type'])); ?></p>
            </div>
            <a href="investment-process.php?investment_type=<?php echo urlencode($investment['investment_type']); ?>" class="invest-button">Invest Now</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No Metals & Mining investments available. Please check back later.</p>
<?php endif; ?>

</body>
</html>
