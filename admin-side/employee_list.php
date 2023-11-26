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
   
 // Check the user's role
if ($_SESSION['role'] === 3) {
    // Set variables for head admin
    $currentPage = "configuration";
    $currentSubPage = "employee";
} else {
    // Set variables for other roles
    $currentPage = "configuration";
    $currentSubPage = "";
}

  
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT * 
   FROM employee_list
   SQL;
   
   $result = mysqli_query($conn, $query);
   ?>




<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='./css/remixicon.css'>
      <link rel='stylesheet' href='./css/unpkg-layout.css'>
      <link rel="stylesheet" href="./css/side_bar.css">
      <link rel="stylesheet" href="./css/ceap_configuration.css">
      <link rel="stylesheet" href="./css/ceap_list.css">
   </head>
   <body>
      <?php 
          include './php/head_admin_side_bar.php';
         ?>
         

      <!-- home content-->    
      <div class="header-label">
<h1>Employee Configuration</h1>
</div>
      <nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
<div class="collapse navbar-collapse">
    <ul class="navbar-nav">
        <li class="nav-item" style="padding-right: 25px;">
            <strong><a class="nav-link status" href="employee_configuration.php">ADD EMPLOYEE</a></strong>
        </li>
        <li class="nav-item" style="padding-right: 20px;">
            <strong><a class="nav-link status actives" href="employee_list.php">EMPLOYEE LIST</a></strong>
        </li>
    </ul>
</div>
</nav> 
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">EMPLOYEE MASTER LIST</h2>
         <div class="applicant-info">
            <table>
               <tr>
                  <th>NO.</th>
                  <th>EMPLOYEE ID NO.</th>
                  <th>DEPARTMENT</th>
                  <th>LAST NAME</th>
                  <th>FIRST NAME</th>
               </tr>
               <?php
                  $counter = 1;
                  
                           // Display applicant info using a table
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo '<tr class="applicant-row contents" onclick="seeMore(\'' . $row['employee_id'] . '\')" style="cursor: pointer;">';
                              echo '<td><strong>' . $counter++ . '</strong></td>';
                              echo '<td>' . strtoupper($row['employee_id_no']) . '</td>';
                              echo '<td>' . strtoupper($row['role_id']) . '</td>';
                              echo '<td>' . strtoupper($row['last_name']) . '</td>';
                              echo '<td>' . strtoupper($row['first_name']) . '</td>';
                              echo '</tr>';
                           }
                           ?>
            </table>
          
         </div>
      </div>
      <!-- end applicant list -->
      <footer class="footer">
      </footer>
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>
      <script>
         function seeMore(id) {
             // Redirect to a page where you can retrieve the reserved data based on the given ID
             window.location.href = "employee_configuration_information.php?employee_id=" + id;
         }
         
      </script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
         $(document).ready(function() {
             // Add an event listener to the search input field
             $('#search').on('input', function() {
                 searchApplicants();
             });
         });
         
         function searchApplicants() {
             var searchValue = $('#search').val().toUpperCase();
             var found = false; // Flag to track if any matching applicant is found
             $('.contents').each(function () {
                 var controlNumber = $(this).find('td:nth-child(2)').text().toUpperCase();
                 var lastName = $(this).find('td:nth-child(3)').text().toUpperCase();
                 if (searchValue.trim() === '' || controlNumber.includes(searchValue) || lastName.includes(searchValue)) {
                     $(this).show();
                     found = true;
                 } else {
                     $(this).hide();
                 }
             });
         
             // Display "No applicant found" message if no matching applicant is found
             if (!found) {
                 $('#noApplicantFound').show();
             } else {
                 $('#noApplicantFound').hide();
             }
         }
         
         
         
      </script>
   </body>
</html>