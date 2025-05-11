<?php
include 'config.php'; 

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

// Database Connection
function connectDB() {
    $servername = "localhost";
    $dbname = "investus";
    $dbusername = "root"; 
    $dbpassword = "";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to fetch posts
function fetchPosts($username) {
    $conn = connectDB();

    $query = "
        SELECT c.id, c.username, c.text, c.file_url, c.file_type, c.created_at, 
               COALESCE(u.profile_picture, bu.profile_picture) AS profile_picture
        FROM contents c
        LEFT JOIN users u ON c.username = u.username
        LEFT JOIN business_users bu ON c.username = bu.username
        WHERE c.username = ? 
        OR c.username IN (SELECT following FROM followers WHERE follower = ?)
        ORDER BY c.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query Preparation Failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $posts;
}
// Fetch "Stars of the Day" from both users and business_users
$stars_stmt = $conn->prepare("
    SELECT username, profile_picture FROM users WHERE id IN (SELECT user_id FROM stars)
    UNION
    SELECT username, profile_picture FROM business_users WHERE id IN (SELECT business_id FROM stars)
");
$stars_stmt->execute();
$stars_stmt->bind_result($star_username, $star_profile);
$stars = [];
while ($stars_stmt->fetch()) {
    $stars[] = [
        'username' => $star_username,
        'profile_picture' => $star_profile
    ];
}
$stars_stmt->close();

// Fetch posts for the logged-in user
$posts = fetchPosts($username);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse</title>
    <style>
        .tweet { background: #fff; padding: 10px; border-radius: 10px; margin-bottom: 15px; }
        .comment-section { margin-top: 10px; padding-left: 10px; }
        .comment { background: #f0f0f0; padding: 5px; border-radius: 5px; margin-top: 5px; }
        .comment-form { margin-top: 10px; }
    </style>

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
        body.dark-mode .post {
        background-color:rgb(29, 28, 28);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
    }
        
        .tweet-buttons {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #444;
        margin-top: 10px;
    }

    .tweet-buttons button {
        background-color: #28a745;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.3s;
        font-size: 14px;
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
.rating-container {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 18px;
    }
#search-results {
    background: white;
    border: 1px solid #e1e8ed;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    width: 200px;
    z-index: 1000;
}

.suggestion-item {
    padding: 10px;
    cursor: pointer;
}

.suggestion-item:hover {
    background-color: #f5f8fa;
}
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.popup-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
}
.rating-stars {
    cursor: pointer;
}
.burst-star {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #FFD700;
    border-radius: 50%;
    box-shadow: 0 0 5px #FFD700;
    pointer-events: none;
    transform: scale(0);
}
.burst-star {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #FFD700;
    border-radius: 50%;
    box-shadow: 0 0 5px #FFD700;
    pointer-events: none;
    transform: scale(0);
}
.burst-star {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #FFD700;
    border-radius: 50%;
    box-shadow: 0 0 8px #FFD700;
    pointer-events: none;
    opacity: 1;
    transition: transform 0.6s ease-out, opacity 0.6s;
}
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
    transition: all 0.3s ease-in-out;
}

.popup-content {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    max-height: 80vh;
    overflow-y: auto;
}

.popup h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #333;
}

textarea#new-comment {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #e1e8ed;
    resize: vertical;
    min-height: 50px;
    margin-bottom: 15px;
    font-size: 14px;
}

button#post-comment {
    background-color: #1da1f2;
    color: #fff;
    padding: 10px 15px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button#post-comment:hover {
    background-color: #0d95e8;
}

.comment {
    background: #f9f9f9;
    padding: 8px;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid #e1e8ed;
    display: flex;
    align-items: flex-start;
    font-size: 14px;
}

.comment .profile-picture {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    margin-right: 10px;
}

.comment .text {
    flex: 1;
}

