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
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch notifications from done_requests table
$sql = "SELECT * FROM done_requests WHERE username = ? ORDER BY completed_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="media/Logo.png">
    <title>Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f8fa; }
        .container { max-width: 600px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ddd; position: relative; }
        .notification { padding: 10px; border-bottom: 1px solid #ddd; cursor: pointer; position: relative; display: flex; justify-content: space-between; align-items: center; }
        .notification:last-child { border-bottom: none; }
        .notification:hover { background-color: #f0f0f0; }
        .exclamation { color: red; font-weight: bold; font-size: 18px; }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }
        .popup button { margin: 10px; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        .close-btn { background-color: red; color: white; }
        .go-btn { background-color: #1da1f2; color: white; }
        .thanks-btn { background-color: green; color: white; }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        .dark-mode .container {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }
        .dark-mode .notification {
            background-color: #1e1e1e;
            border-bottom: 1px solid #333;
        }
        .dark-mode .notification:hover {
            background-color: #333;
        }
        .dark-mode .popup {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }
        .dark-mode .popup button {
            background-color: #333;
            color: #e0e0e0;
            border: 1px solid #555;
        }
        .dark-mode .popup .close-btn { background-color: red; }
        .dark-mode .popup .go-btn { background-color: #1da1f2; }
        .dark-mode .popup .thanks-btn { background-color: green; }
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
        function showPopup(id, industry, investmentType) {
            document.getElementById("popup-info").innerText = `${industry} (${investmentType}) has been approved!`;
            document.getElementById("popup").dataset.requestId = id;
            document.getElementById("popup").style.display = "block";
        }

        function removeNotification() {
            const requestId = document.getElementById("popup").dataset.requestId;
            fetch("remove_notification.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + requestId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`notification-${requestId}`).style.display = 'none';
                    document.getElementById("popup").style.display = "none";
                } else {
                    alert("Error removing notification.");
                }
            })
            .catch(error => console.error("Error:", error));
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Notifications</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="notification" id="notification-<?php echo $row['id']; ?>" onclick="showPopup('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars($row['industry']); ?>', '<?php echo htmlspecialchars($row['investment_type']); ?>')">
                <p><?php echo htmlspecialchars($row['industry']); ?> (<?php echo htmlspecialchars($row['investment_type']); ?>) has been approved!</p>
                <span class="exclamation">&#x26A0;</span>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No notifications available.</p>
    <?php endif; ?>
</div>

<!-- Popup -->
<div id="popup" class="popup">
    <h3>Request Approved</h3>
    <p id="popup-info"></p>
    <button class="close-btn" onclick="document.getElementById('popup').style.display='none'">Close</button>
    <button class="go-btn" onclick="window.location.href='Ainvestors.php'">Go To Industries</button>
    <button class="thanks-btn" onclick="removeNotification()">Thanks</button>
</div>

</body>
</html>

<?php
$conn->close();
?>
