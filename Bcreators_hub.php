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
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// Fetch the business user's email from the database
$stmt = $conn->prepare("SELECT email FROM business_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$business_user_data = $result->fetch_assoc();
$businessUserEmail = $business_user_data['email']; 

// Handle "I'm Interested" action
if (isset($_POST['interested']) && isset($_POST['id'])) {
    $post_id = $_POST['id'];
    $business_user = $_SESSION['username'];

    // Get post details
    $stmt = $conn->prepare("SELECT username, idea_text FROM creators_hub_posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $post_user = $post['username'];

    // Send email to the user
    $to = $post_user; 
    $subject = "A Business User is Interested in Your Idea";
    $message = "Hello, \n\nA business user, {$business_user}, has expressed interest in your idea: \n\n{$post['idea_text']}\n\nYou can reach out to them at: {$businessUserEmail} for further negotiations.";
    $headers = "From: no-reply@investus.com";

    mail($to, $subject, $message, $headers); 

    // Redirect to avoid form resubmission
    header("Location: creators_hub.php");
    exit();
}

// Fetch all posts
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
.im-interested-btn {
    background-color: #4CAF50; 
    color: white; 
    padding: 12px 24px; 
    font-size: 16px; 
    font-weight: bold; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    transition: background-color 0.3s ease; 
}
.im-interested-btn:hover {
    background-color: #45a049; 
}


.im-interested-btn:focus {
    outline: none; 
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); 
}
.im-interested-btn:disabled {
    background-color: #cccccc; 
    color: #666666;
    cursor: not-allowed; 
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
    <a href="Bindex.php">Home</a>
            <a href="create_content.php">Create Content</a>
            <a href="#">Notifications</a>
            <a href="market.php">Live Market</a>
            <a href="library.php">Library</a>
            <a href="Bprofile.php">Profile</a>
            <a href="more.php">More</a>
    </div>

        <div class="main-content">
            <h2>💡 Ideas Shared by Users</h2>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post">
                    <img src="<?php echo !empty($post['profile_picture']) ? htmlspecialchars($post['profile_picture']) : 'media/default_pfp.png'; ?>" alt="Profile Picture" class="profile-pic">
                    <div class="post-content">
                        <p><strong><?php echo htmlspecialchars($post['username']); ?>:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($post['idea_text'])); ?></p>

                        <?php if ($post['file_type'] === 'image'): ?>
                            <img src="<?php echo htmlspecialchars($post['file_url']); ?>" alt="User Image">
                        <?php elseif ($post['file_type'] === 'video'): ?>
                            <video controls>
                                <source src="<?php echo htmlspecialchars($post['file_url']); ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>

                        <small>Posted on: <?php echo $post['created_at']; ?></small>

                        <form method="POST" action="interest.php">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <input type="hidden" name="business_user_email" value="<?php echo $businessUserEmail; ?>">
    <button type="submit" class="im-interested-btn">I'm Interested</button>
</form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        document.querySelectorAll('.im-interested-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); 
        if (confirm("Are you sure you want to declare interest in this idea?")) {
            // If confirmed, submit the form
            this.closest('form').submit();
        }
    });
});
        </script>
</body>
</html>
