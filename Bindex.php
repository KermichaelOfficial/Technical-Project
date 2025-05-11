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
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } 
        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }
        .header {
            font-size: 2em;
            margin-bottom: 20px;
        }
        nav {
            position: relative;
            margin-top: 20px; 
        }
        .nav-item {
            display: inline-block;
            margin: 20px 10px; 
            padding: 10px 20px;
            color: #ffffff;
            text-decoration: none;
            border: 2px solid #ffffff;
            border-radius: 25px;
            transition: background-color 0.3s, color 0.3s;
            position: relative;
            cursor: pointer;
        }
        .nav-item:hover {
            background-color: #ffffff;
            color: #121212;
        }
        .submenu {
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background-color: #ffffff;
            color: #121212;
            border-radius: 10px;
            display: none;
            flex-direction: column;
            width: 150px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .submenu a {
            display: block;
            padding: 10px;
            color: #121212;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .submenu a:hover {
            background-color: #ddd;
        }
        .submenu.active {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['username'])): ?>
            <div class="hero">
                <img src="media/Logo.png" alt="Logo">
                <h1 class="header">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                <p class="hero-text">Discover the latest trends and connect with investors.</p>
            </div>
            <nav>
                <a href="Bbrowse.php" class="nav-item">Browse</a>
                <a href="Ainvestors.php" class="nav-item">Investors</a>
                <a href="Bcreate_content.php" class="nav-item">Create Content</a>
                <a href="logout.php" class="nav-item">Logout</a>
            </nav>
        <?php else: ?>
            <p>Please log in to access the content.</p>
        <?php endif; ?>
    </div>
    <script>
        function toggleSubmenu(event, menuId) {
            event.stopPropagation(); 
            document.querySelectorAll('.submenu').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.remove('active');
                }
            });
            document.getElementById(menuId).classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.nav-item')) {
                document.querySelectorAll('.submenu').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
