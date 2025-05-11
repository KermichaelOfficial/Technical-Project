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

// Fetch library content
$articles = $conn->query("SELECT * FROM library_articles ORDER BY created_at DESC");
$videos = $conn->query("SELECT * FROM library_videos ORDER BY created_at DESC");
$images = $conn->query("SELECT * FROM library_images ORDER BY created_at DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Library</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 20%;
            background-color: #ffffff;
            padding: 20px;
            border-right: 1px solid #e1e8ed;
        }
        .sidebar a {
            display: block;
            text-decoration: none;
            color: #000;
            padding: 12px;
            margin: 8px 0;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            border: none;
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
        }
        .sidebar a:hover {
            background-color: #1da1f2;
            color: white;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            background-color: #1da1f2;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .article, .video, .image {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #e1e8ed;
        }
        .video iframe {
            width: 100%;
            height: 200px;
            border-radius: 5px;
        }
        .image img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
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
        body.dark-mode .article, body.dark-mode .video, body.dark-mode .image {
            background-color: #1e1e1e;
            border-color: #333;
        }
        .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
}

.modal-content {
    max-width: 90%;
    max-height: 90%;
    border-radius: 5px;
}

.close {
    position: absolute;
    top: 15px;
    right: 30px;
    color: white;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
}
    </style>
    <script>
    function openModal(imageSrc) {
    document.getElementById("imageModal").style.display = "flex";
    document.getElementById("fullImage").src = imageSrc;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>
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
        <a href="index.php">Home</a>
        <a href="create_content.php">Create Content</a>
        <a href="#">Shortlist</a>
        <a href="#">Notices</a>
        <a href="#">Messages</a>
        <a href="library.php">Library</a>
        <a href="profile.php">Profile</a>
        <a href="market.php">Live Market</a>
        <a href="#">More</a>
    </div>

    <div class="main-content">
        <!-- Display Investment Articles -->
        <div class="section">
            <h2>📚 Investment Articles</h2>
            <?php while ($article = $articles->fetch_assoc()): ?>
                <div class="article">
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                    <small>By: <?php echo htmlspecialchars($article['author']); ?> | <?php echo $article['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Display Investment Videos -->
        <div class="section">
            <h2>🎥 Investment Videos</h2>
            <?php while ($video = $videos->fetch_assoc()): ?>
                <div class="video">
                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                    <iframe src="<?php echo htmlspecialchars($video['video_url']) . (strpos($video['video_url'], '?') !== false ? '&' : '?') . 'autoplay=0'; ?>" frameborder="0" allowfullscreen></iframe>
                    <small>Uploaded by: <?php echo htmlspecialchars($video['uploaded_by']); ?> | <?php echo $video['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        </div>

       <!-- Display Investment Images -->
<div class="section">
    <h2>📸 Investment Images</h2>
    <?php while ($image = $images->fetch_assoc()): ?>
        <div class="image">
            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                 alt="Investment Image"
                 onclick="openModal('<?php echo htmlspecialchars($image['image_url']); ?>')">
            <small>Uploaded by: <?php echo htmlspecialchars($image['uploaded_by']); ?> | <?php echo $image['created_at']; ?></small>
        </div>
    <?php endwhile; ?>
</div>
    </div>
</div>
<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="fullImage">
</div>
</body>
</html>
