<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

$username = $_SESSION['username'];
$viewed_user = isset($_GET['username']) ? $_GET['username'] : $username;

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
    $stmt->bind_param("ss", $target_file, $username);
    $stmt->execute();
    $stmt->close();
}

// Get profile picture
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Get follower count
$stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE following = ?");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$stmt->bind_result($follower_count);
$stmt->fetch();
$stmt->close();

// Get user posts
$posts = [];
$stmt = $conn->prepare("SELECT id, text, file_url, file_type FROM contents WHERE username = ?");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();

// Get list of followers
$followers = [];
$stmt = $conn->prepare("SELECT follower FROM followers WHERE following = ?");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $followers[] = $row['follower'];
}
$stmt->close();

$conn->close();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($viewed_user); ?>'s Profile</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding-top: 50px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e1e8ed;
            padding-bottom: 20px;
        }
        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
            cursor: pointer;
            border: 2px solid #1da1f2;
        }
        .profile-header h2 {
            margin: 0;
        }
        .follow-button {
            background-color: #1da1f2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            margin-left: auto;
        }
        .follow-button:hover {
            background-color: #0d95e8;
        }
        .tweets {
            margin-top: 20px;
        }
        .tweet {
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        .tweet img.profile-pic {
            border-radius: 50%;
            margin-bottom: 10px;
            width: 50px;
            height: 50px;
        }
        .tweet-content {
            flex: 1;
        }
        .tweet-content p {
            margin: 10px 0;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            text-decoration: none;
        }
        .back-button img {
            width: 30px;
            height: 30px;
        }
        .dark-mode-toggle {
            background-color: #1da1f2;
            color: white;
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
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .profile-header {
            border-bottom: 1px solid #333333;
        }
        body.dark-mode .tweets {
            background-color: #1e1e1e;
            border: 1px solid #333333;
        }
        body.dark-mode .tweet {
            background-color: #1e1e1e;
            border: 1px solid #333333;
        } 
        body.dark-mode #followersModal h3,
body.dark-mode #followersModal li {
    color: rgb(0, 0, 0);
}

    </style>
</head>
<body>
    <div class="container">
    <div class="profile-header">
    <form method="post" enctype="multipart/form-data" id="upload-form" style="display:none;">
        <input type="file" name="profile_picture" onchange="this.form.submit();">
    </form>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" width="100" height="100" onclick="document.getElementById('upload-form').querySelector('input').click();">
    <h2><?php echo htmlspecialchars($viewed_user); ?>'s Profile</h2>
</div>
<p>
    <strong>Followers:</strong> <?php echo $follower_count; ?>
    <button onclick="showFollowers()" style="margin-left: 10px; padding: 5px 10px; border: none; background-color: #1da1f2; color: white; border-radius: 10px; cursor: pointer;">View</button>
</p>

<!-- Modal -->
<div id="followersModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.3); z-index:10000; width:300px; max-height:300px; overflow-y:auto;">
    <h3 style="margin-top:0;">Followers:</h3>
    <ul style="list-style:none; padding:0; margin:0;">
        <?php foreach ($followers as $follower): ?>
            <li style="padding:5px 0; border-bottom:1px solid #eee;"><?php echo htmlspecialchars($follower); ?></li>
        <?php endforeach; ?>
        <?php if (count($followers) === 0): ?>
            <li>No followers yet.</li>
        <?php endif; ?>
    </ul>
    <button onclick="hideFollowers()" style="margin-top:15px; padding:5px 10px; background-color:#dc3545; color:white; border:none; border-radius:5px; cursor:pointer;">Close</button>
</div>


        <div class="tweets">
            <?php foreach ($posts as $post): ?>
                <div class="tweet">
                    <div class="tweet-content">
                        <h3><?php echo htmlspecialchars($viewed_user); ?></h3>
                        <p><?php echo htmlspecialchars($post['text']); ?></p>
                        <?php if (!empty($post['file_url'])): ?>
                            <?php $file_ext = pathinfo($post['file_url'], PATHINFO_EXTENSION); ?>
                            <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?php echo htmlspecialchars($post['file_url']); ?>" alt="Media" style="max-width: 100%; border-radius: 10px;">
                            <?php elseif ($file_ext == 'mp4'): ?>
                                <video controls style="max-width: 100%; border-radius: 10px;">
                                    <source src="<?php echo htmlspecialchars($post['file_url']); ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($username === $viewed_user): ?>
                        <form action="delete_post.php" method="get" style="margin-top: 10px;">
                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                            <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('dark-mode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('dark-mode', 'enabled');
            } else {
                localStorage.removeItem('dark-mode');
            }
        }
    </script>
    <script>
    function showFollowers() {
        document.getElementById('followersModal').style.display = 'block';
    }

    function hideFollowers() {
        document.getElementById('followersModal').style.display = 'none';
    }

    // Hide modal if clicking outside it
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('followersModal');
        if (modal.style.display === 'block' && !modal.contains(e.target) && e.target.tagName !== 'BUTTON') {
            hideFollowers();
        }
    });
</script>


</body>
</html>
