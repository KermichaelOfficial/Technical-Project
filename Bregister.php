<?php
include 'config.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Password validation
    if (!preg_match("/[A-Z]/", $password) || 
        !preg_match("/[0-9]/", $password) || 
        !preg_match("/[\W_]/", $password) || 
        strlen($password) < 7) {
        echo "Password must be at least 7 characters long and include uppercase, number, and symbol.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $status = 0; 
        $stmt = $conn->prepare("INSERT INTO business_users (username, email, password, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $hashed, $status);

        if ($stmt->execute()) {
            // Send email to admin for approval
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
                $mail->addAddress('investus720@gmail.com', 'Admin');

                $mail->isHTML(true);
                $mail->Subject = 'New Business Registration Request';
                $mail->Body    = "
                    <p>A new business user has registered and is pending approval:</p>
                    <p><strong>Username:</strong> $username<br>
                       <strong>Email:</strong> $email</p>
                    <p><a href='http://yourdomain.com/confirm_business_users.php'>Click here to approve or reject</a></p>
                ";
                $mail->send();
            } catch (Exception $e) {
                echo "Admin email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            echo "<script>alert('Your registration is submitted. You’ll receive an email once approved.'); window.location.href='Blogin.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
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
        <form method="post" action="Bregister.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <p> Security Tip 🛡️</p>
            <p> Passwords Must Contain a Capital Letter, Number, Symbol and be atleast 7 Characters In length.</p>
            <input type="submit" value="Register">
        </form>
        <a href="Blogin.php">Already have an account? Login here.</a>
    </div>
</body>
</html>
