<?php
include 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered.');</script>";
    } else {
        // Password validation
        if (!preg_match('/[A-Z]/', $password)) {
            echo "<script>alert('Password must contain at least one uppercase letter.');</script>";
        } elseif (!preg_match('/[0-9]/', $password)) {
            echo "<script>alert('Password must contain at least one number.');</script>";
        } elseif (!preg_match('/[\W_]/', $password)) {
            echo "<script>alert('Password must contain at least one special character.');</script>";
        } elseif (strlen($password) < 7) {
            echo "<script>alert('Password must be at least 7 characters long.');</script>";
        } else {
            // Generate 5-digit code
            $verification_code = rand(10000, 99999);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add a new column in database: verification_code
            $sql = "INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $verification_code);

            if ($stmt->execute()) {
                // Send verification email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'investus720@gmail.com';
                    $mail->Password   = 'ujpbiobaqptdawjj'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    $mail->setFrom('investus720@gmail.com', 'Investus');
                    $mail->addAddress($email, $username);
                    $mail->addReplyTo('investus720@gmail.com', 'Support Team');

                    $logoPath = 'media/logo.png';
                    if (file_exists($logoPath)) {
                    }

                    $mail->isHTML(true);
                    $mail->Subject = 'Confirm your Investus account';
                    $mail->Body    = "
                        <h3>Hi $username,</h3>
                        <p>Thanks for registering at <strong>Investus</strong>.</p>
                        <p>Your confirmation code is: <strong style='font-size:18px;'>$verification_code</strong></p>
                        <p>Please enter this code to complete your registration.</p>
                        <p>Best regards,<br>The Investus Team</p>
                    ";
                    $mail->AltBody = "Hi $username,\n\nYour confirmation code is: $verification_code\nPlease enter this to complete your registration.";

                    $mail->send();

                    // Redirect to confirmation page 
                    header("Location: verify.php?email=" . urlencode($email));
                    exit();
                } catch (Exception $e) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                echo "<script>alert('Registration failed.');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000000; 
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color:rgb(8, 8, 8); 
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 300px;
        }
        .register-container h1 {
            margin-bottom: 20px;
            font-size: 1.5em;
        }
        .register-container p {
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #b3b3b3;
        }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 25px;
            background-color: #1a1a1a;
            color: #ffffff;
            font-size: 0.9em;
        }
        .register-container input[type="submit"] {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .register-container input[type="submit"]:hover {
            background-color: #218838;
        }
        .register-container a {
            display: block;
            color: #b3b3b3;
            text-decoration: none;
            margin-top: 10px;
            font-size: 0.8em;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>
        <p>Please fill in the details to create an account!</p>
        <form method="post" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <p> Security Tip 🛡️</p>
            <p> Passwords Must Contain a Capital Letter, Number, Symbol and be atleast 7 Characters In length.</p>
            <input type="submit" value="Register">
        </form>
        <a href="login.php">Already have an account? Login here.</a>
    </div>
</body>
</html>