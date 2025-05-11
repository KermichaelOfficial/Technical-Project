<?php
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
include 'config.php';
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle approval
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE business_users SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Fetch user email & name
        $result = $conn->query("SELECT username, email FROM business_users WHERE id = $id");
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $username = $row['username'];

        // Send approval email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'investus720@gmail.com';
            $mail->Password = 'ujpbiobaqptdawjj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('investus720@gmail.com', 'Investus');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Your Business Account Has Been Verified';
            $mail->Body    = "
                <h3>Hi $username,</h3>
                <p>Good news! Your business account has been approved by the Investus team. You can now log in and use all available features.</p>
                <p>Best regards,<br>The Investus Team</p>
            ";

            $mail->send();
            echo "<script>alert('User approved and email sent.'); window.location='confirm_business_users.php';</script>";
        } catch (Exception $e) {
            echo "Error sending approval email: {$mail->ErrorInfo}";
        }
    }
}

// Handle rejection
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);

    // Fetch user email & name
    $result = $conn->query("SELECT username, email FROM business_users WHERE id = $id");
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $username = $row['username'];

    // Set status to 2 (rejected)
    $stmt = $conn->prepare("UPDATE business_users SET status = 2 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Send rejection email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'investus720@gmail.com';
            $mail->Password = 'ujpbiobaqptdawjj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('investus720@gmail.com', 'Investus');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Business Account Rejected';
            $mail->Body    = "
                <h3>Hi $username,</h3>
                <p>Thank you for registering your business with Investus. After review, we’re unable to approve your account at this time.</p>
                <p>Feel free to reach out if you have any questions.</p>
                <p>Regards,<br>The Investus Team</p>
            ";

            $mail->send();
            echo "<script>alert('User has been rejected and notified.'); window.location='confirm_business_users.php';</script>";
        } catch (Exception $e) {
            echo "Error sending rejection email: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<link rel="icon" type="image/png" href="media/Logo.png">
<html>
<head>
    <title>Confirm Business Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            padding: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px 18px;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #222;
        }
        tr:hover {
            background-color: #1e1e1e;
        }
        a.button {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 8px;
        }
        .approve {
            background-color: #28a745;
            color: white;
        }
        .reject {
            background-color: #dc3545;
            color: white;
        }
        .new-label {
            background-color: #ffc107;
            color: black;
            padding: 2px 6px;
            font-size: 0.8em;
            border-radius: 4px;
            margin-left: 5px;
        }
    </style>

</head>
<body>
    <h2>Pending Business User Registrations</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT id, username, email, status FROM business_users WHERE status = 0");
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <?= ($row['status'] == 0) ? 'Pending <span class="new-label">New</span>' : 'Unknown'; ?>
            </td>
            <td>
                <a class="button approve" href="?approve=<?= $row['id'] ?>">Approve</a>
                <a class="button reject" href="?reject=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to reject this user?')">Reject</a>
            </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="4">No new business registrations pending approval.</td></tr>
        <?php endif; ?>
    </table>

</body>
</html>
