<?php
  session_start();
  include '../../../php/config_iskolarosa_db.php';
  include '../../../php/functions.php';
  
  // Description: This script handles permission checks and retrieves applicant information.
  
  // Check if the session is not set (user is not logged in)
  if (!isset($_SESSION['username'])) {
      echo 'You need to log in to access this page.';
      exit();
  }
  
  // Define the required permission
  $requiredPermission = 'set_interview_date';
  
  // Define an array of required permissions for different pages
  $requiredPermissions = [
      'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
      'edit_users' => 'You do not have permission to edit applicant.',
      'delete_applicant' => 'You do not have permission to delete applicant.',
      'set_interview_date' => 'You do not have permission to set an interview for applicant.',
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
  
$currentStatus = 'grantee';
$currentPage = 'lppp_list';
$currentSubPage = 'GRADE 9';

include '../../../php/config_iskolarosa_db.php';


// Retrieve applicant info using JOIN query for the specified barangay
// Modify the SQL query to include the selected barangay
$query = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, UPPER(t.status) AS status, UPPER(p.grade_level) AS grade_level
FROM lppp_reg_form p
INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
WHERE t.status = 'Grantee' AND p.grade_level = '9'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="../../../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='../../../css/remixicon.css'>
      <link rel='stylesheet' href='../../../css/unpkg-layout.css'>
      <link rel="stylesheet" href="../../../css/side_bar.css">
      <link rel="stylesheet" href="../../../css/ceap_list.css">
   </head>
   <body>
      <?php         
                include '../../side_bar_lppp_grantee.php';


         ?>
              
      <!-- home content-->    
      <div class="form-group">
      <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">

    <button type="button" class="btn btn-primary" onclick="searchApplicants()">Search</button>

   </div>

<!-- table for displaying the applicant list -->

<div class="background">
<h2 style="text-align: center">LPPP GRANTEE LIST | <?php echo $currentSubPage ?></h2>
<?php
            if (mysqli_num_rows($result) === 0) {
              // Display the empty state image and message
              echo '<div class="empty-state">';
              echo '<img src="../../../../empty-state-img/no_applicant.png" alt="No records found" class="empty-state-image">';
              echo '<p>No applicant found.</p>';
              echo '</div>';
            } else {
              ?>
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
   <?php } ?>

<!-- end applicant list -->

        
         <footer class="footer">
         </footer>
         </main>
         <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script><script  src="../../../js/side_bar.js"></script>


      
<script>
 
 function seeMore(id) {
     // Redirect to a page where you can retrieve the reserved data based on the given ID
     window.location.href = "lppp_grantee_information.php?lppp_reg_form_id=" + id;
 }
 function formatInput(inputElement) {
    // Remove multiple consecutive white spaces
    inputElement.value = inputElement.value.replace(/\s+/g, ' ');

    // Convert input text to uppercase
    inputElement.value = inputElement.value.toUpperCase();
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