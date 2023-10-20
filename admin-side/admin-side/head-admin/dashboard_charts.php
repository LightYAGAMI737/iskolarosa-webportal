<?php
// Start the session
session_start();

include '../php/config_iskolarosa_db.php';
include '../php/functions.php';

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

// Define the required permission
$requiredPermission = 'view_ceap_applicants';

// Define an array of required permissions for different pages
$requiredPermissions = [
    'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
    'edit_users' => 'You do not have permission to edit applicants.',
    'delete_applicant' => 'You do not have permission to delete applicants.',
];

// Check if the required permission exists in the array
if (!isset($requiredPermissions[$requiredPermission])) {
    echo 'Invalid permission specified.';
    exit();
}

// Call the hasPermission function to check the user's permission
if (!hasPermission($_SESSION['role'], $requiredPermission)) {
    echo $requiredPermissions[$requiredPermission];
    exit();
}

$currentPage = "dashboard";
$currentSubPage = "";

// Fetch CEAP applicant status counts
$ceapStatusQuery = "SELECT status, COUNT(*) as count FROM temporary_account GROUP BY status";
$ceapStatusData = mysqli_query($conn, $ceapStatusQuery);

// Fetch CEAP applicant gender counts
$ceapGenderQuery = "SELECT gender, COUNT(*) as count FROM ceap_reg_form GROUP BY gender";
$ceapGenderData = mysqli_query($conn, $ceapGenderQuery);

// Similarly, fetch data for LPPP applicant status and gender
$lpppStatusQuery = "SELECT status, COUNT(*) as count FROM lppp_temporary_account GROUP BY status";
$lpppStatusData = mysqli_query($conn, $lpppStatusQuery);

// Fetch lppp applicant gender counts
$lpppGenderQuery = "SELECT gender, COUNT(*) as count FROM lppp_reg_form GROUP BY gender";
$lpppGenderData = mysqli_query($conn, $lpppGenderQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
    <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
    <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
    <link rel="stylesheet" href="../css/side_bar.css">
    <link rel="stylesheet" href="../css/dashboard_charts.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px;
            text-align: center;
        }

        .chart-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            margin: 10px;
            padding: 10px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        .chart {
            width: 500px;
            height: 300px;
        }

        /* Toggle switch style */
        input[type='checkbox'] {
            display: none;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 90px;
            height: 30px;
            background-color: #ccc;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .toggle-label {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
            opacity: 0.5;
            transition: opacity 0.3s ease-in-out;
        }

        .toggle-label-on {
            left: 10px;
        }

        .toggle-label-off {
            right: 10px;
            color: #000000;
        }

        .toggle-slider {
            position: absolute;
            top: 0;
            left: 5px;
            width: 30px;
            height: 30px;
            background-color: #FEC021;
            z-index: 999;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out;
        }

        /* Toggled state */
        .toggle-checkbox:checked + .toggle-switch {
            background-color: #A5040A;
        }

        .toggle-checkbox:checked + .toggle-switch .toggle-label {
            opacity: 1;
        }

        .toggle-checkbox:checked + .toggle-switch .toggle-slider {
            transform: translateX(48px);
            background-color: #FEC021;
        }

        .toggle-checkbox:checked + .toggle-switch .toggle-label-off {
            display: none;
        }

        /* CSS to hide the "HIDE" label when checkbox is not checked */
        .toggle-checkbox:not(:checked) + .toggle-switch .toggle-label-on {
            display: none;
        }
    </style>

    <script type="text/javascript" src="./js/pie_bar_charts_google.js"></script>
</head>
<body>
<?php
include 'head_admin_side_bar.php';
?>

<!-- home content-->
<div class="header-label">
<h1>Dashboard</h1>
</div>
<nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">

            <li class="nav-item" style="padding-right: 25px;">
                <strong><a class="nav-link status actives" href="dashboard_charts.php">CHARTS</a></strong>
            </li>
            <li class="nav-item" style="padding-right: 20px;">
                <strong><a class="nav-link status " href="dashboard_table.php">TABLE</a></strong>
            </li>
            <li class="nav-item" style="padding-right: 20px;">
                <strong><a class="nav-link status " href="dashboard_reports.php">REPORTS</a></strong>
            </li>
        </ul>
    </div>
</nav>
       
<!-- Add an HTML element for chart type selection -->
<label for="chartType">Select Chart Type:</label>
<select id="chartType" onchange="updateChart()">
    <option value="pie">Pie Chart</option>
    <option value="bar">Bar Graph</option>
