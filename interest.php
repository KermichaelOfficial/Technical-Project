<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's username from the session
$logged_in_user = $_SESSION['username'];

// Database connection
$servername = "localhost";
$dbname = "investus";
$dbusername = "root";
$dbpassword = "";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the business user's email from the business_users table
$business_user_email = ''; 

$business_user_query = $conn->prepare("SELECT email FROM business_users WHERE username = ?");
$business_user_query->bind_param("s", $logged_in_user);
$business_user_query->execute();
$business_user_query->store_result();
if ($business_user_query->num_rows > 0) {
    $business_user_query->bind_result($business_user_email);
    $business_user_query->fetch();
} else {
    die("Business user email not found.");
}
$business_user_query->close();

// Retrieve the post ID from POST data
$post_id = $_POST['post_id'];  

// Fetch the post details
$stmt = $conn->prepare("SELECT chp.username AS creator_username, chp.idea_text, u.email AS creator_email FROM creators_hub_posts chp JOIN users u ON chp.username = u.username WHERE chp.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();
$post = $post_result->fetch_assoc();

$creator_email = $post['creator_email'];
$creator_username = $post['creator_username'];
$idea_text = $post['idea_text'];

// PHPMailer Configuration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'investus720@gmail.com';
    $mail->Password   = 'ujpbiobaqptdawjj'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Send email to the post creator 
    $mail->setFrom('investus720@gmail.com', 'Investus');
    $mail->addAddress($creator_email, $creator_username);
    $mail->isHTML(true);
    $mail->Subject = "Company Potentially Interested in Your Idea";
    $mail->Body    = "
        <h3>Hi $creator_username,</h3>
        <p>A company has shown interest in your idea titled: \"$idea_text\".</p>
        <p>They can be reached at: <b>$business_user_email</b> for further discussions.</p>
        <p>Best regards,<br>The Investus Team</p>
    ";

    // Send the email to the creator
    $mail->send();

    // Clear previous recipients and send a confirmation to the business user
    $mail->clearAddresses();
    $mail->addAddress($business_user_email); 
    $mail->Subject = "You Have Shown Interest in an Idea";

    $mail->Body    = "
        <h3>Hi,</h3>
        <p>You have successfully shown interest in the idea titled: \"$idea_text\" posted by $creator_username.</p>
        <p>You can discuss the details further with them at: <b>$creator_email</b>.</p>
        <p>Best regards,<br>The Investus Team</p>
    ";

    // Send email to the business user
    $mail->send();

    // Redirect after sending email
    header("Location: Bcreators_hub.php");
    exit();

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$stmt->close();
$conn->close();
?>
