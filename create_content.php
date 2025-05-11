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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['text'];
    $target_dir = "uploads/";
    $uploadOk = 1;
    $file_url = "";
    $imageFileType = "";

    if (!empty($_FILES["file"]["tmp_name"])) {
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an image or video
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false || in_array($imageFileType, ['mp4', 'avi', 'mov'])) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_url = $target_file;
            } else {
                $file_url = "";
            }
        }
    }

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

    $stmt = $conn->prepare("INSERT INTO contents (username, text, file_url, file_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $text, $file_url, $imageFileType);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: browse.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Content</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e1e8ed;
            border-radius: 5px;
            resize: none;
        }
        .container input[type="file"] {
            margin-bottom: 10px;
        }
        .container input[type="submit"] {
            background-color: #1da1f2;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .container input[type="submit"]:hover {
            background-color: #0d95e8;
        }
        .step {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 5px 0;
            width: 100%;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
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
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .container {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        body.dark-mode .container textarea {
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #555555;
        }
        body.dark-mode .container input[type="submit"] {
            background-color: #333333;
        }
        body.dark-mode .container input[type="submit"]:hover {
            background-color: #444444;
        }
    </style>

    <!-- Adding the favicon using the Logo.png -->
    <link rel="icon" type="image/png" href="media/Logo.png">
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Check if dark mode is enabled in localStorage
            if (localStorage.getItem('dark-mode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            // Save the current state to localStorage
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
        <h1>Create New Content</h1>
        <div class="step">SEO Research</div>
        <div class="step" style="background-color: #28a745;">Ideation</div>
        <div class="step" style="background-color: #17a2b8;">Writing/Creating</div>
        <div class="step" style="background-color: #ffc107;">Editing</div>
        <div class="step" style="background-color: #dc3545;">Uploading</div>
        <div class="step" style="background-color: #6c757d;">Publishing</div>
        <div class="step" style="background-color: #20c997;">Promoting</div>
        <form action="create_content.php" method="post" enctype="multipart/form-data">
            <textarea name="text" placeholder="Write something..."></textarea><br>
            <input type="file" name="file"><br>
            <input type="submit" value="Post">
        </form>
    </div>
</body>
</html>