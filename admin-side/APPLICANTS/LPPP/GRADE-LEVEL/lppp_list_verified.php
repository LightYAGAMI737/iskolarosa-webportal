

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
    'view_ceap_applicants' => 'You do not have permission to view LPPP applicants.',
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
$currentPage = 'lppp_list';
$currentSubPage = 'GRADE 7';

// Construct the SQL query using heredoc syntax
$query = <<<SQL
SELECT t.*, 
       UPPER(p.first_name) AS first_name, 
       UPPER(p.last_name) AS last_name, 
       UPPER(p.barangay) AS barangay, 
       p.control_number, 
       p.date_of_birth, 
       UPPER(t.status) AS status
FROM lppp_reg_form p
INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
WHERE  t.status = 'Verified'
SQL;

$result = mysqli_query($conn, $query);
 // Query to count 'verified' accounts
 $lpppverifiedCountQuery = "SELECT COUNT(*) AS lpppverifiedCount FROM lppp_temporary_account WHERE status = 'Verified'";
 $stmtlpppVerifiedCount = mysqli_prepare($conn, $lpppverifiedCountQuery);
 mysqli_stmt_execute($stmtlpppVerifiedCount);
 $lpppverifiedCountResult = mysqli_stmt_get_result($stmtlpppVerifiedCount);
 $lpppverifiedCountRow = mysqli_fetch_assoc($lpppverifiedCountResult);
 
 // Store the count of 'verified' accounts in a variable
 $lpppverifiedCount = $lpppverifiedCountRow['lpppverifiedCount'];
 
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
      <link rel="stylesheet" href="../../../css/status_popup.css">
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
        require '../../../php/LPPPstatus_popup.php';
        include '../../side_bar_lppp.php';
      ?>
      <!-- home content--> 
      <!-- search bar and set interview modal -->   
      <div class="form-group">
      <?php
// Check if the user's role is not "Staff"
if ($_SESSION['role'] !== 1) {
    // Only display the button if the user's role is not "Staff" and there are verified applicants
    if (hasVerifiedStatusInDatabase($conn)) {
        echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn">Set Exam</button>';
    } else {
        echo '<button type="button" class="btn btn-primary btn-rad" id="openModalBtn" disabled style="background-color: #ccc; cursor: not-allowed;">Set Exam</button>';
    }
}
?>
      <input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name"  oninput="formatInput(this)">

         <button type="button" class="btn btn-primary" onclick="searchApplicants()">Search</button>
  <!-- Add a button to trigger the modal -->
  
  <?php
  function hasVerifiedStatusInDatabase($conn) {
    $query = "SELECT COUNT(*) FROM lppp_temporary_account WHERE status = 'Verified'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_fetch_row($result)[0];
    return $count > 0;
    // Placeholder value for demonstration
    //return false;
}

?>

      </div>
      <!-- Set Interview Modal (hidden by default) -->
