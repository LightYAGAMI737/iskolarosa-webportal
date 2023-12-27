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
   
   $currentStatus = 'interview';
   $currentPage = 'ceap_list';
   $currentSubPage = 'old applicant';
   
   $currentDirectory = basename(__DIR__);
   $currentBarangay = $currentDirectory;
   
   // Assuming $currentStatus is also a variable you need to sanitize
   $currentStatus = mysqli_real_escape_string($conn, $currentStatus);
   $currentBarangay = mysqli_real_escape_string($conn, $currentBarangay);
   
   // Construct the SQL query using heredoc syntax
   $query = <<<SQL
   SELECT * 
   FROM ceap_personal_account 
   WHERE barangay = ? AND status = ?
   SQL;
   
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "ss", $currentBarangay, $currentStatus);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   
   $currentDate = date('Y-m-d');
   
   $countQuery = "SELECT COUNT(*) AS todayCount FROM ceap_personal_account WHERE interview_date = ? AND status = ? AND barangay = ?";
   $stmtCount = mysqli_prepare($conn, $countQuery);
   
   $currentStatusCount = mysqli_real_escape_string($conn, $currentStatus);
   $currentBarangayCount = mysqli_real_escape_string($conn, $currentBarangay);
   
   mysqli_stmt_bind_param($stmtCount, "sss", $currentDate, $currentStatusCount, $currentBarangayCount);
   mysqli_stmt_execute($stmtCount);
   
   $todayCountResult = mysqli_stmt_get_result($stmtCount);
   $todayCountRow = mysqli_fetch_assoc($todayCountResult);
   
   $todayInterviewCount = $todayCountRow['todayCount'];
   
   mysqli_stmt_close($stmtCount);
   
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
      <link rel="stylesheet" href="../../../css/ceap_interview.css">
      <script>
         // Prevent manual input in date fields
         function preventInput(event) {
             event.preventDefault();
         }
         
         window.onload = function() {
             const currentDate = new Date();
             const currentYear = currentDate.getFullYear();
             const lastDayOfYear = new Date(currentYear, 11, 31); // Month is 0-based
             const formattedLastDay = lastDayOfYear.toISOString().split('T')[0];
         
             document.getElementById('startDate').setAttribute('max', formattedLastDay);
             document.getElementById('endDate').setAttribute('max', formattedLastDay);
         };
      </script>
   </head>
   <body>
      <?php 
         include '../../side_bar_barangay_old.php';
         ?>
      <!-- home content-->
      <!-- search and reschedule -->
      <div class="form-group">
         <!-- Reschedule Button -->
         <div class="reschedule-button">
            <button id="rescheduleButton" type="button" class="btn btn-primary" style="border-radius: 15px; margin-right: 40px;" onclick="openRescheduleModal()">Reschedule</button>
         </div>
         <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">
         <button type="button" class="btn btn-primary" style="margin-right: 10px;" onclick="searchApplicants()">Search</button>
      </div>
      <!-- Set Interview Modal (hidden by default) -->
      <div id="myModal" class="modal">
         <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <div class="modal-body">
               <form method="post" action="../../../php/rescheduleOldGrantee.php">
                  <h3 style="text-align: center;">Reschedule today's applicant.</h3>
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
                        <input type="number" name="interview_minutes" id="interview_minutes" minlength="2" class="form-control" min="0" max="59" required>
                        <select class="form-control" name="interview_ampm" id="interview_ampm" required>
                           <option value="AM">AM</option>
                           <option value="PM">PM</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="limit">Qty</label>
                     <input type="number" class="form-control"  name="limit" id="limit" min="1" max="<?php echo $todayInterviewCount; ?>" required>
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
                     <button type="submit" name="rescheduleBtn" id="rescheduleBtn" class="btn btn-primary">Reschedule</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- end search and reschedule -->
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
                  <!-- <th>BARANGAY</th>
                  <th>STATUS</th> -->
                  <th>INTERVIEW DATE</th>
               </tr>
               <?php
                  $counter = 1;
                  
                           // Display applicant info using a table
                           while ($row = mysqli_fetch_assoc($result)) {
                              // Inside the loop that displays applicant info
                              echo '<tr class="applicant-row contents" data-interview-date="' . $row['interview_date'] . '" onclick="seeMore(\'' . $row['ceap_personal_account_id'] . '\')" style="cursor: pointer;">';
                                 echo '<td><strong>' . $counter++ . '</strong></td>';
                              echo '<td>' . strtoupper($row['control_number']) . '</td>';
                              echo '<td>' . strtoupper($row['last_name']) . '</td>';
                              echo '<td>' . strtoupper($row['first_name']) . '</td>';
                            //   echo '<td>' . strtoupper($row['barangay']) . '</td>';
                            //   echo '<td>' . strtoupper($row['status']) . '</td>';
                              echo '<td>' . strtoupper($row['interview_date']) . '</td>';
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
        
         // Function to open the reschedule modal
         function openRescheduleModal() {
             var rescheduleButton = document.getElementById("rescheduleButton");
             if (!rescheduleButton.disabled) {
                 var modal = document.getElementById("myModal");
                 modal.style.display = "block";
             }
         }
         
         // Function to close the modal
         function closeRescheduleModal() {
             var modal = document.getElementById("myModal");
             modal.style.display = "none";
         }
         
         // Close the modal when the close button is clicked
         document.getElementById("closeModalBtn").addEventListener("click", function() {
             closeRescheduleModal();
         });
         
         // Close the modal when clicking outside of it
         window.onclick = function(event) {
             var modal = document.getElementById("myModal");
             if (event.target === modal) {
                 closeRescheduleModal();
             }
         }
         
         // Check if there are any applicants with interview_date set to today
         checkTodayInterview();
         
         function checkTodayInterview() {
             var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
         
             var hasTodayInterview = false; // Flag to track if there are applicants with today's interview
         
             $('.contents').each(function () {
                 var interviewDate = $(this).data('interview-date');
                 if (interviewDate === today) {
                     hasTodayInterview = true;
                     return false; // Exit the loop early since we found a match
                 }
             });
         
             // Disable the "Reschedule" button if no applicants have interview_date today
             var rescheduleButton = document.getElementById("rescheduleButton");
             if (!hasTodayInterview) {
                 rescheduleButton.disabled = true;
             }
         }
         
         // DOMContentLoaded event
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
         
             // Remove multiple consecutive white spaces and convert input text to uppercase
             const searchInput = document.getElementById("search");
             searchInput.addEventListener("input", function() {
                 searchInput.value = searchInput.value.replace(/\s+/g, ' ').toUpperCase();
             });
         });
         
  
      </script>
   </body>
</html>