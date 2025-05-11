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

$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get searched username from URL
if (isset($_GET['username']) && !empty(trim($_GET['username']))) {
    $viewed_user = trim($_GET['username']);
} else {
    echo "<p>Error: No username provided.</p>";
    exit();
}

// Search for user in both users and business_users
$user_found = false;
$profile_picture = null;

$tables = ['users', 'business_users'];
foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT profile_picture FROM $table WHERE username = ?");
    $stmt->bind_param("s", $viewed_user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($profile_picture);
        $stmt->fetch();
        $user_found = true;
        break;
    }
    $stmt->close();
}

if (!$user_found) {
    echo "<p>User not found.</p>";
    exit();
}

// Get follower count
$stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE following = ?");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$stmt->bind_result($follower_count);
$stmt->fetch();
$stmt->close();

// Fetch posts from contents table
$posts = [];
$stmt = $conn->prepare("SELECT text, file_url, file_type, created_at FROM contents WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $viewed_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
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
            border: 2px solid #1da1f2;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .profile-header h2 {
            margin: 0;
        }
        .content-section {
            margin-top: 20px;
        }
        .content-box {
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .content-box img, .content-box video, .content-box audio {
            border-radius: 10px;
            max-width: 100%;
        }
        .timestamp {
            font-size: 0.8em;
            color: gray;
        }
        .download-link {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #1da1f2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .download-link:hover {
            background-color: #0d8ae2;
        }
#followBtn {
    margin-left: 20px;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background-color: #1da1f2;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#followBtn:hover {
    background-color: #0d8ae2;
}
#followBtn.unfollow {
    background-color: #e0245e;
}

#followBtn.unfollow:hover {
    background-color: #c21c4d;
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
        body.dark-mode h3,p {
            color:rgb(0, 0, 0);
        } 
        body.dark-mode strong {
            color:rgb(255, 255, 255);
        } 
        d {
    position: relative;
    top: 7px; 
}
        
        
    </style>
    <script>
        // Dark Mode toggle
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
</head>
<body>
    <div class="container">
    <div class="profile-header">
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
    <h2><?php echo htmlspecialchars($viewed_user); ?>'s Profile</h2>
    <?php if ($viewed_user !== $_SESSION['username']): ?>
        <button id="followBtn" onclick="toggleFollow()"></button>
    <?php endif; ?>
</div>
<d><strong>Followers:</strong> <?php echo $follower_count; ?><d>
        <div class="content-section">
            <?php if (empty($posts)): ?>
                <p>No posts available.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="content-box">
                        <h3><?php echo htmlspecialchars($viewed_user); ?></h3>
                        <p><?php echo htmlspecialchars($post['text']); ?></p>
                        <p class="timestamp"><?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></p>
                        
                        <?php if (!empty($post['file_url'])): ?>
                            <?php
                            $file_url = htmlspecialchars($post['file_url']);
                            $file_type = strtolower($post['file_type']);
                            ?>
                            
                            <!-- Display images -->
                            <?php if (in_array($file_type, ['image', 'jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?php echo $file_url; ?>" alt="Uploaded Image">
                            
                            <!-- Display videos -->
                            <?php elseif (in_array($file_type, ['video', 'mp4', 'webm', 'ogg'])): ?>
                                <video controls>
                                    <source src="<?php echo $file_url; ?>" type="video/mp4">
                                </video>
                            
                            <!-- Display audio -->
                            <?php elseif (in_array($file_type, ['audio', 'mp3', 'wav', 'ogg'])): ?>
                                <audio controls>
                                    <source src="<?php echo $file_url; ?>" type="audio/mpeg">
                                </audio>
                            
                            <!-- Display documents -->
                            <?php elseif (in_array($file_type, ['pdf', 'doc', 'docx', 'pptx', 'xlsx'])): ?>
                                <a class="download-link" href="<?php echo $file_url; ?>" target="_blank">Download Document</a>
                            
                            <!-- Default case: Show file link -->
                            <?php else: ?>
                                <a class="download-link" href="<?php echo $file_url; ?>" target="_blank">View File</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    checkFollowStatus();
});

function checkFollowStatus() {
    let username = "<?php echo $viewed_user; ?>";

    fetch("check_follow_status.php?following=" + username)
        .then(response => response.json())
        .then(data => {
            let followBtn = document.getElementById("followBtn");
            if (data.following) {
                followBtn.innerText = "Unfollow";
                followBtn.style.backgroundColor = "#e0245e";
            } else {
                followBtn.innerText = "Follow";
                followBtn.style.backgroundColor = "#1da1f2";
            }
        });
}

function toggleFollow() {
    let username = "<?php echo $viewed_user; ?>";
    let formData = new FormData();
    formData.append("following", username);

    fetch("follow.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "followed") {
            document.getElementById("followBtn").innerText = "Unfollow";
            document.getElementById("followBtn").style.backgroundColor = "#e0245e";
        } else if (data.status === "unfollowed") {
            document.getElementById("followBtn").innerText = "Follow";
            document.getElementById("followBtn").style.backgroundColor = "#1da1f2";
        }
    });
}
</script>
</html>