<div id="myModal" class="modal">
   <div class="modal-content">
      <span class="close" id="closeModalBtn">&times;</span>
      <div class="modal-body">
    <label for="current_time"><h2>Set Exam Date</h2></label>
               <!-- <span id="currentDateTime"></span>
               <script>
                  // Function to update the current date and time
                  function updateCurrentDateTimeLPPP() {
                      const currentDateTimeElement = document.getElementById('currentDateTime');
                      const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric', year: 'numeric', month: 'numeric', day: 'numeric' };
                      const currentDateTime = new Date().toLocaleString([], options);
                      currentDateTimeElement.textContent = currentDateTime;
                  }
                  
                  // Update the current date and time initially and then every second
                  updateCurrentDateTimeLPPP();
                  setInterval(updateCurrentDateTimeLPPP, 1000); // Update every 1 second
               </script> -->
         <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
               <label for="exam_date">Date</label>
              <input type="date" name="exam_date" id="exam_date" class="form-control" required onkeydown="preventInput(event)"
           <?php
               echo 'min="' . date('Y-m-d') . '"';
               echo ' max="' . date('Y-12-31') . '"';
           ?>>
            </div>
            <div class="form-group">
               <label>Time</label>
               <div style="display: flex; align-items: center;">
                  <input type="number" name="exam_hour" id="exam_hour" class="form-control" min="1" max="12" required>
                  <span style="margin: 0 5px;">:</span>
                  <input type="number" name="exam_minutes" id="exam_minutes" minlength="2" class="form-control" min="0" max="59" required>
                  <select class="form-control" name="exam_ampm" id="exam_ampm" required>
                     <option value="AM">AM</option>
                     <option value="PM">PM</option>
                  </select>
               </div>
            </div>
            <span id="error-message-LPPP" style="text-align: center; display: flex; justify-content: center;"></span>
            <div class="form-group">
               <label for="limit">Qty.</label>
               <input type="number" class="form-control" name="limit" id="limit" min="1"  max="<?php echo $lpppverifiedCount; ?>" required>
            </div>
            <span id="error-message-LPPP-limit" style="text-align: center; display: flex; justify-content: center;"></span>
            <div class="form-group">
               <button type="button" name="saveBtnEXAMLPPP" id="saveBtnEXAMLPPP" class="btn btn-primary" onclick="openexamLPPPPopup(), closeModalEXAMLPPP()" disabled>Set</button>
            </div>
         </form>
      </div>
   </div>
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



      <!-- end search and modal -->
      <!-- table for displaying the applicant list -->
      <div class="background">
         <h2 style="text-align: center">LPPP APPLICANT LIST</h2>
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
   <?php } ?>
      <!-- end applicant list -->
      <footer class="footer">
      </footer>
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/LPPPStatus_Popup.js"></script>
      <script  src="../../../js/updateStatusExamLPPP.js"></script>

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
         function formatInput(inputElement) {
    // Remove multiple consecutive white spaces
    inputElement.value = inputElement.value.replace(/\s+/g, ' ');

    // Convert input text to uppercase
    inputElement.value = inputElement.value.toUpperCase();
  }

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
    const EXAMdateInput = document.getElementById('exam_date');
    const EXAMhoursInput = document.getElementById('exam_hour');
    const EXAMminutesInput = document.getElementById('exam_minutes');
    const EXAMperiodInput = document.getElementById('exam_ampm');
    const EXAMlimitInput = document.getElementById('limit');
    const errorMessage = document.getElementById('error-message-LPPP');
    const errorMessageLimit = document.getElementById('error-message-LPPP-limit');

    // Add input event listeners to date/time inputs
    EXAMdateInput.addEventListener('input', validateDateTimeInput);
    EXAMhoursInput.addEventListener('input', validateDateTimeInput);
    EXAMminutesInput.addEventListener('input', validateDateTimeInput);
    EXAMperiodInput.addEventListener('input', validateDateTimeInput);

    // Add input event listener to the limit input
    EXAMlimitInput.addEventListener('input', validateLimitInput);

    function validateDateTimeInput() {
    const selectedDate = new Date(EXAMdateInput.value);
    const hours = parseInt(EXAMhoursInput.value);
    const minutes = parseInt(EXAMminutesInput.value);
    const period = EXAMperiodInput.value;

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
    const inputElements = [EXAMdateInput, EXAMhoursInput, EXAMminutesInput, EXAMperiodInput];

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
        limit <= <?php echo $lpppverifiedCount; ?>;

    // Add or remove 'invalid' class based on validation
    if (isLimitValid) {
        EXAMlimitInput.classList.remove('invalid');
        errorMessageLimit.textContent = '';
    } else {
        EXAMlimitInput.classList.add('invalid');
        errorMessageLimit.textContent = 'Quantity cannot exceed to <?php echo$lpppverifiedCount; ?>.';
        errorMessageLimit.style.color = 'red';
    }
}

// Get references to the required input fields and the save button
const requiredInputs = document.querySelectorAll('[required]'); // Get all required fields
const saveBtnEXAMLPPP = document.getElementById('saveBtnEXAMLPPP');

// Function to check if all required inputs are valid
function checkRequiredInputs() {
    const allInputsValid = Array.from(requiredInputs).every((input) => {
        return input.value.trim() !== '' && !input.classList.contains('invalid');
    });

    if (allInputsValid) {
        saveBtnEXAMLPPP.removeAttribute('disabled');
    } else {
        saveBtnEXAMLPPP.setAttribute('disabled', 'true');
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