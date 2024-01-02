<?php
   // Include configuration and functions
   session_start();
   include './php/config_iskolarosa_db.php';
   include './php/functions.php';
   
   // Description: This script handles permission checks and retrieves applicant information.
   
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
       'edit_users' => 'You do not have permission to edit applicant.',
       'delete_applicant' => 'You do not have permission to delete applicant.',
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


  
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT * 
   FROM ceap_reg_form
   SQL;
   
   $result = mysqli_query($conn, $query);
   
   ?>
<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
      <link rel="icon" href="system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='./css/remixicon.css'>
      <link rel='stylesheet' href='./css/unpkg-layout.css'>
      <link rel="stylesheet" href="./css/side_bar.css">
      <link rel="stylesheet" href="./css/dashboard_charts.css">
      <link rel="stylesheet" href="./css/dashboard_reports.css">

   </head>
   <body>
      <?php 
          include './php/head_admin_side_bar.php';
         ?>
         

      <!-- home content-->  
      <div class="header-label">
<h1>Dashboard</h1>
</div>  
      <nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">

            <li class="nav-item" style="padding-right: 25px;">
                <strong><a class="nav-link status " href="dashboard_charts.php">CHARTS</a></strong>
            </li>
            <li class="nav-item" style="padding-right: 20px;">
                <strong><a class="nav-link status actives" href="dashboard_reports.php">REPORTS</a></strong>
            </li>
        </ul>
    </div>
</nav>

<div class="background">
    <form id="downloadForm" action="./php/generate_report.php" method="POST">
        <div class="container-reports">
            <div class="programs">
        <div class="label-header">
            <label for="sortingOrder">Program:</label>
        </div>
        <div class="ceaplpppradio">
            <label for="ceapRadio">
                <input type="radio" id="ceapRadio" name="dataChoice" value="ceap" checked>
                CEAP
            </label>
            <label for="lpppRadio">
                <input type="radio" id="lpppRadio" name="dataChoice" value="lppp">
                LPPP
            </label>
        </div>
        </div>
        <div class="sorting-order">
        <div class="label-header">
            <label for="sortingOrder">Sort Order:</label>
        </div>
        <div class="sorting-options">
            <input type="radio" id="ascending" name="sortingOrder" value="ASC" checked>
            <label for="ascending">Ascending</label>
            <input type="radio" id="descending" name="sortingOrder" value="DESC">
            <label for="descending">Descending</label>
        </div>
        </div>
        <div class="datato-download">
        <div class="label-header">
            <label for="filterOption">Choose Filtering Options:</label>
        </div>
        <div class="filterselectdata">
    <select id="filterOption" name="filterOption[]" class="select-dropdown">
        <option value="listOfApplicants">List of Applicants Name</option>
        <option value="totalCountGender">Total Count of Sex (Male/Female)</option>
        <option value="totalCountStatus">Total Count of Applicant Status</option>
        <option value="totalCountBarangay">Total Count of Applicant each Barangay</option>
    </select>
</div>
        </div>
        <div class="submitBTN">
        <button type="submit">Download Data</button>
        </div>
        </div>
    </form>
</div>


      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
      <script>
    function generatePDF() {
        // Redirect to generate_chart_pdf.php
        window.location.href = 'generate_report.php';
    }
</script>

   </body>
</html>