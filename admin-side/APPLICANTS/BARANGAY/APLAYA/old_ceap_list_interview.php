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

    date_default_timezone_set('Asia/Manila');
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
      <link rel="stylesheet" href="../../../css/status_popup.css">
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
            include '../../../php/reschedulepopups.php';
         include '../../side_bar_barangay_old.php';
         ?>
      <!-- home content-->
      <!-- search and reschedule -->
      <div class="form-group">
         <!-- Reschedule Button -->
            <?php
                // Check if the user's role is not "Staff"
                if ($_SESSION['role'] !== 1) {
                    // Only display the button if the user's role is not "Staff" and there are verified applicants
                    $hasInterviewStatusOLD = hasInterviewStatusInDatabaseOLD($conn, $currentBarangay, $currentStatus);

                    if ($hasInterviewStatusOLD) {
                        echo '<button id="rescheduleButton" type="button" class="btn btn-primary">Reschedule</button>';
                    } else {
                        echo '<button id="rescheduleButton" type="button" class="btn btn-primary" disabled style="background-color: #ccc; cursor: not-allowed;">Reschedule</button>';
                    }
                }

                function hasInterviewStatusInDatabaseOLD($conn, $currentBarangay, $currentStatus) {
                    $interviewDate = date('Y-m-d');

                    $query = "SELECT COUNT(*) FROM ceap_personal_account AS t
                            WHERE t.status = ? AND UPPER(t.barangay) = ? AND t.interview_date = ?";

                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "sss", $currentStatus, $currentBarangay, $interviewDate);

                    if ($stmt) {
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            $count = mysqli_fetch_row($result)[0];
                            mysqli_stmt_close($stmt);
                            return $count > 0;
                        } else {
                            // Proper error handling for the query execution
                            echo "Error executing query: " . mysqli_error($conn);
                            mysqli_stmt_close($stmt);
                            return false;
                        }
                    } else {
                        // Proper error handling for the statement preparation
                        echo "Error preparing statement: " . mysqli_error($conn);
                        return false;
                    }
                }
                ?>

         <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">
         <button type="button" class="btn btn-primary" style="margin-right: 10px;" onclick="searchApplicants()">Search</button>
      </div>
      <!-- Reschedule Modal (hidden by default) -->
      <div id="myModal" class="modal">
         <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <div class="modal-body">
               <label for="current_time">Reschedule Interview Date</label>
               <!-- <span id="currentDateTime"></span>
               <script>
                  
                  function updateCurrentDateTime() {
                      const currentDateTimeElement = document.getElementById('currentDateTime');
                      const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric', year: 'numeric', month: 'numeric', day: 'numeric' };
                      const currentDateTime = new Date().toLocaleString([], options);
                      currentDateTimeElement.textContent = currentDateTime;
                  }
                  
                  // Update the current date and time initially and then every second
                  updateCurrentDateTime();
                  setInterval(updateCurrentDateTime, 1000); // Update every 1 second
               </script> -->
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
               <!-- <h3 style="text-align: center;">Reschedule today's applicants</h3> -->
               <div class="form-group">
                    <label for="interview_date">Date</label>
                    <input type="date" name="interview_date" id="interview_date" class="form-control" required onkeydown="preventInput(event)"
                        <?php
                            echo 'min="' . date('Y-m-d') . '"';
                            
                            // Calculate the max date (6 months from the current date)
                            $maxDate = date('Y-m-d', strtotime('+6 months'));
                            echo ' max="' . $maxDate . '"';
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
                  <span id="error-message" style="text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <label for="limit">Qty</label>
                     <input type="number" class="form-control" name="limit" id="limit" min="1" max="<?php echo $todayInterviewCount; ?>" required>
                  </div>
                  <span id="error-message-limit" style="text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <button type="button" class="btn btn-primary" id="rescheduleBtn" onclick="openRescheduleOLD(), closeModalInterview()" disabled>Reschedule</button>
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
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/VerifiedandInterview.js"></script>
      <script  src="../../../js/rescheduleInterviewOLD.js"></script>
      <script  src="../../../js/openReschedulePopupOLD.js"></script>

      <script>
         function seeMore(id) {
             // Redirect to a page where you can retrieve the reserved data based on the given ID
             window.location.href = "old_ceap_information.php?ceap_personal_account_id=" + id;
         }
         
      </script>
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
        
          //open modal
         // Get Reschedmodal elements using plain JavaScript
         var Reschedmodal = document.getElementById("myModal");
         var openReschedModalBtn = document.getElementById("rescheduleButton");
         var closeReschedModalBtn = document.getElementById("closeModalBtn");
         
         // Show Reschedmodal when the button is clicked
         openReschedModalBtn.addEventListener("click", function() {
            Reschedmodal.style.display = "block";
         });
         
         // Close Reschedmodal when the close button is clicked
         closeReschedModalBtn.addEventListener("click", function() {
            Reschedmodal.style.display = "none";
         });
         
         // Close Reschedmodal when clicking outside the Reschedmodal content
         window.addEventListener("click", function(event) {
            if (event.target === Reschedmodal) {
               Reschedmodal.style.display = "none";
            }
         });
         
         
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
      
