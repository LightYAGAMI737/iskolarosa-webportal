<?php
   // Include configuration and functions
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
   $currentStatus = 'verified';
   $currentPage = 'ceap_list';
   $currentBarangay ='APLAYA';
   $currentSubPage = 'old applicant';
   
   if (isset($_POST['saveBtn'])) {
   // Process and update interview dates
   $interviewDate = $_POST['interview_date'];
   $interview_hours = $_POST['interview_hours'];
   $interview_minutes = $_POST['interview_minutes'];
   $interview_ampm = $_POST['interview_ampm'];
   $limit = $_POST['limit'];
   
   if (!empty($interviewDate) && !empty($interview_hours) && !empty($interview_minutes) && !empty($interview_ampm) && !empty($limit)) {
       $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
       $limit = intval($limit);
   
       $qualifiedQuery = "SELECT *
       FROM ceap_personal_account p
       WHERE status = 'Verified' AND barangay = 'aplaya'
       LIMIT ?";
       $stmt = mysqli_prepare($conn, $qualifiedQuery);
       mysqli_stmt_bind_param($stmt, "i", $limit);
       mysqli_stmt_execute($stmt);
       $qualifiedResult = mysqli_stmt_get_result($stmt);
   
       $updateCount = 0; // Track the number of applicants updated
   
       while ($row = mysqli_fetch_assoc($qualifiedResult)) {
           if ($updateCount >= $limit) {
               break; // Stop updating once the limit is reached
           }
           $ceappersonalaccouundID = $row['ceap_personal_account_id'];
           $employeeUsername = $_SESSION["username"];
   
           // Retrieve the previous status from the database
           $prevStatusQuery = "SELECT status FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
           $stmtPrev = $conn->prepare($prevStatusQuery);
           $stmtPrev->bind_param("i", $ceappersonalaccouundID);
           $stmtPrev->execute();
           $stmtPrev->bind_result($previousStatus);
           $stmtPrev->fetch();
           $stmtPrev->close();
   
           $updateTimeQuery = "UPDATE ceap_personal_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ? WHERE ceap_personal_account_id = ?";
           $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
           mysqli_stmt_bind_param($stmtTimeUpdate, "siisi", $interviewDate, $interview_hours, $interview_minutes, $interview_ampm, $ceappersonalaccouundID);
           mysqli_stmt_execute($stmtTimeUpdate);
   
           // Update the status to 'interview'
           $statusUpdateQuery = "UPDATE ceap_personal_account SET status = 'interview' WHERE ceap_personal_account_id = ?";
           $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
           mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceappersonalaccouundID);
           mysqli_stmt_execute($stmtStatusUpdate);
   
           // Close and reset the prepared statements used in this loop iteration
           mysqli_stmt_close($stmtTimeUpdate);
           mysqli_stmt_close($stmtStatusUpdate);
   
           if ($stmt->affected_rows > 0) {
               // Retrieve the employee_logs_id based on the employee username
               $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
               $stmtEmployeeId = $conn->prepare($employeeIdQuery);
               $stmtEmployeeId->bind_param("s", $employeeUsername);
               $stmtEmployeeId->execute();
               $stmtEmployeeId->bind_result($employeeLogsId);
               $stmtEmployeeId->fetch();
               $stmtEmployeeId->close();
   
               $status = 'interview'; // Assign a value to $status before the insert query
               // Log the status change in the applicant_status_logs table using a prepared statement
               $logQuery = "INSERT INTO old_grantee_status_logs (old_previous_status, old_updated_status, ceap_personal_account_id, employee_logs_id) VALUES (?, ?, ?, ?)";
               $stmtLog = $conn->prepare($logQuery);
               $stmtLog->bind_param("ssii", $previousStatus, $status, $ceappersonalaccouundID, $employeeLogsId);
               $stmtLog->execute();
   
               $response = 'success'; // Update and log were successful
           } else {
               $response = 'error'; // Update failed
           }
   
           $updateCount++;
       }
   }
   
   // Redirect to prevent form resubmission
   header("Location: " . $_SERVER['REQUEST_URI']);
   exit();
   }
   
   
   
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT *, 
          UPPER(first_name) AS first_name, 
          UPPER(last_name) AS last_name, 
          UPPER(barangay) AS barangay, 
          control_number, 
          date_of_birth, 
          UPPER(status) AS status
   FROM ceap_personal_account p
   WHERE barangay = 'aplaya' && status = 'Verified'
   SQL;
   
   $result = mysqli_query($conn, $query);
   
   
   // Query to count 'verified' accounts
   $verifiedCountQuery = "SELECT COUNT(*) AS verifiedCount FROM ceap_personal_account WHERE status = 'Verified' AND barangay='aplaya'";
   $stmtVerifiedCount = mysqli_prepare($conn, $verifiedCountQuery);
   mysqli_stmt_execute($stmtVerifiedCount);
   $verifiedCountResult = mysqli_stmt_get_result($stmtVerifiedCount);
   $verifiedCountRow = mysqli_fetch_assoc($verifiedCountResult);
   
   // Store the count of 'verified' accounts in a variable
   $verifiedCount = $verifiedCountRow['verifiedCount'];
   
   ?>
