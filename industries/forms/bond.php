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

require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// DB Connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Bond Companies
$bondCompanies = [];
$result = $conn->query("SELECT DISTINCT business_username FROM investment_options WHERE investment_type = 'bond'");
while ($row = $result->fetch_assoc()) {
    $bondCompanies[] = $row['business_username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'] ?? '';
    $seller_name = $_POST['seller_name'] ?? '';
    $account_name = $_POST['account_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (empty($company_name) || empty($seller_name) || empty($account_name) || empty($email) || empty($amount) || empty($duration)) {
        die("All fields are required.");
    }

    // Create downloadable file
    $details = "Bond Investment Request\n\n";
    $details .= "Company: $company_name\nSeller: $seller_name\nAccount Name: $account_name\nEmail: $email\n";
    $details .= "Amount: $amount\nDuration: $duration\nReason: $reason\n";

    $dir = __DIR__ . "/../../downloads/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $filename = "Bond_Request_" . time() . ".txt";
    $filepath = $dir . $filename;
    file_put_contents($filepath, $details);

    $stmt = $conn->prepare("INSERT INTO bond_requests (company_name, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $company_name, $filepath);

    if ($stmt->execute()) {
        // Get Business Email
        $business_email_query = $conn->prepare("SELECT email FROM business_users WHERE username = ?");
        $business_email_query->bind_param("s", $company_name);
        $business_email_query->execute();
        $business_email_query->store_result();
        $business_email_query->bind_result($business_email);
        $business_email_query->fetch();
        $business_email_query->close();

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

            // Business email
            $mail->addAddress($business_email, $company_name);
            $mail->isHTML(true);
            $mail->Subject = 'New Bond Investment Request';
            $mail->Body    = "
                <h3>Hi $company_name,</h3>
                <p>You have received a new <strong>bond investment</strong> request from $account_name.</p>
                <p>Please review the details in the attached file.</p>
                <p>Best regards,<br>The Investus Team</p>
            ";
            $mail->addAttachment($filepath);
            $mail->send();

            // Investor email
            $mail->clearAddresses();
            $mail->clearAttachments();
            $mail->addAddress($email, $account_name);
            $mail->Subject = 'Bond Investment Request Submitted';
            $mail->Body    = "
                <h3>Hi $account_name,</h3>
                <p>Your <strong>bond investment</strong> request has been submitted successfully to <strong>$company_name</strong>.</p>
                <p>We will keep you informed on any updates.</p>
                <p>Best regards,<br>The Investus Team</p>
            ";
            $mail->send();

            echo "<script>alert('Bond Request Submitted! File saved.'); window.location.href = '../../Uindex.php';</script>";
        } catch (Exception $e) {
            echo "Email Error: " . $mail->ErrorInfo;
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
    <title>Bond Investment</title>
    <link rel="icon" type="image/png" href="../../media/Logo.png">
    <style>
        body {
            font-family: Arial;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .submit-btn {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
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
    <script>
        function autofillSellerName() {
            const companySelect = document.getElementById("company_name");
            document.getElementById("seller_name").value = companySelect.value;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Bond Investment Form</h1>
        <form action="bond.php" method="POST">
            <label for="company_name">Select Company:</label>
            <select name="company_name" id="company_name" required onchange="autofillSellerName()">
                <option value="">-- Select Company --</option>
                <?php foreach ($bondCompanies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company); ?>"><?php echo htmlspecialchars($company); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="seller_name">Seller Name (autofilled):</label>
            <input type="text" name="seller_name" id="seller_name" readonly required>

            <label for="account_name">Account Name:</label>
            <input type="text" name="account_name" id="account_name" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>

            <label for="amount">Investment Amount (USD):</label>
            <input type="number" name="amount" id="amount" required>

            <label for="duration">Bond Duration:</label>
            <select name="duration" id="duration" required>
                <option value="">-- Select Duration --</option>
                <option value="1-Year">1-Year (Shalom / Jubilee / Maccabee)</option>
                <option value="2-Year">2-Year</option>
                <option value="3-Year">3-Year</option>
                <option value="5-Year">5-Year</option>
                <option value="10-Year">10-Year</option>
                <option value="15-Year">15-Year</option>
            </select>

            <label for="reason">Reason for Investment (optional):</label>
            <input type="text" name="reason" id="reason" placeholder="E.g., Gift, Synagogue Appeal, etc.">

            <input type="submit" class="submit-btn" value="Submit Bond Investment">
        </form>
    </div>
</body>
</html>