<script>
    const rescheduledateInput = document.getElementById('interview_date');
    const reschedulehoursInput = document.getElementById('interview_hours');
    const rescheduleminutesInput = document.getElementById('interview_minutes');
    const rescheduleperiodInput = document.getElementById('interview_ampm');
    const reschedulelimitInput = document.getElementById('limit');
    const rescheduleerrorMessage = document.getElementById('error-message');
    const rescheduleerrorMessageLimit = document.getElementById('error-message-limit');

    // Add input event listeners to date/time inputs
    rescheduledateInput.addEventListener('input', validateDateTimeInput);
    reschedulehoursInput.addEventListener('input', validateDateTimeInput);
    rescheduleminutesInput.addEventListener('input', validateDateTimeInput);
    rescheduleperiodInput.addEventListener('input', validateDateTimeInput);

    // Add input event listener to the limit input
    reschedulelimitInput.addEventListener('input', validaterescheduleLimitInput);

    function validateDateTimeInput() {
    const selectedDate = new Date(rescheduledateInput.value);
    const hours = parseInt(reschedulehoursInput.value);
    const minutes = parseInt(rescheduleminutesInput.value);
    const period = rescheduleperiodInput.value;

    // Log the selected period to the console
    console.log('Selected Period:', period);

    let adjustedHours = hours; // Declare a new variable to store the adjusted hours

    if (period === 'PM') {
        adjustedHours += 12;
    }

    const isDateTimeValid =
        !isNaN(selectedDate) &&
        !isNaN(adjustedHours) && // Use adjustedHours
        !isNaN(minutes);

    // Get the input elements for date, hours, minutes, and period
    const inputElements = [rescheduledateInput, reschedulehoursInput, rescheduleminutesInput, rescheduleperiodInput];

    if (isDateTimeValid) {
        // If input is valid, remove 'invalid' class
        inputElements.forEach((element) => {
            element.classList.remove('invalid');
        });

        selectedDate.setHours(adjustedHours, minutes, 0, 0);
        const currentDate = new Date();

        if (selectedDate >= currentDate) {
            rescheduleerrorMessage.textContent = '';
        }  else {
            inputElements.forEach((element) => {
                element.classList.add('invalid');
            });
            rescheduleerrorMessage.textContent = 'Invalid Date and time';
            rescheduleerrorMessage.style.color= 'red';
        }
    } else {
        // If input is invalid, add 'invalid' class
        inputElements.forEach((element) => {
            element.classList.add('invalid');
        });
        rescheduleerrorMessage.textContent = 'Ensure the date and time are not earlier than the current time.';
        rescheduleerrorMessage.style.color= 'gray';

    }
}

function validaterescheduleLimitInput() {
    const limit = parseInt(reschedulelimitInput.value);
    const isLimitValid =
        !isNaN(limit) &&
        limit >= 1 &&
        limit <= <?php echo $todayInterviewCount; ?>;

    // Add or remove 'invalid' class based on validation
    if (isLimitValid) {
        reschedulelimitInput.classList.remove('invalid');
        rescheduleerrorMessageLimit.textContent = '';
    } else {
        reschedulelimitInput.classList.add('invalid');
        rescheduleerrorMessageLimit.textContent = 'Quantity cannot exceed to <?php echo $todayInterviewCount; ?>.';
        rescheduleerrorMessage.style.color= 'red';

    }
}

// Get references to the required input fields and the save button
const requiredInputs = document.querySelectorAll('[required]'); // Get all required fields
const rescheduleBtn = document.getElementById('rescheduleBtn');

// Function to check if all required inputs are valid
function checkRequiredInputs() {
    const allInputsValid = Array.from(requiredInputs).every((input) => {
        return input.value.trim() !== '' && !input.classList.contains('invalid');
    });

    if (allInputsValid) {
        rescheduleBtn.removeAttribute('disabled');
    } else {
        rescheduleBtn.setAttribute('disabled', 'true');
    }
}

// Add input event listeners to required input fields
requiredInputs.forEach((input) => {
    input.addEventListener('input', checkRequiredInputs);
});

// Initial check
checkRequiredInputs();

</script>
   </body>
</html>