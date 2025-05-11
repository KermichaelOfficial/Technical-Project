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

$sql = "SELECT * FROM done_requests ORDER BY completed_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Requests</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <style>
        body.dark-mode h2 {
    color:rgb(0, 0, 0); 
}
        body { font-family: Arial, sans-serif; background-color: #f5f8fa; }
        .container { max-width: 900px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ddd; }
        .request { padding: 15px; border-bottom: 1px solid #ddd; }
        .request h3 { margin: 0; color: #1da1f2; }
        .request p { margin: 5px 0; color: #555; }
        .logo { width: 50px; height: 50px; border-radius: 5px; margin-right: 10px; }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .sidebar, body.dark-mode .right-sidebar {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
        body.dark-mode .sidebar a {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333333;
        }
        body.dark-mode .sidebar a:hover {
            background-color: #333333;
            color: #e0e0e0;
        }
        body.dark-mode .main-content {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .tweet {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid #333333;
        }
        body.dark-mode .search-bar input {
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #555555;
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
    <h2>Completed Requests</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="request">
            <h3><?php echo $row['company_name']; ?> (<?php echo $row['industry']; ?>)</h3>
            <p><strong>Submitted by:</strong> <?php echo $row['username']; ?></p>
            <p><strong>Investment Type:</strong> <?php echo $row['investment_type']; ?></p>
            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $row['address']; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br($row['description']); ?></p>
            <p><strong>Submitted At:</strong> <?php echo $row['submitted_at']; ?></p>
            <p><strong>Completed At:</strong> <?php echo $row['completed_at']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
