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
   
   // Set variables
   $currentStatus = 'in progress';
   $currentPage = 'lppp_list';
   
   // Get the barangay parameter from the URL
   if (isset($_GET['barangay'])) {
       $currentSubPage = $_GET['barangay'];
   } else {
       $currentSubPage = 'aplaya';
   }
   
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT t.*, 
          UPPER(p.first_name) AS first_name, 
          UPPER(p.last_name) AS last_name, 
          UPPER(p.barangay) AS barangay, 
          p.control_number, 
          p.date_of_birth, 
          UPPER(t.status) AS status, UPPER(p.grade_level) AS grade_level 
   FROM lppp_reg_form p
   INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
   SQL;
   
   $result = mysqli_query($conn, $query);
   ?>




<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/ceap_list.css">
   </head>
   <body>
      <?php 
          include 'head_admin_side_bar.php';

         ?>
         

      <!-- home content-->   
      <div class="header-label">
<h1>Libreng Pagpapaaral sa Pribadong Paaralan (LPPP)</h1>
</div> 
      <div class="form-group">
      <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">

         <button type="button" class="btn btn-primary" onclick="searchApplicants()">Search</button>
      </div>
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">LPPP APPLICANT MASTER LIST</h2>
         <div class="applicant-info">
            <table>
               <tr>
                  <th>NO.</th>
                  <th>CONTROL NUMBER</th>
                  <th>LAST NAME</th>
                  <th>FIRST NAME</th>
                  <th>STATUS</th>
                  <th>GRADE LEVEL</th>
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
                              echo '<td>' . strtoupper($row['status']) . '</td>';
                              echo '<td>' . strtoupper($row['grade_level']) . '</td>';
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
      <footer class="footer">
      </footer>
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>
      <script>
         function seeMore(id) {
             // Redirect to a page where you can retrieve the reserved data based on the given ID
             window.location.href = "lppp_information.php?lppp_reg_form_id=" + id;
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