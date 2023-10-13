<?php
   // Include configuration and functions
   session_start();
   include '../php/config_iskolarosa_db.php';
   include '../php/functions.php';
   
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
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/dashboard_charts.css">

</script>
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
            <strong><a class="nav-link status " href="dashboard_charts.php">CHARTS</a></strong>
        </li>
        <li class="nav-item" style="padding-right: 20px;">
            <strong><a class="nav-link status actives " href="dashboard_table.php">TABLE</a></strong>
        </li>
        <li class="nav-item" style="padding-right: 20px;">
                <strong><a class="nav-link status " href="dashboard_reports.php">REPORTS</a></strong>
            </li>


<!--form for user filtering -->
<div class="filter-form">
    <button id="filterButton" class=" nav-link status" style="padding-right: 20px;" >Filter</button>
    <form id="tableForm" method="post" action="" class="hidden-form">
        <input type="radio" name="tableChoice" value="ceap" id="ceapRadio" onchange="submitSortingForm('asc')">
        <label for="ceapRadio">CEAP</label><br>
        <input type="radio" name="tableChoice" value="lppp" id="lpppRadio" onchange="submitSortingForm('asc')">
        <label for="lpppRadio">LPPP</label><br>
        <div style="background-color: #D9D9D9; border-radius: 2px; height:3px; margin: 2px;"></div>
        <input type="radio" name="sortingChoice" value="asc" id="ascRadio" onchange="submitSortingForm('asc')">
        <label for="ascRadio">Ascending</label><br>
        <input type="radio" name="sortingChoice" value="desc" id="descRadio" onchange="submitSortingForm('desc')">
        <label for="descRadio">Descending</label>
    </form>

</div>
</ul>
</div>
</nav>

<script>
      document.getElementById('filterButton').addEventListener('click', function() {
    var form = document.getElementById('tableForm');
    form.classList.toggle('show-form');
});
    </script>




 <!-- Table for displaying the filtered data -->
     <!-- table for displaying the applicant list -->

<!-- Display the table content -->
<div id="filteredTable">

<!-- sa loob nito ma display yung table-->

</div>



          
      <!-- end applicant list -->
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   

    function submitForm() {
        document.getElementById("tableForm").submit();
    }

    function submitSortingForm(sortingChoice) {
    // Use AJAX to update the table content with sorting choice
    $.ajax({
        url: '../php/update_table.php', // Replace with the actual AJAX endpoint
        method: 'POST',
        data: {
            tableChoice: getSelectedTableChoice(),
            sortingChoice: sortingChoice // Include sorting choice in the data
        },
        success: function (data) {
            $('#filteredTable').html(data);
        }
    });
}
</script>
<script>
    function getSelectedTableChoice() {
        var ceapRadio = document.getElementById('ceapRadio');
        var lpppRadio = document.getElementById('lpppRadio');

        if (ceapRadio.checked) {
            return 'ceap';
        } else if (lpppRadio.checked) {
            return 'lppp';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('tableForm');
        var ceapRadio = document.getElementById('ceapRadio');
        var ascRadio = document.getElementById('ascRadio');

        // Set default values
        ceapRadio.checked = true;
        ascRadio.checked = true;

        // Trigger sorting function
        submitSortingForm('asc', getSelectedTableChoice());

        // Toggle form display
        document.getElementById('filterButton').addEventListener('click', function() {
            form.classList.toggle('filter-form'); // Use 'filter-form' instead of 'hidden-form'
        });
    });
</script>

   </body>
</html>