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
   
   // Set variables
   $currentPage = 'archive';
   $currentSubPage = '';
   
   
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT * , t.status
   FROM lppp_reg_form p
   INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
   WHERE is_deleted = 1;
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
      <link rel="stylesheet" href="./css/ceap_list.css">
   <style>
    
/* Navigation styles */
.navbar-collapse {
text-align: center;
width: 100%; /* Set the width to 100% */
height: 60px; 
}

.navbar-nav {
display: flex;
align-items: center; /* Vertically center the content */
justify-content: center; /* Horizontally center the content */
margin: 0;
padding: 0;
height: 100%; /* Make sure the navbar-nav takes full height of the navbar-collapse */
}

.nav-item {
display: inline-block;
padding-right: 20px;
}

.nav-link {
text-decoration: none;
color: white;
font-weight: bold;
display: block;
padding: 10px 15px;
border-radius: 15px;
width: 150px;
background-color: #8F8F8F;
}

.nav-link:hover {
background-color: #400000;
color: white;
}

.actives {
background-color: #400000;
}
    </style>
    </head>
   <body>
      <?php 
          include './php/head_admin_side_bar.php';

         ?>
         

      <!-- home content-->    
      <div class="header-label">
<h1>Archive</h1>
</div>
<nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
         <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
               <li class="nav-item" style="padding-right: 25px;">
                  <strong><a class="nav-link status " href="archive_list.php">CEAP</a></strong>
               </li>
               <li class="nav-item" style="padding-right: 20px;">
                  <strong><a class="nav-link status actives" href="archive_list_lppp.php">LPPP</a></strong>
               </li>
            </ul>
         </div>
      </nav>
      <div class="form-group">
      <input type="text" name="search-bar" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">
      </div>
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">Archive Master List</h2>
         <div class="applicant-info">
            <table>
               <tr>
                  <th>NO.</th>
                  <th>CONTROL NUMBER</th>
                  <th>LAST NAME</th>
                  <th>FIRST NAME</th>
                  <th>BARANGAY</th>
                  <th>STATUS</th>
               </tr>
               <?php
                  $counter = 1;
                  
                           // Display applicant info using a table
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo '<tr class="applicant-row contents" onclick="seeMore(\'' . $row['lppp_reg_form_id'] . '\')" style="cursor: pointer;">';
                              echo '<td><strong>' . $counter++ . '</strong></td>';
                              echo '<td>' . strtoupper($row['control_number']) . '</td>';
                              echo '<td>' . strtoupper($row['last_name']) . '</td>';
                              echo '<td>' . strtoupper($row['first_name']) . '</td>';
                              echo '<td>' . strtoupper($row['barangay']) . '</td>';
                              echo '<td>' . strtoupper($row['status']) . '</td>';
                              echo '</tr>';
                           }
                           ?>
            </table>
            <div id="noApplicantFound" style="display: none; text-align: center; margin-top: 10px;">
               No applicant found.
            </div>
         </div>
      </div>
      <!-- end applicant list -->
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>
      <script>
         function seeMore(id) {
             // Redirect to a page where you can retrieve the reserved data based on the given ID
             window.location.href = "archive_information_lppp.php?lppp_reg_form_id=" + id;
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
         
                
         function formatInput(inputElement) {
    // Remove multiple consecutive white spaces
    inputElement.value = inputElement.value.replace(/\s+/g, ' ');

    // Convert input text to uppercase
    inputElement.value = inputElement.value.toUpperCase();
  }

         
      </script>
   </body>
</html>