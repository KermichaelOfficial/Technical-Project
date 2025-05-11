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

$servername = "localhost";
$dbname = "investus";
$dbusername = "root"; 
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users and business users
$users_stmt = $conn->prepare("SELECT id, username FROM users");
$users_stmt->execute();
$users_stmt->bind_result($user_id, $user_username);
$users = [];
while ($users_stmt->fetch()) {
    $users[] = [
        'id' => $user_id,
        'username' => $user_username,
        'type' => 'user'
    ];
}
$users_stmt->close();

$business_stmt = $conn->prepare("SELECT id, username FROM business_users");
$business_stmt->execute();
$business_stmt->bind_result($business_id, $business_username);
$business_users = [];
while ($business_stmt->fetch()) {
    $business_users[] = [
        'id' => $business_id,
        'username' => $business_username,
        'type' => 'business'
    ];
}
$business_stmt->close();

// Update selected stars
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_users = $_POST['stars'] ?? [];

    // Clear existing stars
    $conn->query("DELETE FROM stars");

    // Insert new stars
    foreach ($selected_users as $selected) {
        list($id, $type) = explode("_", $selected); 
        if ($type === "user") {
            $stmt = $conn->prepare("INSERT INTO stars (user_id, business_id) VALUES (?, NULL)");
        } else {
            $stmt = $conn->prepare("INSERT INTO stars (user_id, business_id) VALUES (NULL, ?)");
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Stars updated successfully!'); window.location='Aindex.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Stars of the Day</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    width: 500px;
    text-align: center;
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    font-size: 1.1em;
}

.checkbox-group {
    text-align: left;
    margin: 20px 0;
    max-height: 200px;
    overflow-y: auto;
    padding-right: 10px;
    border-right: 2px solid #007bff;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.checkbox-group div {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.checkbox-group input[type="checkbox"] {
    margin-right: 10px; 
    cursor: pointer;
    width: 20px;
    height: 20px;
    background-color: gold;  
    border: 2px solid #cc9a00;
    border-radius: 3px;
}

.checkbox-group label {
    display: inline-block;
    margin-left: 5px;
    font-size: 1em;
    color: #333;
}

.checkbox-group input[type="checkbox"]:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

.checkbox-group input[type="checkbox"]:checked {
    background-color: #ffd700; 
}

button {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background 0.3s ease-in-out;
}

button:hover {
    background-color: #0056b3;
}
body.dark-mode {
    background-color: #000000; 
    color: #ffffff; 
}

body.dark-mode .container {
    background-color: #121212; 
    color: #ffffff; 
    border: 1px solid #333333;
}

body.dark-mode .checkbox-group {
    border-right: 2px solid #333333;
}

body.dark-mode .checkbox-group input[type="checkbox"]:hover {
    background-color: #333333;
}

body.dark-mode .checkbox-group label {
    color: #fff;
}

body.dark-mode button {
    background-color: #333333;
    color: #fff;
    border: 1px solid #555555;
}

body.dark-mode button:hover {
    background-color: #444444;
}

body.dark-mode h2 {
    color: #ffffff; 
}

body.dark-mode .checkbox-group input[type="checkbox"]:checked {
    background-color: #ffcc00; 
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
        <h2>⭐ Admin Panel - Stars of the Day</h2>
        <form method="post">
            <label>Select Users to Feature:</label>
            <div class="checkbox-group">
                <?php foreach ($users as $user): ?>
                    <div>
                        <input type="checkbox" name="stars[]" value="<?php echo $user['id'] . "_user"; ?>">
                        <label for="star-<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?> (User)</label>
                    </div>
                <?php endforeach; ?>
            </div>

            <label>Select Business Users to Feature:</label>
            <div class="checkbox-group">
                <?php foreach ($business_users as $business): ?>
                    <div>
                        <input type="checkbox" name="stars[]" value="<?php echo $business['id'] . "_business"; ?>">
                        <label for="star-<?php echo $business['id']; ?>"><?php echo htmlspecialchars($business['username']); ?> (Business)</label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit">Update Stars</button>
        </form>
    </div>
</body>
</html>
