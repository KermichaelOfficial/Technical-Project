<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $input_code = $_POST['code'];

    $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_code);
    $stmt->fetch();
    $stmt->close();

    if ($stored_code && $input_code === $stored_code) {
        $update = $conn->prepare("UPDATE users SET verification_code = NULL WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();
        $update->close();

        echo "<script>alert('Email verified successfully! You can now login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Invalid code. Please try again.');</script>";
    }
} else {
    $email = $_GET['email'] ?? '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .verify-container {
            background-color: #111;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }
        .verify-container h1 {
            margin-bottom: 20px;
        }
        .verify-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 25px;
            background-color: #1a1a1a;
            color: #ffffff;
            font-size: 0.9em;
        }
        .verify-container input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .verify-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="verify-container">
    <h1>Verify Your Email</h1>
    <form method="post" action="verify.php">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="text" name="code" placeholder="Enter 5-digit code" required>
        <input type="submit" value="Verify">
    </form>
</div>
</body>
</html>
