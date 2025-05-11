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
    header("Location: Alogin.php");
    exit();
}
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = ""; 

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve profile picture
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Retrieve contents
$stmt = $conn->prepare("SELECT username, text, file_url, file_type FROM contents WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($content_username, $content_text, $content_file_url, $content_file_type);
$contents = [];
while ($stmt->fetch()) {
    $contents[] = [
        'username' => $content_username,
        'text' => $content_text,
        'file_url' => $content_file_url,
        'file_type' => $content_file_type
    ];
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse</title>
    <link rel="icon" type="image/png" href="media/Logo.png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
            overflow: hidden;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar, .right-sidebar {
            background-color: #ffffff;
            padding: 20px;
            flex-shrink: 0;
        }
        .sidebar {
            width: 20%;
            border-right: 1px solid #e1e8ed;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .sidebar a {
            text-decoration: none;
            color: #000000;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
            border: 1px solid #e1e8ed;
        }
        .sidebar a:hover {
            background-color: #e1e8ed;
            color: #000000;
        }
        .main-content {
            width: 60%;
            padding: 20px;
            overflow-y: scroll;
        }
        .tweet {
            background-color: #ffffff;
            padding: 10px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .right-sidebar {
            width: 20%;
            border-left: 1px solid #e1e8ed;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 50%;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #e1e8ed;
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
        .profile-picture {
            border-radius: 50%;
            margin-right: 10px;
        }
        .tweet-header {
            display: flex;
            align-items: center;
        }
        .profile-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .sidebar, body.dark-mode .right-sidebar {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        body.dark-mode .sidebar a {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333333;
        }
        body.dark-mode .sidebar a:hover {
            background-color: #333333;
            color: #e0e0e0;
        }
        body.dark-mode .main-content {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .tweet {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333333;
        }
        body.dark-mode .search-bar input {
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #555555;
        }
        .tweet-buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 10px;
}

.tweet-buttons button {
    padding: 8px 15px;
    border: none;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    outline: none;
}

.tweet-buttons button:nth-child(1) {
    background-color: #ff4d4d;
    color: white;
}

.tweet-buttons button:nth-child(1):hover {
    background-color: #e60000;
}

.tweet-buttons button:nth-child(2) {
    background-color: #1da1f2;
    color: white;
}

.tweet-buttons button:nth-child(2):hover {
    background-color: #0d95e8;
}

.tweet-buttons button:nth-child(3) {
    background-color: #28a745;
    color: white;
}

.tweet-buttons button:nth-child(3):hover {
    background-color: #218838;
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
        <div class="sidebar">
            <a href="Aindex.php">Home</a>
            <a href="#">Notifications</a>
            <a href="Arequest.php">Requests</a>
            <a href="market.php">Live Market</a>
            <a href="Alibrary.php">Library</a>
            <a href="profile.php">Profile</a>
            <a href="more.php">More</a>
        </div>
        <div class="main-content">
    <div class="search-bar">
        <input type="text" placeholder="Search">
    </div>
    <div class="tweets" id="tweets">
                <?php foreach ($contents as $content): ?>
                    <div class="tweet">
                        <div class="tweet-header">
                            <a href="profile.php?user=<?php echo htmlspecialchars($content['username']); ?>" class="profile-link">
                                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture" width="40" height="40">
                                <h3><?php echo htmlspecialchars($content['username']); ?></h3>
                            </a>
                        </div>
                        <p><?php echo htmlspecialchars($content['text']); ?></p>
                        <?php if (!empty($content['file_url'])): ?>
                            <?php $file_ext = pathinfo($content['file_url'], PATHINFO_EXTENSION); ?>
                            <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?php echo htmlspecialchars($content['file_url']); ?>" alt="Image" style="max-width: 100%; cursor: pointer;">
                            <?php elseif (in_array($file_ext, ['mp4', 'avi', 'mov'])): ?>
                                <video controls style="max-width: 100%; cursor: pointer;">
                                    <source src="<?php echo htmlspecialchars($content['file_url']); ?>" type="video/<?php echo htmlspecialchars($file_ext); ?>">
                                    Your browser does not support the video tag.
                                </video>
                            <?php else: ?>
                                <p>Unsupported file type: <?php echo htmlspecialchars($file_ext); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p>No media available</p>
                        <?php endif; ?>
                        <div class="tweet-buttons">
                            <button>Like</button>
                            <button>Follow</button>
                            <button>Rate</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