</select>
<!-- Create separate chart containers for each chart -->
<div class="chart-container">
    <div class="chart-column">
    <div class="chart-label">CEAP STATUS</div> <!-- Add chart label -->
        <div id="ceapStatusChart" class="chart"></div>
    </div>
    <div class="chart-column">
    <div class="chart-label">CEAP GENDER</div> <!-- Add chart label -->

        <div id="ceapGenderChart" class="chart"></div>
    </div>
</div>

<div class="chart-container">
    <div class="chart-column">
    <div class="chart-label">LPPP STATUS</div> <!-- Add chart label -->

        <div id="lpppStatusChart" class="chart"></div>
    </div>
    <div class="chart-column">
    <div class="chart-label">LPPP GENDER</div> <!-- Add chart label -->

        <div id="lpppGenderChart" class="chart"></div>
    </div>
</div>

<canvas id="ceapStatusCanvas" class="chart" style="display: none;"></canvas>
<canvas id="ceapGenderCanvas" class="chart" style="display: none;"></canvas>
<canvas id="lpppStatusCanvas" class="chart" style="display: none;"></canvas>
<canvas id="lpppGenderCanvas" class="chart" style="display: none;"></canvas>

<!-- partial -->
<script src='https://unpkg.com/@popperjs/core@2'></script>
<script src="../js/side_bar.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Initialize Google Charts library
    google.charts.load('current', { 'packages': ['corechart'] });

    // Define separate data variables for CEAP and LPPP
    var ceapStatusData = [
        ['Status', 'Count'],
        <?php
        while ($row = mysqli_fetch_assoc($ceapStatusData)) {
            echo "['{$row['status']}', {$row['count']}],";
        }
        ?>
    ];

    var ceapGenderData = [
        ['Gender', 'Count'],
        <?php
        while ($row = mysqli_fetch_assoc($ceapGenderData)) {
            echo "['{$row['gender']}', {$row['count']}],";
        }
        ?>
    ];

    var lpppStatusData = [
        ['Status', 'Count'],
        <?php
        while ($row = mysqli_fetch_assoc($lpppStatusData)) {
            echo "['{$row['status']}', {$row['count']}],";
        }
        ?>
    ];

    var lpppGenderData = [
        ['Gender', 'Count'],
        <?php
        while ($row = mysqli_fetch_assoc($lpppGenderData)) {
            echo "['{$row['gender']}', {$row['count']}],";
        }
        ?>
    ];

    // Set the default chart type
    var selectedChartType = 'pie';

    
    // Handle chart type change
    function updateChart() {
        selectedChartType = document.getElementById('chartType').value;
        drawCharts();
    }

    // Draw the charts
    function drawCharts() {
        var chartOptions = {
            backgroundColor: 'white',
            legend: { position: 'right' }
        };

        var ceapStatusChart = new google.visualization.PieChart(document.getElementById('ceapStatusChart'));
var ceapGenderChart = new google.visualization.PieChart(document.getElementById('ceapGenderChart'));
var lpppStatusChart = new google.visualization.PieChart(document.getElementById('lpppStatusChart'));
var lpppGenderChart = new google.visualization.PieChart(document.getElementById('lpppGenderChart'));

var options = {
    backgroundColor: 'white',
    legend: { position: 'right' },
    is3D: true, // Enable 3D effect
    pieSliceText: 'percentage', // Show percentage labels
    pieSliceTextStyle: {
        color: 'white',
        bold: true
    }
};

        if (selectedChartType === 'bar') {
            ceapStatusChart = new google.visualization.BarChart(document.getElementById('ceapStatusChart'));
            ceapGenderChart = new google.visualization.BarChart(document.getElementById('ceapGenderChart'));
            lpppStatusChart = new google.visualization.BarChart(document.getElementById('lpppStatusChart'));
            lpppGenderChart = new google.visualization.BarChart(document.getElementById('lpppGenderChart'));
        }

       // Draw the charts with the specified options
ceapStatusChart.draw(google.visualization.arrayToDataTable(ceapStatusData), options);
ceapGenderChart.draw(google.visualization.arrayToDataTable(ceapGenderData), options);
lpppStatusChart.draw(google.visualization.arrayToDataTable(lpppStatusData), options);
lpppGenderChart.draw(google.visualization.arrayToDataTable(lpppGenderData), options);
    }

    // Initial chart drawing
    google.charts.setOnLoadCallback(drawCharts);

</script>

</body>
</html>
