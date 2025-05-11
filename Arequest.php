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

// Database connection
$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests
$sql = "SELECT id, company_name, industry, status FROM requests ORDER BY id DESC";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = trim($_POST['company_name']);
    $description = trim($_POST['description']);
    $header_text = trim($_POST['header_text']);
    $investment_type = trim($_POST['investment_type']);
    $company_type = trim($_POST['company_type']); 
    
    // Image Upload Handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["image_file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image_file"]["size"] > 5000000) { 
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if(!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO admin_investments (company_name, description, image_url, header_text, investment_type, company_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $company_name, $description, $target_file, $header_text, $investment_type, $company_type); // Include company_type
            
            if ($stmt->execute()) {
                echo "<script>alert('Investment data saved successfully!'); window.location.href='/Investus_Tech_Project/Ainvestors.php';</script>";
            } else {
                echo "<script>alert('Error saving data. Please try again.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}
$conn->close();

// Ensure the uploads directory exists
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f8fa; }
        .container { max-width: 800px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ddd; }
        .request { padding: 10px; border-bottom: 1px solid #ddd; cursor: pointer; }
        .request:hover { background-color: #f0f0f0; }
        .popup { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background: #fff; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            width: 400px; 
            text-align: center;
        }
        .popup button { margin: 10px; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        .close-btn { background-color: red; color: white; }
        .go-btn { background-color: #1da1f2; color: white; }
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
    background-color: #000000; 
    color: #ffffff; 
}

body.dark-mode .container {
    background-color: #121212; 
    color: #ffffff; 
    border: 1px solid #333333;
}

body.dark-mode .request {
    background-color: #1e1e1e;
    color: #ffffff;
    border-bottom: 1px solid #333333;
}

body.dark-mode .request:hover {
    background-color: #333333;
}

body.dark-mode .popup {
    background-color: #1e1e1e;
    color: #ffffff;
    border: 1px solid #444444;
}

body.dark-mode .popup button {
    background-color: #333333;
    color: #ffffff;
    border: 1px solid #555555;
}

body.dark-mode .popup button:hover {
    background-color: #444444;
}

body.dark-mode .search-bar input {
    background-color: #333333;
    color: #ffffff;
    border: 1px solid #555555;
}

body.dark-mode .sidebar, 
body.dark-mode .right-sidebar {
    background-color: #1e1e1e;
    color: #ffffff;
}

body.dark-mode .sidebar a {
    background-color: #1e1e1e;
    color: #ffffff;
    border: 1px solid #333333;
}

body.dark-mode .sidebar a:hover {
    background-color: #333333;
    color: #ffffff;
}

body.dark-mode .tweet {
    background-color: #1e1e1e;
    color: #ffffff;
    border: 1px solid #333333;
}
.container { max-width: 600px; margin: 40px auto; background-color: #ffffff; padding: 20px; border-radius: 10px; border: 1px solid #e1e8ed; text-align: center; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .submit-btn { background-color: #1da1f2; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }

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
    <h2>Investment Requests</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="request" onclick="showPopup(<?php echo $row['id']; ?>)">
            <strong><?php echo $row['company_name']; ?></strong> - <?php echo $row['industry']; ?> (<?php echo $row['status']; ?>)
        </div>
    <?php endwhile; ?>
<!-- Form to Add Investment Opportunity Below Requests -->
<h2>Add Investment Opportunity</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="company_name" placeholder="Company Name" required>
        <input type="text" name="header_text" placeholder="Header Text (e.g., Company: SpaceX)" required>
        <textarea name="description" rows="4" placeholder="Company Description" required></textarea>
        <input type="file" name="image_file" accept="image/*" required>

        <!-- Investment Type Dropdown -->
        <select name="investment_type" required>
            <option value="stocks">Stocks: Own part of a company with potential growth and dividends</option>
            <option value="bonds">Bonds: Low-risk loans to companies or governments with steady returns</option>
            <option value="real_estate">Real Estate: Property investment with income through rent or appreciation</option>
            <option value="mutual_funds">Mutual Funds: Diversified portfolio managed by professionals</option>
            <option value="etfs">ETFs: Track market indices, tradeable like stocks</option>
            <option value="commodities">Commodities: Investment in raw materials, a hedge against inflation</option>
            <option value="cryptocurrencies">Cryptocurrencies: High-risk digital assets with high-reward potential</option>
        </select>

        <!-- Company Type Dropdown -->
        <select name="company_type" required>
            <option value="Aerospace & Defense">Aerospace & Defense</option>
            <option value="Airlines">Airlines</option>
            <option value="Automotive">Automotive</option>
            <option value="Banking">Banking</option>
            <option value="Biotech">Biotech</option>
            <option value="Cannabis">Cannabis</option>
            <option value="Energy">Energy</option>
            <option value="Food & Beverage">Food & Beverage</option>
            <option value="Health Care">Health Care</option>
            <option value="Internet">Internet</option>
            <option value="Media">Media</option>
            <option value="Metals & Mining">Metals & Mining</option>
            <option value="Pharma">Pharma</option>
            <option value="Real Estate/Construction">Real Estate/Construction</option>
            <option value="Retail">Retail</option>
            <option value="Software">Software</option>
            <option value="Telecom">Telecom</option>
            <option value="Technology">Technology</option>
        </select>

        <button type="submit" class="submit-btn">Submit</button>
    </form>
</div>

<!-- Popup -->
<div id="popup" class="popup">
    <h3>Request Details</h3>
    <p id="request-info"></p>
    <button class="close-btn" onclick="document.getElementById('popup').style.display='none'">Close</button>
    <button class="go-btn" onclick="window.location.href='Ainvestors.php'">Go To</button>
    <button class="done-btn" onclick="markAsDone()">Done</button>
</div>

<script>
let currentRequestId = null;

function showPopup(requestId) {
    currentRequestId = requestId;
    fetch("get_request.php?id=" + requestId)
        .then(response => response.text())
        .then(data => {
            document.getElementById("request-info").innerHTML = data;
            document.getElementById("popup").style.display = "block";
        });
}

function markAsDone() {
    if (!currentRequestId) return;

    fetch("mark_done.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + currentRequestId
    })
    .then(response => response.json())
    .then(data => {
        alert("Request marked as completed!"); 
        window.location.href = "Arequest.php"; 
    })
    .catch(error => console.error("Error marking request as done:", error));
}
</script>

</body>
</html>

