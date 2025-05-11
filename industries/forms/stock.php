<?php
session_start();
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../../index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch companies offering stock investments
$companies = [];
$result = $conn->query("SELECT DISTINCT business_username FROM investment_options WHERE investment_type = 'stock'");
while ($row = $result->fetch_assoc()) {
    $companies[] = $row['business_username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Investor details
    $investor_name = $_POST['investor_name'] ?? '';
    $investor_email = $_POST['investor_email'] ?? '';
    $investor_phone = $_POST['investor_phone'] ?? '';

    // Stock investment details
    $company_name = $_POST['company_name'] ?? '';
    $stock_symbol = $_POST['stock_symbol'] ?? '';
    $investment_amount = $_POST['investment_amount'] ?? '';
    $expected_roi = $_POST['expected_roi'] ?? '';
    $investment_duration = $_POST['investment_duration'] ?? '';
    $counterparty_name = $_POST['counterparty_name'] ?? '';
    $counterparty_id = $_POST['counterparty_id'] ?? '';
    $contact_info = $_POST['contact_info'] ?? '';

    if (
        empty($investor_name) ||
        empty($investor_email) ||
        empty($investor_phone) ||
        empty($company_name) ||
        empty($stock_symbol) ||
        empty($investment_amount) ||
        empty($expected_roi) ||
        empty($investment_duration) ||
        empty($counterparty_name) ||
        empty($counterparty_id) ||
        empty($contact_info)
    ) {
        die("All required fields must be filled.");
    }

    // Create .txt file with details
    $details = "Stock Investment Request\n\n";
    $details .= "Investor Name: $investor_name\n";
    $details .= "Investor Email: $investor_email\n";
    $details .= "Investor Phone: $investor_phone\n";
    $details .= "Company: $company_name\n";
    $details .= "Stock Symbol: $stock_symbol\n";
    $details .= "Investment Amount: $investment_amount\n";
    $details .= "Expected ROI: $expected_roi%\n";
    $details .= "Investment Duration: $investment_duration years\n";
    $details .= "Counterparty Name: $counterparty_name\n";
    $details .= "Counterparty ID: $counterparty_id\n";
    $details .= "Contact Info: $contact_info\n";

    // Directory where files will be saved
    $dir = __DIR__ . "/../../downloads/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $filename = "Stock_Investment_Request_" . time() . ".txt";
    $filepath = $dir . $filename;
    file_put_contents($filepath, $details);

    // Insert into database 
    $stmt = $conn->prepare("INSERT INTO stock_requests (company_name, file_path, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $company_name, $filepath);
    if ($stmt->execute()) {
        // Send email to business user
        $business_email_query = $conn->prepare("SELECT email FROM business_users WHERE username = ?");
        $business_email_query->bind_param("s", $company_name);
        $business_email_query->execute();
        $business_email_query->store_result();
        $business_email_query->bind_result($business_email);
        $business_email_query->fetch();
        $business_email_query->close();

        // Prepare PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'investus720@gmail.com';
            $mail->Password   = 'ujpbiobaqptdawjj'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Send email to business user
            $mail->setFrom('investus720@gmail.com', 'Investus');
            $mail->addAddress($business_email, $company_name);
            $mail->isHTML(true);
            $mail->Subject = 'New Stock Investment Request';
            $mail->Body    = "
                <h3>Hi $company_name,</h3>
                <p>You have received a new stock investment request from $investor_name.</p>
                <p>Please review the details in the attached file.</p>
                <p>Best regards,<br>The Investus Team</p>
            ";

            // Send the email to the business user
            $mail->send();

            // Clear previous recipients and prepare email for the investor
            $mail->clearAddresses();
            $mail->addAddress($investor_email, $investor_name);
            $mail->Subject = 'Stock Investment Request Submitted';
            $mail->Body    = "
                <h3>Hi $investor_name,</h3>
                <p>Your stock investment request has been submitted successfully.</p>
                <p>You will be contacted soon by the business you've selected.</p>
                <p>Best regards,<br>The Investus Team</p>
            ";

            // Send the email to the investor
            $mail->send();

            echo "<script>alert('Stock Investment Request Submitted! File saved and emails sent.'); window.location.href = '../../Uindex.php';</script>";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        die("Database Error: " . $stmt->error);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Investment Request</title>
    <link rel="icon" type="image/png" href="../../media/Logo.png">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 750px;
        margin: 50px auto;
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #1a1a1a;
        margin-bottom: 30px;
    }

    label {
        display: block;
        margin-top: 20px;
        font-weight: 500;
        color: #333;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 14px;
        font-size: 16px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fdfdfd;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .submit-btn {
        margin-top: 30px;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 14px;
        font-size: 17px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 8px;
        width: 100%;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .submit-btn:hover {
        background-color: #0056b3;
        box-shadow: 0 6px 18px rgba(0, 123, 255, 0.2);
    }
    .dark-mode-toggle {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #1da1f2;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

body.dark-mode {
    background-color: #121212;
    color: #e0e0e0;
}

body.dark-mode .container {
    background: #1e1e1e;
    color: #e0e0e0;
}

body.dark-mode table {
    background-color: #1e1e1e;
}

body.dark-mode th, body.dark-mode td {
    color: #e0e0e0;
    border-bottom: 1px solid #444;
}

body.dark-mode a {
    color: #1da1f2;
}

body.dark-mode a:hover {
    color: #ffffff;
    text-decoration: underline;
}

body.dark-mode .delete-btn {
    color: #ff6347;
}

body.dark-mode .delete-btn:hover {
    text-decoration: underline;
}

body.dark-mode h2 {
    color: #ffffff; 
}

body.dark-mode h1 {
    color: #ffffff; 
}
body.dark-mode label {
    color: #ffffff; 
}
body.dark-mode input {
    color:rgb(12, 12, 12); 
}
input, select, textarea { 
    width: 100%; 
    padding: 10px; 
    margin: 10px 0; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
    box-sizing: border-box; 
}

textarea {
    resize: vertical; 
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
    <h2>Stock Investment Request Form</h2>
    <form method="POST">
        <!-- Investor Details -->
        <label for="investor_name">Investor Name:</label>
        <input type="text" name="investor_name" id="investor_name" required>

        <label for="investor_email">Investor Email:</label>
        <input type="email" name="investor_email" id="investor_email" required>

        <label for="investor_phone">Investor Phone Number:</label>
        <input type="text" name="investor_phone" id="investor_phone" required>

        <!-- Stock Investment Details -->
        <label for="company_name">Select Company:</label>
        <select name="company_name" id="company_name" required>
            <option value="">-- Select Company --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?php echo htmlspecialchars($company); ?>"><?php echo htmlspecialchars($company); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="stock_symbol">Stock Symbol:</label>
        <input type="text" name="stock_symbol" id="stock_symbol" required>

        <label for="investment_amount">Investment Amount:</label>
        <input type="number" name="investment_amount" id="investment_amount" required>

        <label for="expected_roi">Expected ROI (%):</label>
        <input type="number" step="0.01" name="expected_roi" id="expected_roi" required>

        <label for="investment_duration">Investment Duration (years):</label>
        <input type="number" name="investment_duration" id="investment_duration" required>

        <label for="counterparty_name">Counterparty Name:</label>
        <input type="text" name="counterparty_name" id="counterparty_name" required>

        <label for="counterparty_id">Counterparty ID:</label>
        <input type="text" name="counterparty_id" id="counterparty_id" required>

        <label for="contact_info">Contact Info:</label>
        <textarea name="contact_info" id="contact_info" rows="3" required></textarea>

        <button type="submit" class="submit-btn">Submit Request</button>
    </form>
</div>
</body>
</html>
