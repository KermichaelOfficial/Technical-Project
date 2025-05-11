<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin_users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {

         // Send welcome email
         $subject = "Welcome to Investus!";
         $message = "Hi $username,\n\nThank you for registering at Investus! We're excited to have you here.\n\nBest regards,\nThe Team.";
         $headers = "From: noreply@yourdomain.com" . "\r\n" .
                    "Reply-To: support@yourdomain.com" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

         // Send email
         mail($email, $subject, $message, $headers);


        header("Location: Alogin.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
        <form method="post" action="Aregister.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <a href="Alogin.php">Already have an account? Login here.</a>
    </div>
</body>
</html>
