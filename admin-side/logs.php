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
   $requiredPermission = 'delete_applicant';
   
   // Define an array of required permissions for different pages
   $requiredPermissions = [
       'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
       'edit_users' => 'You do not have permission to edit applicant.',
       'delete_applicant' => 'You do not have permission access this page.',
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
   $currentPage = 'logs';
   $currentSubPage = '';

date_default_timezone_set('Asia/Manila');

// Fetch logs from employee_logs
$queryEmployeeLogs = "SELECT * FROM employee_logs ORDER BY timestamp DESC";
$resultEmployeeLogs = mysqli_query($conn, $queryEmployeeLogs);

$query = <<<SQL
SELECT
    a.applicant_status_logs_id,
    a.previous_status,
    a.updated_status,
    a.ceap_reg_form_id,
    a.timestamp,
    t.control_number,
    e.employee_username
FROM
    applicant_status_logs AS a
JOIN
    ceap_reg_form AS t ON a.ceap_reg_form_id = t.ceap_reg_form_id
JOIN
    employee_logs AS e ON a.employee_logs_id = e.employee_logs_id
ORDER BY
    a.timestamp DESC
SQL;

// Execute the SQL query and handle errors
$resultApplicantStatusLogs = mysqli_query($conn, $query);

if (!$resultApplicantStatusLogs) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

?>




<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='./css/remixicon.css'>
      <link rel='stylesheet' href='./css/unpkg-layout.css'>
      <link rel="stylesheet" href="./css/side_bar.css">
      <link rel="stylesheet" href="./css/ceap_list.css">
      <link rel="stylesheet" href="./css/logs.css">
      <style>
    .truncate-text {
        max-width: 150px; /* Set the maximum width for the column */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
   </head>
   <body>
      <?php 
          include './php/head_admin_side_bar.php';

         ?>
         

      <!-- home content-->    
      <div class="header-label">
<h1>Activity Logs</h1>
</div>
      <!-- <div class="form-group">
      <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">

         <button type="button" class="btn btn-primary" onclick="searchApplicants()">Search</button>
      </div> -->
   <!-- Table for displaying employee_logs -->
<div class="background">
    <h2 style="text-align: center">Employee Logs</h2>
    <div class="employee-logs-table">
        <div class="table-container"> <!-- Added a container div for scrolling -->
        <table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Username</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 1; // Initialize the counter
        while ($row = mysqli_fetch_assoc($resultEmployeeLogs)) {
            echo "<tr>";
            echo "<td style='text-align: center'>" . $counter . "</td>"; // Display the counter
            echo "<td>" . $row['employee_username'] . "</td>";
            
            $actionText = $row['action'];
            $maxChars = 60;

            if (strlen($actionText) > $maxChars) {
                $actionText = substr($actionText, 0, $maxChars) . "..."; // Truncate and add ellipsis
            }

            echo "<td class='truncate-text'>" . $actionText . "</td>";

            $formattedTimestamp =  date('d/m/y, h:i A',strtotime($row['timestamp']));
            echo "<td>" . $formattedTimestamp . "</td>";
            echo "</tr>";
            $counter++; // Increment the counter for the next row
        }
        ?>
    </tbody>
</table>
        </div>
    </div>
</div>

<!-- Applicant Status Logs Table -->
<div class="background applicant-status-logs-table">
    <h2 style="text-align: center">Applicant Status Logs</h2>
    <div class="table-container"> <!-- Added a container div for scrolling -->
        <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Control Number</th>
                        <th>Previous Status</th>
                        <th>Updated Status</th>
                        <th>Updated By</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $counter = 1; // Initialize the counter
                while ($row = mysqli_fetch_assoc($resultApplicantStatusLogs)) {
                    echo "<tr>";
                    echo "<td style='text-align: center'>" . $counter . "</td>"; // Display the counter
                    echo "<td>" . $row['control_number'] . "</td>";
                    echo "<td>" . $row['previous_status'] . "</td>";
                    echo "<td>" . $row['updated_status'] . "</td>";
                    echo "<td>" . $row['employee_username'] . "</td>";
                    $formattedTimestamp =  date('d/m/y, h:i A',strtotime($row['timestamp']));
                    echo "<td>" . $formattedTimestamp . "</td>";
                    echo "</tr>";
                    $counter++; // Increment the counter for the next row
                }
                ?>
                </tbody>
        </table>
    </div>
</div>

<!-- End applicant_status_logs table -->
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
    // $(document).ready(function() {
    //     // Add an event listener to the search input field
    //     $('#search').on('input', function() {
    //         searchApplicants();
    //     });
    // });

    // function searchApplicants() {
    //     var searchValue = $('#search').val().toUpperCase();
    //     var found = false; // Flag to track if any matching applicant is found

    //     // Loop through rows in both tables
    //     $('.applicant-status-logs-table tbody tr').each(function() {
    //         var controlNumber = $(this).find('td:nth-child(2)').text().toUpperCase(); // Control Number
    //         var previousStatus = $(this).find('td:nth-child(3)').text().toUpperCase(); // Previous Status
    //         var updatedStatus = $(this).find('td:nth-child(4)').text().toUpperCase(); // Updated Status
    //         var employeeUsername = $(this).find('td:nth-child(5)').text().toUpperCase(); // Employee Username

    //         // Check if any of the columns contain the search value
    //         if (
    //             searchValue.trim() === '' ||
    //             controlNumber.includes(searchValue) ||
    //             previousStatus.includes(searchValue) ||
    //             updatedStatus.includes(searchValue) ||
    //             employeeUsername.includes(searchValue)
    //         ) {
    //             $(this).show();
    //             found = true;
    //         } else {
    //             $(this).hide();
    //         }
    //     });

    //     // Display "No applicant found" message if no matching applicant is found
    //     if (!found) {
    //         $('#noApplicantFound').show();
    //     } else {
    //         $('#noApplicantFound').hide();
    //     }
    // }
                

         
      </script>
   </body>
</html>