.comment .timestamp {
    color: #aaa;
    font-size: 12px;
    margin-left: 10px;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
    color: #333;
    background: transparent;
    border: none;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #ff4d4d;
}
@media (max-width: 600px) {
    .popup-content {
        width: 90%;
    }

    .comment textarea {
        font-size: 12px;
    }
}
.content {
            margin-left: 50px;
            width: calc(100% - 150px);
            padding: 20px;
            overflow-y: auto;
        }
        .post {
            display: flex;
            align-items: flex-start;
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 15px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            flex-direction: column;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .post-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-text {
            font-size: 1em;
            color: #333;
            margin-bottom: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .post-media {
            margin-top: 10px;
            text-align: center;
        }

        .post-media img, .post-media video {
            max-width: 100%;
            border-radius: 8px;
        }

        .post-meta {
            font-size: 12px;
            color: gray;
            margin-top: 10px;
            text-align: right;
        }
        @media screen and (max-width: 768px) {
            .content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                width: 70%;
                height: auto;
                position: static;
            }

            .post-header img {
                width: 40px;
                height: 40px;
            }
        }
        .post {
        background-color:rgb(255, 255, 255);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
    }

    .post-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .post-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #ffffff;
    }

    .post-text {
        margin-bottom: 15px;
    }

    .post-media {
        margin-bottom: 15px;
    }

    .post-image, .post-video {
        max-width: 100%;
        border-radius: 8px;
    }

    .tweet-buttons {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #444;
        margin-top: 10px;
    }

    .tweet-buttons button {
        background-color: #28a745;
        color: #ffffff;
        border: none;
        padding: 8px 26px;
        border-radius: 50px;
        cursor: pointer;
        transition: background 0.3s;
        font-size: 14px;
    }

    .tweet-buttons button:hover {
        background-color: #218838;
    }

    .follow-btn {
        background-color: #007bff;
    }

    .follow-btn:hover {
        background-color: #0056b3;
    }

    .rating-container {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 18px;
    }

    .rating-stars {
        cursor: pointer;
        color: gold;
    }

    .avg-rating {
        color: #b3b3b3;
        font-size: 14px;
    }
    .stars-section h3 { font-size: 1.2em; }
        .star { display: flex; align-items: center; margin-bottom: 10px; }
        .star img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
        .star p { margin: 0; }
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        let query = searchInput.value.trim();

        if (query.length > 0) {
            fetch(`search_users.php?q=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    searchResults.innerHTML = data;
                    searchResults.style.display = "block";
                })
                .catch(error => console.error('Error fetching search results:', error));
        } else {
            searchResults.innerHTML = "";
            searchResults.style.display = "none";
        }
    });

    // Hide search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = "none";
        }
    });
});
</script>

</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="Uindex.php">Home</a>
            <a href="create_content.php">Create Content</a>
            <a href="#">Notifications</a>
            <a href="creators_hub.php">Creators Hub</a>
            <a href="market.php">Live Market</a>
            <a href="library.php">Library</a>
            <a href="profile.php">Profile</a>
            <a href="more.php">More</a>
        </div>
        <div id="comment-popup" class="popup">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <h3>Comments</h3>
        <div id="comment-list"></div>
        <textarea id="new-comment" placeholder="Write a comment..."></textarea>
        <button id="post-comment">Post Comment</button>
    </div>
</div>
        <div class="main-content">
        <div class="search-bar">
    <input type="text" id="search-input" placeholder="Search for users">
    <div id="search-results"></div>
</div>

<!-- Content Area -->
<div class="content">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                <img src="<?php echo htmlspecialchars($post['profile_picture']); ?>" alt="Profile">
                    
                    <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                </div>

                <div class="post-text">
                    <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
                </div>

                <?php if (!empty($post['file_url'])): ?>
                    <div class="post-media">
                        <?php
                        // Get file extension
                        $file_ext = pathinfo($post['file_url'], PATHINFO_EXTENSION);
                        // Check for image extensions
                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])):
                        ?>
                            <img src="<?php echo htmlspecialchars($post['file_url']); ?>" alt="Post Image" style="max-width: 100%; cursor: pointer;">
                        <?php
                        // Check for video extensions
                        elseif (in_array($file_ext, ['mp4', 'avi', 'mov'])):
                        ?>
                            <video controls style="max-width: 100%; cursor: pointer;">
                                <source src="<?php echo htmlspecialchars($post['file_url']); ?>" type="video/<?php echo htmlspecialchars($file_ext); ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <p>Unsupported file type: <?php echo htmlspecialchars($file_ext); ?></p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p>No media available</p>
                <?php endif; ?>

                <div class="tweet-buttons">
                    <button class="comment-btn" data-content-id="<?php echo $post['id']; ?>">Comment</button>
                    <button class="follow-btn">Follow</button>
                    <div class="rating-container" data-post-id="<?php echo $post['id']; ?>">
                        <span class="rating-stars" data-value="1">★</span>
                        <span class="rating-stars" data-value="2">★</span>
                        <span class="rating-stars" data-value="3">★</span>
                        <span class="rating-stars" data-value="4">★</span>
                        <span class="rating-stars" data-value="5">★</span>
                        <span class="avg-rating">(0)</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts available</p>
    <?php endif; ?>
</div>
</div>
<div class="sidebar">
            <div class="stars-section">
                <h3>⭐ Stars of the Day</h3>
                <?php if (!empty($stars)): ?>
                    <?php foreach ($stars as $star): ?>
                        <div class="star">
                            <div style="display: flex; align-items: center;">
                                <img src="<?php echo htmlspecialchars($star['profile_picture']); ?>" alt="Profile">
                                <p><?php echo htmlspecialchars($star['username']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No featured users today.</p>
                <?php endif; ?>
        </div>
        </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let contentContainer = document.getElementById("content-container");
        if (!contentContainer || contentContainer.innerHTML.trim() === "") {
            document.getElementById("browse-section").style.display = "block";
        }
    });
</script>
        </div>

            </div>
        </div>
        </div>
    </div>
    <script>
       document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".rating-container").forEach(container => {
        const postId = container.getAttribute("data-post-id");
        const stars = container.querySelectorAll(".rating-stars");
        const avgRatingSpan = container.querySelector(".avg-rating");

        //Load and apply the current average rating
        function loadRating() {
            fetch(`get_ratings.php?post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.average !== undefined) {
                        const roundedRating = Math.round(data.average);
                        avgRatingSpan.textContent = `(${roundedRating})`;
                        highlightStars(roundedRating);
                    }
                })
                .catch(error => console.error("Error fetching ratings:", error));
        }

        //Function to highlight stars based on rating
        function highlightStars(rating) {
            stars.forEach(star => {
                const starValue = parseInt(star.getAttribute("data-value"));
                star.style.color = starValue <= rating ? "#FFD700" : "#ccc"; 
            });
        }

        // Function to create a burst effect of stars
        function createStarBurst(target) {
            for (let i = 0; i < 10; i++) { // Create 10 stars for effect
                let burstStar = document.createElement("div");
                burstStar.classList.add("burst-star");
                document.body.appendChild(burstStar);

                const rect = target.getBoundingClientRect();
                const xOffset = rect.left + rect.width / 2;
                const yOffset = rect.top + rect.height / 2;
                burstStar.style.left = `${xOffset}px`;
                burstStar.style.top = `${yOffset}px`;

                const angle = Math.random() * 2 * Math.PI; 
                const distance = Math.random() * 40 + 20; 
                const x = Math.cos(angle) * distance;
                const y = Math.sin(angle) * distance;

                // Trigger animation
                setTimeout(() => {
                    burstStar.style.transform = `translate(${x}px, ${y}px) scale(1)`;
                    burstStar.style.opacity = "0";
                }, 10);

                // Remove after animation
                setTimeout(() => {
                    burstStar.remove();
                }, 600);
            }
        }

        // Handle star click 
        stars.forEach(star => {
            star.addEventListener("click", function () {
                const rating = parseInt(this.getAttribute("data-value"));

                fetch("rate_post.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `post_id=${postId}&rating=${rating}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        avgRatingSpan.textContent = `(${rating})`; 
                        highlightStars(rating); 
                        createStarBurst(this); 
                    } else {
                        console.error("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error submitting rating:", error));
            });

            //Handle star hover effect
            star.addEventListener("mouseover", function () {
                highlightStars(parseInt(this.getAttribute("data-value")));
            });

            //Reset stars to actual rating on mouseout
            star.addEventListener("mouseout", function () {
                loadRating(); 
            });
        });

        //Load initial rating when page loads
        loadRating();
    });
});


        </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const commentPopup = document.getElementById('comment-popup');
    const commentList = document.getElementById('comment-list');
    const closeBtn = document.querySelector('.close-btn');
    const postCommentBtn = document.getElementById('post-comment');
    let currentContentId = null;

    // Show popup when comment button is clicked
    document.querySelectorAll('.comment-btn').forEach(button => {
        button.addEventListener('click', function () {
            currentContentId = this.getAttribute('data-post-id');
            commentPopup.style.display = 'flex';
            fetchComments(currentContentId);
        });
    });

    // Close popup when clicking outside
    commentPopup.addEventListener('click', function (event) {
        if (event.target === commentPopup || event.target === closeBtn) {
            commentPopup.style.display = 'none';
        }
    });

    // Fetch comments
    function fetchComments(contentId) {
        fetch(`fetch_comments.php?content_id=${contentId}`)
            .then(response => response.json())
            .then(data => {
                commentList.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(comment => {
                        commentList.innerHTML += `<div class='comment'><strong>${comment.username}</strong>: ${comment.comment_text} <span class="time">${comment.created_at}</span></div>`;
                    });
                } else {
                    commentList.innerHTML = "<p>No comments yet.</p>";
                }
            })
            .catch(error => console.error('Error fetching comments:', error));
    }

    // Post new comment
    postCommentBtn.addEventListener('click', function () {
        const newComment = document.getElementById('new-comment').value.trim();
        if (newComment !== '') {
            fetch('post_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `content_id=${currentContentId}&comment=${encodeURIComponent(newComment)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById('new-comment').value = '';
                    fetchComments(currentContentId);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error posting comment:', error));
        }
    });
});

</script>

</body>
</html>



search_users.php: <?php
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

// Get the query from the URL parameter
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// If the query is not empty, search the database
if (!empty($query)) {
    // Use LIKE for partial matching
    $stmt = $conn->prepare("SELECT username FROM users WHERE username LIKE ? LIMIT 5");
    $searchTerm = "%" . $query . "%"; 
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $stmt->bind_result($username);

    // Fetch results and display them as suggestions
    $suggestions = [];
    while ($stmt->fetch()) {
        $suggestions[] = $username;
    }

    // Return the results 
    if (count($suggestions) > 0) {
        foreach ($suggestions as $suggestion) {
            // Wrap each username in a link to their profile
            echo '<div class="suggestion-item"><a href="view_profile.php?user=' . urlencode($suggestion) . '">' . htmlspecialchars($suggestion) . '</a></div>';
        }
    } else {
        echo '<div>No users found</div>';
    }

    $stmt->close();
}

$conn->close();
?>