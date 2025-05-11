<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user has submitted the first form 
    if (isset($_POST['username']) && isset($_POST['email']) && !isset($_POST['new_password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];

        // Check if the username and email match in the database
        $sql = "SELECT * FROM users WHERE username='$username' AND email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User exists, show the password change form
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            echo '<div class="login-container">
                    <h1>Reset Password</h1>
                    <form method="post" action="passwordrec.php">
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                        <input type="submit" value="Submit New Password">
                    </form>
                </div>';
        } else {
            echo "No user found with that username and email combination.";
        }
    }

    // Check if the user has submitted the new password
    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify the new password and confirmation match
        if ($new_password === $confirm_password) {
            $username = $_SESSION['username'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
            $stmt->bind_param("ss", $hashed_password, $username);
            if ($stmt->execute()) {
                echo "Your password has been updated successfully!";
            } else {
                echo "An error occurred while updating the password. Please try again.";
            }
        } else {
            echo "The passwords do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Recovery</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #000000, #ff416c);
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #000000;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 300px;
        }
        .login-container h1 {
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .login-container p {
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #b3b3b3;
        }
        .login-container input[type="text"],
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 25px;
            background-color: #1a1a1a;
            color: #ffffff;
            font-size: 0.9em;
        }
        .login-container input[type="submit"] {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-container input[type="submit"]:hover {
            background-color: #218838;
        }
        .login-container a {
            display: block;
            color: #b3b3b3;
            text-decoration: none;
            margin-top: 10px;
            font-size: 0.8em;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Password Recovery</h1>
        <p>Enter your username and email to verify your account.</p>
        <form method="post" action="passwordrec.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="submit" value="Verify User">
        </form>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
