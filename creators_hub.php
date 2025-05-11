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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idea_text = isset($_POST['idea_text']) ? trim($_POST['idea_text']) : '';
    $file_url = '';
    $file_type = '';

    if (!empty($_FILES['file']['name'])) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedVideoExts = ['mp4', 'avi', 'mov', 'mkv'];

        if (in_array($fileExt, $allowedImageExts)) {
            $file_type = 'image';
        } elseif (in_array($fileExt, $allowedVideoExts)) {
            $file_type = 'video';
        }

        if ($file_type) {
            $newFileName = uniqid('', true) . '.' . $fileExt;
            $fileDestination = 'uploads/creators_hub/' . $newFileName;
            move_uploaded_file($fileTmpName, $fileDestination);
            $file_url = $fileDestination;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO creators_hub_posts (username, idea_text, file_url, file_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $idea_text, $file_url, $file_type);
    $stmt->execute();
    $stmt->close();
}

// Fetch all posts along with profile pictures
$posts = $conn->query("
    SELECT chp.*, u.profile_picture 
    FROM creators_hub_posts chp 
    JOIN users u ON chp.username = u.username 
    ORDER BY chp.created_at DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creators Hub</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f5f8fa; }
        .container { display: flex; height: 100vh; }
        .main-content { flex: 1; padding: 20px; overflow-y: auto; }
        .toggle-upload { cursor: pointer; padding: 10px; background: #1da1f2; color: white; text-align: center; border-radius: 5px; }
        .upload-section { display: none; padding: 20px; background: white; border-radius: 5px; margin-bottom: 20px; }
        .post {
    display: flex;
    align-items: flex-start; 
    gap: 10px; 
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    background: white;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.post .profile-pic {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0; 
}

.post-content {
    flex-grow: 1; 
}

.post-content p {
    margin: 5px 0;
    word-wrap: break-word; 
}

.post img, .post video {
    max-width: 100%;
    border-radius: 5px;
    margin-top: 10px;
}

small {
    color: #666;
    font-size: 12px;
}

body.dark-mode {
    background-color: #121212;
    color: #ffffff;
}
body.dark-mode .sidebar {
    background-color: #1e1e1e;
}
body.dark-mode .sidebar a {
    background-color: #333;
    color: #ffffff;
}
body.dark-mode .sidebar a:hover {
    background-color: #1da1f2;
    color: white;
}
body.dark-mode .main-content {
    background-color: #121212;
    color: #ffffff;
}
body.dark-mode .post {
    background-color: #1e1e1e;
    color: #ffffff;
    box-shadow: 0px 2px 5px rgba(255, 255, 255, 0.1);
}
body.dark-mode .post p {
    color: #ffffff;
}
body.dark-mode small {
    color: #b0b0b0;
}
body.dark-mode .upload-section {
    background-color: #1e1e1e;
}
body.dark-mode textarea, 
body.dark-mode input, 
body.dark-mode button {
    background-color: #333;
    color: #ffffff;
    border: 1px solid #555;
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
    <script>
        function toggleUpload() {
            var uploadSection = document.getElementById('uploadSection');
            uploadSection.style.display = (uploadSection.style.display === 'none' || uploadSection.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>
<body>
<div class="container">
    <div class="sidebar">
    <a href="Uindex.php">Home</a>
            <a href="create_content.php">Create Content</a>
            <a href="#">Notifications</a>
            <a href="market.php">Live Market</a>
            <a href="library.php">Library</a>
            <a href="profile.php">Profile</a>
            <a href="more.php">More</a>
    </div>
    <div class="main-content">
        <div class="toggle-upload" onclick="toggleUpload()">➕ Upload Your Idea</div>
        <div id="uploadSection" class="upload-section">
            <form action="creators_hub.php" method="POST" enctype="multipart/form-data">
                <textarea name="idea_text" placeholder="Describe your idea..."></textarea>
                <input type="file" name="file" accept="image/*,video/*">
                <button type="submit">Upload Idea</button>
            </form>
        </div>
        
        <h2>💡 Ideas Shared by Users</h2>
<?php while ($post = $posts->fetch_assoc()): ?>
    <div class="post">
        <!-- Profile Picture -->
        <img src="<?php echo !empty($post['profile_picture']) ? htmlspecialchars($post['profile_picture']) : 'media/default_pfp.png'; ?>" 
             alt="Profile Picture" class="profile-pic">

        <div class="post-content">
            <!-- Username and Idea -->
            <p><strong><?php echo htmlspecialchars($post['username']); ?>:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($post['idea_text'])); ?></p>

            <!-- Display Image or Video -->
            <?php if ($post['file_type'] === 'image'): ?>
                <img src="<?php echo htmlspecialchars($post['file_url']); ?>" alt="User Image">
            <?php elseif ($post['file_type'] === 'video'): ?>
                <video controls>
                    <source src="<?php echo htmlspecialchars($post['file_url']); ?>" type="video/mp4">
                </video>
            <?php endif; ?>

            <!-- Timestamp -->
            <small>Posted on: <?php echo $post['created_at']; ?></small>
        </div>
    </div>
<?php endwhile; ?>


    </div>
</div>
</body>
</html>