<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="../../../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='../../../css/remixicon.css'>
      <link rel='stylesheet' href="../../../css/unpkg-layout.css">
      <link rel="stylesheet" href="../../../css/side_bar.css">
      <link rel="stylesheet" href="../../../css/ceap_list.css">
      <link rel="stylesheet" href="../../../css/ceap_verified.css">
      <script>
         // Prevent manual input in date fields
         function preventInput(event) {
             event.preventDefault();
         }
      </script>
   </head>
   <body>
      <?php 
         include '../../side_bar_barangay_old.php';
         ?>
      <!-- home content--> 
      <!-- search bar and set interview modal -->   
      <div class="form-group">
         <?php
            // Check if the user's role is not "Staff"
            if ($_SESSION['role'] !== 1) {
                // Only display the button if the user's role is not "Staff" and there are verified applicants
                if (hasVerifiedStatusInDatabase($conn)) {
                    echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn">Set Interview</button>';
                } else {
                    echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn" disabled style="background-color: #ccc; cursor: not-allowed;">Set Interview</button>';
                }
            }
            ?>
         <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">
         <button type="button" class="btn btn-primary" onclick="searchApplicants()">Search</button>
         <!-- Add a button to trigger the modal -->
         <?php
            function hasVerifiedStatusInDatabase($conn) {
                $query = "SELECT COUNT(*) FROM ceap_personal_account
                          WHERE status = 'Verified' AND UPPER(barangay) = 'APLAYA'";
                
                $result = mysqli_query($conn, $query);
                
                if ($result) {
                    $count = mysqli_fetch_row($result)[0];
                    return $count > 0;
                } else {
                    // Handle the query error if needed
                    echo "Error: " . mysqli_error($conn);
                    return false;
                }
            }
             ?>
      </div>
      <!-- Set Interview Modal (hidden by default) -->
      <div id="myModal" class="modal">
         <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <div class="modal-body">
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="form-group">
                     <label for="interview_date">Date</label>
                     <input type="date" name="interview_date" id="interview_date" class="form-control" required onkeydown="preventInput(event)"
                        <?php
                           echo 'min="' . date('Y-m-d') . '"';
                           echo ' max="' . date('Y-12-31') . '"';
                           ?>>
                  </div>
                  <div class="form-group">
                     <label>Time</label>
                     <div style="display: flex; align-items: center;">
                        <input type="number" name="interview_hours" id="interview_hours" class="form-control" min="1" max="12" required>
                        <span style="margin: 0 5px;">:</span>
                        <input type="number" name="interview_minutes" id="interview_minutes" class="form-control" min="0" max="59" required>
                        <select class="form-control" name="interview_ampm" id="interview_ampm" required>
                           <option value="AM">AM</option>
                           <option value="PM">PM</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="limit">Qty</label>
                     <input type="number" class="form-control" name="limit" id="limit" min="1" max="<?php echo $verifiedCount; ?>" required>
                  </div>
                  <script>
                     const limitInput = document.getElementById('limit');
                     
                     limitInput.addEventListener('input', function() {
                         const userInput = limitInput.value.trim();
                         let sanitizedInput = userInput.replace(/^0+|(\..*)\./gm, '$0');
                     
                         if (sanitizedInput === '' || isNaN(sanitizedInput)) {
                             limitInput.value = '';
                         } else {
                             const parsedInput = parseInt(sanitizedInput);
                     
                             // Ensure the value is within the valid range
                             const validValue = Math.min(Math.max(parsedInput, 1), 200);
                     
                             limitInput.value = validValue;
                         }
                     });
                  </script>
                  <div class="form-group">
                     <button type="submit" name="saveBtn" id="saveBtn" class="btn btn-primary">Set</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- end search and modal -->
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">CEAP OLD GRANTEE LIST</h2>
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
                  <th>BARANGAY</th>
                  <th>STATUS</th>
               </tr>
               <?php
                  $counter = 1;
                  
                           // Display applicant info using a table
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo '<tr class="applicant-row contents" onclick="seeMore(\'' . $row['ceap_personal_account_id'] . '\')" style="cursor: pointer;">';
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
             window.location.href = "old_ceap_information.php?ceap_personal_account_id=" + id;
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
      <script>
         // Get modal elements using plain JavaScript
         var modal = document.getElementById("myModal");
         var openModalBtn = document.getElementById("openModalBtn");
         var closeModalBtn = document.getElementById("closeModalBtn");
         
         // Show modal when the button is clicked
         openModalBtn.addEventListener("click", function() {
            modal.style.display = "block";
         });
         
         // Close modal when the close button is clicked
         closeModalBtn.addEventListener("click", function() {
            modal.style.display = "none";
         });
         
         // Close modal when clicking outside the modal content
         window.addEventListener("click", function(event) {
            if (event.target === modal) {
               modal.style.display = "none";
            }
         });
      </script>
      <script>
         document.addEventListener("DOMContentLoaded", function() {
             const hoursInput = document.getElementById("interview_hours");
             hoursInput.addEventListener("input", function() {
               
                 const hoursValue = parseInt(hoursInput.value);
                 if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
                     hoursInput.value = '';
                 }
             });
             
             const minutesInput = document.getElementById("interview_minutes");
         minutesInput.addEventListener("input", function() {
         let minutesValue = minutesInput.value;
         
         // Remove leading zeros, except when the input is '0' or '00'
         minutesValue = minutesValue.replace(/^0+(?!$)/, '');
         
         // Ensure the value is within the valid range (0 to 59)
         const parsedMinutesValue = parseInt(minutesValue);
         if (isNaN(parsedMinutesValue) || parsedMinutesValue < 0 || parsedMinutesValue > 59) {
         minutesInput.value = '';
         } else {
         // Add leading zeros if the value is less than 10
         minutesInput.value = parsedMinutesValue < 10 ? `0${parsedMinutesValue}` : parsedMinutesValue.toString();
         }
         });
         
         
         });
         
         function formatInput(inputElement) {
             // Remove multiple consecutive white spaces
             inputElement.value = inputElement.value.replace(/\s+/g, ' ');
         
             // Convert input text to uppercase
             inputElement.value = inputElement.value.toUpperCase();
           }
         
      </script>
   </body>
</html>