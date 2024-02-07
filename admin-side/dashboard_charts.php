<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include './php/config_iskolarosa_db.php';
include './php/functions.php';

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


// Check the user's role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Set the display style based on the user's role
$displayStyle = ($role == '3') ? 'block' : 'none';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
    <link rel="icon" href="system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='css/remixicon.css'>
      <link rel='stylesheet' href='css/unpkg-layout.css'>
      <link rel="stylesheet" href="css/side_bar.css">
    <link rel="stylesheet" href="./css/dashboard_charts.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="./js/pie_bar_charts_google.js"></script>
</head>
<body>
<?php
         
         if ($_SESSION['role'] == '3') {
            include './php/head_admin_side_bar.php';
        } else {
            include './php/side_bar_main.php';
        }
?>

<!-- home content-->
<div class="header-label">
<h1>Dashboard</h1>
</div>

<nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px; display: <?php echo $displayStyle; ?>;">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">

            <li class="nav-item" style="padding-right: 25px;">
                <strong><a class="nav-link status actives" href="dashboard_charts.php">CHARTS</a></strong>
            </li>
            <li class="nav-item" style="padding-right: 20px;">
                <strong><a class="nav-link status " href="dashboard_reports.php">REPORTS</a></strong>
            </li>
        </ul>
    </div>
</nav>
<?php
if (mysqli_num_rows($ceapStatusData) === 0 && mysqli_num_rows($ceapGenderData) === 0 && mysqli_num_rows($lpppStatusData) === 0 && mysqli_num_rows($lpppGenderData) === 0) {
    echo '<div class="empty-state">';
    echo '<img src="../empty-state-img/dashboard.png" alt="No records found" class="empty-state-image">';
    echo '<p>No Data.</p>';
    echo '</div>';
} else {
    ?>
    <div class="chart-type">
<!-- Add an HTML element for chart type selection -->
<label for="chartType">Select Chart Type:</label>
<select id="chartType" onchange="updateChart()">
    <option value="pie">Pie Chart</option>
    <option value="bar">Bar Graph</option>
</select>
    </div>

<!-- Create separate chart containers for each chart -->
<div class="outside-chart-container">
<div class="chart-container">
    <div class="chart-column">
    <div class="chart-label">CEAP APPLICANT STATUS</div> <!-- Add chart label -->
        <div id="ceapStatusChart" class="chart"></div>
    </div>

    <div class="chart-column">
    <div class="chart-label">CEAP APPLICANT SEX</div> <!-- Add chart label -->

        <div id="ceapGenderChart" class="chart"></div>
    </div>
</div>

<div class="chart-container">
    <div class="chart-column">
    <div class="chart-label">LPPP APPLICANT STATUS</div> <!-- Add chart label -->

        <div id="lpppStatusChart" class="chart"></div>
    </div>
    <div class="chart-column">
    <div class="chart-label">LPPP APPLICANT SEX</div> <!-- Add chart label -->

        <div id="lpppGenderChart" class="chart"></div>
    </div>
</div>
</div>
<!-- Add this PHP code to display an image if there's no data -->
<?php
    echo '<script>drawCharts();</script>';
}
?>
<!-- End of code to display the image or charts -->

<!-- partial -->
      <script src='js/unpkg-layout.js'></script><script  src="./js/side_bar.js"></script>

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
