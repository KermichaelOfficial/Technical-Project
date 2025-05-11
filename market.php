<?php
$timeout_duration = 600; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?session_timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
// API URL and Parameters
$url = "https://www.searchapi.io/api/v1/search";
$params1 = [
    "engine" => "google_finance",
    "q" => "NI225:INDEXNIKKEI",
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params2 = [
    "engine" => "google_finance",
    "q" => "TSLA:NASDAQ",
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params3 = [
    "engine" => "google_finance",
    "q" => "AAPL:NASDAQ",
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params4 = [
    "engine" => "google_finance",
    "q" => "META:NASDAQ",
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params5 = [
    "engine" => "google_finance",
    "q" => "VWAGY:OTCMKTS", 
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params6 = [
    "engine" => "google_finance",
    "q" => "DAL:NYSE", 
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];
$params7 = [
    "engine" => "google_finance",
    "q" => "AMZN:NASDAQ", 
    "window" => "MAX",
    "api_key" => "Q7ZeWhjGzuvRvVnpUC5ZVx3r"
];

function fetchStockData($params) {
    $url = "https://www.searchapi.io/api/v1/search?" . http_build_query($params);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => ["Accept: application/json"]
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

$nikkeiData = fetchStockData($params1);
$teslaData = fetchStockData($params2);
$appleData = fetchStockData($params3);
$facebookData = fetchStockData($params4);
$volkswagenData = fetchStockData($params5);
$deltaData = fetchStockData($params6);
$amazonData = fetchStockData($params7);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Market Data</title>
    <link rel="icon" type="image/png" href="media/Logo.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding-top: 50px;
        }
        .market-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e1e8ed;
            padding-bottom: 20px;
        }
        .market-header h2 {
            margin: 0;
        }
        .stock-table, .event-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        .stock-table th, .stock-table td, .event-table th, .event-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .stock-table th, .event-table th {
            background-color: #1da1f2;
            color: white;
        }
        .positive { color: green; }
        .negative { color: red; }
        .chart-container {
            margin-top: 20px;
            width: 100%;
            height: 400px;
        }
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        .dark-mode .container {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }
        .dark-mode .notification {
            background-color: #1e1e1e;
            border-bottom: 1px solid #333;
        }
        .dark-mode .notification:hover {
            background-color: #333;
        }
        .dark-mode .popup {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }
        .dark-mode .popup button {
            background-color: #333;
            color: #e0e0e0;
            border: 1px solid #555;
        }
        .dark-mode .popup .close-btn { background-color: red; }
        .dark-mode .popup .go-btn { background-color: #1da1f2; }
        .dark-mode .popup .thanks-btn { background-color: green; }
                .dark-mode-toggle {
            background-color: #1da1f2;
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 20px;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .dark-mode-toggle:hover {
            background-color: #0d95e8;
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
        <div class="market-header">
            <h2>Live Market Data</h2>
        </div>
<!-- Key Events Section -->
<h3>Key Events</h3>
        <table class="event-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Source</th>
                    <th>Price Change</th>
                </tr>
            </thead>
            <tbody id="event-table-body">
                <tr><td colspan="4">Loading events...</td></tr>
            </tbody>
        </table>

        <!-- Graph Section -->
        <div class="chart-container">
            <canvas id="stockChart"></canvas>
        </div>
    </div>

    <script>
    const nikkeiData = <?php echo json_encode($nikkeiData["graph"] ?? []); ?>;
    const teslaData = <?php echo json_encode($teslaData["graph"] ?? []); ?>;
    const appleData = <?php echo json_encode($appleData["graph"] ?? []); ?>;
    const facebookData = <?php echo json_encode($facebookData["graph"] ?? []); ?>;
    const volkswagenData = <?php echo json_encode($volkswagenData["graph"] ?? []); ?>;
    const deltaData = <?php echo json_encode($deltaData["graph"] ?? []); ?>;
    const amazonData = <?php echo json_encode($amazonData["graph"] ?? []); ?>;



    const ctx = document.getElementById('stockChart').getContext('2d');
    const labels = nikkeiData.map(point => new Date(point.date).toLocaleDateString());
    const nikkeiPrices = nikkeiData.map(point => point.price);
    const teslaPrices = teslaData.map(point => point.price);
    const applePrices = appleData.map(point => point.price);
    const facebookPrices = facebookData.map(point => point.price);
    const volkswagenPrices = volkswagenData.map(point => point.price);
    const deltaPrices = deltaData.map(point => point.price);
    const amazonPrices = amazonData.map(point => point.price);



    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nikkei 225',
                    data: nikkeiPrices,
                    borderColor: 'gold',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Tesla',
                    data: teslaPrices,
                    borderColor: 'red',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Apple',
                    data: applePrices,
                    borderColor: 'green',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Facebook (Meta)',
                    data: facebookPrices,
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Volkswagen',
                    data: volkswagenPrices,
                    borderColor: 'orange',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Delta Airlines',
                    data: deltaPrices,
                    borderColor: 'cyan',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Amazon',
                    data: amazonPrices,
                    borderColor: 'brown',
                    borderWidth: 2,
                    fill: false
                }

            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks: { autoSkip: true, maxTicksLimit: 10 } },
                y: { beginAtZero: false }
            },
            plugins: { legend: { display: true } }
        }
    });
</script>
</body>
</html>

