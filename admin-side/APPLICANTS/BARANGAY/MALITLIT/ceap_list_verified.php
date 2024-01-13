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
   $currentStatus = 'Verified';
   $currentPage = 'ceap_list';
   $currentSubPage = 'new applicant';

   $currentDirectory = basename(__DIR__);
$currentBarangay = $currentDirectory;

// Assuming $currentStatus is also a variable you need to sanitize
$currentStatus = mysqli_real_escape_string($conn, $currentStatus);

// Prepare the SQL query using prepared statements
$query = "SELECT t.*, 
          UPPER(p.first_name) AS first_name, 
          UPPER(p.last_name) AS last_name, 
          UPPER(p.barangay) AS barangay, 
          p.control_number, 
          p.date_of_birth, 
          UPPER(t.status) AS status
   FROM ceap_reg_form p
   INNER JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
   WHERE p.barangay = ? AND t.status = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $currentBarangay, $currentStatus);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
   

$verifiedCountQuery = "SELECT COUNT(*) AS verifiedCount, UPPER(p.barangay) AS barangay, UPPER(t.status) AS status
FROM ceap_reg_form p
INNER JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
WHERE p.barangay = ? AND t.status = ?";

$stmtVerifiedCount = mysqli_prepare($conn, $verifiedCountQuery);
mysqli_stmt_bind_param($stmtVerifiedCount, "ss", $currentBarangay, $currentStatus);
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
      <link rel="stylesheet" href="../../../css/status_popup.css">

      <script>
         // Prevent manual input in date fields
         function preventInput(event) {
             event.preventDefault();
         }
      </script>
   </head>
   <body>
      <?php 
         include '../../../php/status_popup.php';
         include '../../../php/confirmStatusPopUp.php';
         ?>
           <!-- Set Interview Modal (hidden by default) -->
      <div id="myModal" class="modal">
         <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <div class="modal-body">
               <label  class="form-label" for="current_time"  style="font-size: 18px;"></h3>Set Interview Date</h3></label>
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="form-group">
                     <label for="interview_date" class="form-label" style="width: 65px; text-align: center;">Date</label>
                     <input type="date" name="interview_date" id="interview_date" class="form-control" required onkeydown="preventInput(event)"
                     <?php
                            echo 'min="' . date('Y-m-d') . '"';
                            
                            // Calculate the max date (6 months from the current date)
                            $maxDate = date('Y-m-d', strtotime('+6 months'));
                            echo ' max="' . $maxDate . '"';
                        ?>>
                  </div>
                  <div class="form-group">
                     <label class="form-label" style="width: 65px; text-align: center;">Time</label>
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
                  <span id="error-message" style="text-align: center; display: flex; justify-content: center; max-width: 275px; margin-left: 70px;"></span>
                  <div class="form-group">
                     <label for="limit" class="form-label" style="width: 65px; text-align: center;">Quantity</label>
                     <input type="number" class="form-control" name="limit" id="limit" min="1" max="<?php echo $verifiedCount; ?>" required>
                  </div>
                  <span id="error-message-limit" style="text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <button type="button" class="btn btn-primary" id="saveBtn" onclick="openInterviewPopup(), closeModalInterview()" disabled>Set</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <?php 
         include '../../side_bar_barangay.php';
         ?>
      <!-- end search and modal -->
      <!-- home content--> 
      <!-- search bar and set interview modal -->   
      <div class="form-group">
         <?php
            // Check if the user's role is not "Staff"
            if ($_SESSION['role'] !== 1) {
                // Only display the button if the user's role is not "Staff" and there are verified applicants
                $hasVerifiedStatus = hasVerifiedStatusInDatabase($conn, $currentBarangay, $currentStatus);
                if ($hasVerifiedStatus) {
                    echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn">Set Interview</button>';
                } else {
                    echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn" disabled style="background-color: #ccc; cursor: not-allowed;">Set Interview</button>';
                }
            }
            ?>
         <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">
         <!-- Add a button to trigger the modal -->
         <?php
     function hasVerifiedStatusInDatabase($conn, $currentBarangay, $currentStatus) {
        // Prepare the SQL query using prepared statements
        $query = "SELECT COUNT(*) FROM temporary_account AS t
                  INNER JOIN ceap_reg_form AS p ON t.ceap_reg_form_id = p.ceap_reg_form_id
                  WHERE t.status = ? AND UPPER(p.barangay) = ?";
    
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $currentStatus, $currentBarangay);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $count = mysqli_fetch_row($result)[0];
            mysqli_stmt_close($stmt);
            return $count > 0;
        } else {
            echo "Error: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
            return false;
        }
        
    }
    
             ?>
      </div>
    
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">CEAP APPLICANT LIST</h2>
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
               </tr>
               <?php
                  $counter = 1;
                  
                           // Display applicant info using a table
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo '<tr class="applicant-row contents" onclick="seeMore(\'' . $row['ceap_reg_form_id'] . '\')" style="cursor: pointer;">';
                              echo '<td><strong>' . $counter++ . '</strong></td>';
                              echo '<td>' . strtoupper($row['control_number']) . '</td>';
                              echo '<td>' . strtoupper($row['last_name']) . '</td>';
                              echo '<td>' . strtoupper($row['first_name']) . '</td>';
                            //   echo '<td>' . strtoupper($row['barangay']) . '</td>';
                            //   echo '<td>' . strtoupper($row['status']) . '</td>';
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
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/status_popup.js"></script>
      <script  src="../../../js/VerifiedandInterview.js"></script>
      <script  src="../../../js/updateStatusInterview.js"></script>
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
    const dateInput = document.getElementById('interview_date');
    const hoursInput = document.getElementById('interview_hours');
    const minutesInput = document.getElementById('interview_minutes');
    const periodInput = document.getElementById('interview_ampm');
    const limitInput = document.getElementById('limit');
    const errorMessage = document.getElementById('error-message');
    const errorMessageLimit = document.getElementById('error-message-limit');

    // Add input event listeners to date/time inputs
    dateInput.addEventListener('input', validateDateTimeInput);
    hoursInput.addEventListener('input', validateDateTimeInput);
    minutesInput.addEventListener('input', validateDateTimeInput);
    periodInput.addEventListener('input', validateDateTimeInput);

    // Add input event listener to the limit input
    limitInput.addEventListener('input', validateLimitInput);

    function validateDateTimeInput() {
    const selectedDate = new Date(dateInput.value);
    const hours = parseInt(hoursInput.value);
    const minutes = parseInt(minutesInput.value);
    const period = periodInput.value;

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
    const inputElements = [dateInput, hoursInput, minutesInput, periodInput];

    if (isDateTimeValid) {
        // If input is valid, remove 'invalid' class
        inputElements.forEach((element) => {
            element.classList.remove('invalid');
        });

        selectedDate.setHours(adjustedHours, minutes, 0, 0);
        const currentDate = new Date();

        if (selectedDate >= currentDate) {
            errorMessage.textContent = '';
        }  else {
            inputElements.forEach((element) => {
                element.classList.add('invalid');
            });
            errorMessage.textContent = 'Invalid Date and time';
            errorMessage.style.color = 'red';
        }
    } else {
        // If input is invalid, add 'invalid' class
        inputElements.forEach((element) => {
            element.classList.add('invalid');
        });
        errorMessage.textContent = 'Ensure the date and time are not earlier than the current time.';
        errorMessage.style.color = 'gray';

    }
}

function validateLimitInput() {
    const limit = parseInt(limitInput.value);
    const isLimitValid =
        !isNaN(limit) &&
        limit >= 1 &&
        limit <= <?php echo $verifiedCount; ?>;

    // Add or remove 'invalid' class based on validation
    if (isLimitValid) {
        limitInput.classList.remove('invalid');
        errorMessageLimit.textContent = '';
    } else {
        limitInput.classList.add('invalid');
        errorMessageLimit.textContent = 'Quantity max limit:  <?php echo $verifiedCount; ?>.';
        errorMessageLimit.style.color = 'gray';
    }
}

// Get references to the required input fields and the save button
const requiredInputs = document.querySelectorAll('[required]'); // Get all required fields
const saveBtn = document.getElementById('saveBtn');

// Function to check if all required inputs are valid
function checkRequiredInputs() {
    const allInputsValid = Array.from(requiredInputs).every((input) => {
        return input.value.trim() !== '' && !input.classList.contains('invalid');
    });

    if (allInputsValid) {
        saveBtn.removeAttribute('disabled');
    } else {
        saveBtn.setAttribute('disabled', 'true');
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