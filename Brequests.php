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

$conn = new mysqli("localhost", "root", "", "investus");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle File Download
if (isset($_GET['file'])) {
    $file_name = basename($_GET['file']);
    $file_path = __DIR__ . "/downloads/" . $file_name;

    if (file_exists($file_path)) {
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $file_name);
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($file_path));
        readfile($file_path);
        exit();
    } else {
        echo "<script>alert('File not found: $file_path'); window.location.href='Brequests.php';</script>";
        exit();
    }
}

// Handle Request Deletion
if (isset($_GET['delete'])) {
    $request_id = intval($_GET['delete']);
    $type = $_GET['type'] ?? 'stock';

    $table = $type === 'bond' ? 'bond_requests' : ($type === 'real_estate' ? 'real_estate_requests' : ($type === 'commodity' ? 'commodity_requests' : 'stock_requests'));

    // Fetch file path before deleting
    $query = $conn->prepare("SELECT file_path FROM $table WHERE id = ?");
    $query->bind_param("i", $request_id);
    $query->execute();
    $query->bind_result($file_path);
    $query->fetch();
    $query->close();

    if ($file_path) {
        $full_path = __DIR__ . "/downloads/" . basename($file_path);
        if (file_exists($full_path)) {
            unlink($full_path);
        }

        // Delete record
        $delete_query = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $delete_query->bind_param("i", $request_id);
        if ($delete_query->execute()) {
            echo "<script>alert('Request deleted successfully!'); window.location.href='Brequests.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting request!');</script>";
        }
    }
}

// Get logged-in business username
$business_username = $_SESSION['username'];

// Fetch Stock Requests
$stock_stmt = $conn->prepare("SELECT id, file_path, created_at FROM stock_requests WHERE company_name = ?");
$stock_stmt->bind_param("s", $business_username);
$stock_stmt->execute();
$stock_result = $stock_stmt->get_result();

// Fetch Bond Requests
$bond_stmt = $conn->prepare("SELECT id, file_path, submitted_at FROM bond_requests WHERE company_name = ?");
$bond_stmt->bind_param("s", $business_username);
$bond_stmt->execute();
$bond_result = $bond_stmt->get_result();

// Fetch Real Estate Requests
$realEstate_stmt = $conn->prepare("SELECT id, file_path, submitted_at FROM real_estate_requests WHERE company_name = ?");
$realEstate_stmt->bind_param("s", $business_username);
$realEstate_stmt->execute();
$realEstate_result = $realEstate_stmt->get_result();

// Fetch Commodity Requests 
$commodity_stmt = $conn->prepare("SELECT id, file_path, submitted_at FROM commodity_requests WHERE company_name = ?");
$commodity_stmt->bind_param("s", $business_username);
$commodity_stmt->execute();
$commodity_result = $commodity_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investment Requests</title>
    <link rel="icon" type="image/png" href="media/Logo.png">

    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}

.container {
    max-width: 700px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
}

h2 {
    text-align: center;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}

.delete-btn {
    color: red;
    cursor: pointer;
    border: none;
    background: none;
    font-size: 14px;
}

.delete-btn:hover {
    text-decoration: underline;
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
    <h2>Your Stock Requests</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Download</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $stock_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date("Y-m-d H:i", strtotime($row['created_at'])); ?></td>
            <td><a href="Brequests.php?file=<?php echo urlencode(basename($row['file_path'])); ?>">Download</a></td>
            <td><a class="delete-btn" href="Brequests.php?delete=<?php echo $row['id']; ?>&type=stock" onclick="return confirm('Delete this stock request?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="container">
    <h2>Your Bond Requests</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Download</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $bond_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date("Y-m-d H:i", strtotime($row['submitted_at'])); ?></td>
            <td><a href="Brequests.php?file=<?php echo urlencode(basename($row['file_path'])); ?>">Download</a></td>
            <td><a class="delete-btn" href="Brequests.php?delete=<?php echo $row['id']; ?>&type=bond" onclick="return confirm('Delete this bond request?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="container">
    <h2>Your Real Estate Requests</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Download</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $realEstate_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date("Y-m-d H:i", strtotime($row['submitted_at'])); ?></td>
            <td><a href="Brequests.php?file=<?php echo urlencode(basename($row['file_path'])); ?>">Download</a></td>
            <td><a class="delete-btn" href="Brequests.php?delete=<?php echo $row['id']; ?>&type=real_estate" onclick="return confirm('Delete this real estate request?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<div class="container">
    <h2>Your Commodities Requests</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Download</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $commodity_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo date("Y-m-d H:i", strtotime($row['submitted_at'])); ?></td>
            <td><a href="Brequests.php?file=<?php echo urlencode(basename($row['file_path'])); ?>">Download</a></td>
            <td><a class="delete-btn" href="Brequests.php?delete=<?php echo $row['id']; ?>&type=commodity" onclick="return confirm('Delete this commodity request?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
