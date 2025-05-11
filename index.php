<?php
session_start();
if (isset($_GET['message']) && $_GET['message'] === 'session_expired') {
    echo '<div class="session-expired-message">Your session has expired due to inactivity. Please log in again.</div>';
}
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investus</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        .popup-message {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #ffcccc;
    color: #b30000;
    padding: 15px 25px;
    font-weight: bold;
    border: 1px solid #b30000;
    border-radius: 0 0 5px 5px;
    z-index: 1000;
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from { top: -50px; opacity: 0; }
    to { top: 0; opacity: 1; }
}


        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            height: 100vh;
            padding-top: 5px; 
        } 
        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }
        .header {
            font-size: 2em;
            margin-bottom: 10px; 
        }
        nav {
            position: relative;
            margin-top: 5px; 
        }
        .nav-item {
            display: inline-block;
            margin: 10px 10px; 
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
        #hero-message {
    transition: opacity 0.5s ease-in-out;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <img src="media/Logo.png" alt="Logo">
            <h1 class="header" id="hero-message">Start your investment journey today</h1>
            </div>
        <nav>
            <div class="nav-item" onclick="toggleSubmenu(event, 'login-menu')">
                Login
                <div id="login-menu" class="submenu">
                    <a href="Alogin.php">Admin</a>
                    <a href="Blogin.php">Business</a>
                    <a href="login.php">User</a>
                </div>
            </div>
            <div class="nav-item" onclick="toggleSubmenu(event, 'register-menu')">
                Register
                <div id="register-menu" class="submenu">
                    <a href="Bregister.php">Business</a>
                    <a href="register.php">User</a>
                </div>
            </div>
        </nav>
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
    <script>
    const messages = [
        "Start your investment journey today",
        "Grow your wealth with Investus",
        "Smart investments start here",
        "Secure your future, invest now!",
        "Join thousands of successful investors",
        "Register your company today!"
    ];

    let currentMessageIndex = 0;
    const messageElement = document.getElementById("hero-message");

    function rotateMessages() {
        currentMessageIndex = (currentMessageIndex + 1) % messages.length;
        messageElement.textContent = messages[currentMessageIndex];
    }

    setInterval(rotateMessages, 7000);
</script>


</body>
<?php if (isset($_GET['session_timeout']) && $_GET['session_timeout'] == 1): ?>
<div class="popup-message" id="session-expired-popup">
    Your session has expired due to inactivity. Please log in again.
</div>
<script>
    setTimeout(() => {
        document.getElementById('session-expired-popup').style.display = 'none';
    }, 5000);
</script>
<?php endif; ?>


</html>
