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

   // Set variables
   $currentStatus = 'exam';
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
       UPPER(t.status) AS status,
       t.interview_date, t.interview_date
FROM lppp_reg_form p
INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
WHERE (t.status = 'exam') OR (t.status = 'interview' AND t.interview_date = '0000-00-00')
SQL;

$result = mysqli_query($conn, $query);
 // Query to count 'verified' accounts
 $interviewCountQuery = "SELECT COUNT(*) AS inteviewCount FROM lppp_temporary_account WHERE status = 'interview'  AND interview_date ='0000-00-00' ";
 $stmtlpppVerifiedCount = mysqli_prepare($conn, $interviewCountQuery);
 mysqli_stmt_execute($stmtlpppVerifiedCount);
 $interviewCountResult = mysqli_stmt_get_result($stmtlpppVerifiedCount);
 $interviewCountRow = mysqli_fetch_assoc($interviewCountResult);
 
 // Store the count of 'verified' accounts in a variable
 $interviewCount = $interviewCountRow['inteviewCount'];
 


date_default_timezone_set('Asia/Manila');
// Get the current date in the format 'Y-m-d'
$currentDateResched = date('Y-m-d');

// Query to count applicants with exam dates today
$countQuery = "SELECT COUNT(*) AS todayCount,
    UPPER(status) AS status, exam_date
FROM lppp_temporary_account 
WHERE  status = ? AND exam_date = ?";

$stmtCount = mysqli_prepare($conn, $countQuery);
mysqli_stmt_bind_param($stmtCount, "ss", $currentStatus, $currentDateResched);
mysqli_stmt_execute($stmtCount);
$todayCountResult = mysqli_stmt_get_result($stmtCount);
$todayCountRow = mysqli_fetch_assoc($todayCountResult);

// Store the count in a variable
$todayexamCount = $todayCountRow['todayCount'];

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
      <link rel="stylesheet" href="../../../css/ceap_interview.css">
      <link rel="stylesheet" href="../../../css/ceap_verified.css">
      <link rel="stylesheet" href="../../../css/status_popup.css">
      <style>
        button[disabled] {
            background-color: #ccc !important;
            cursor: not-allowed;
        }
        #rescheduleBtnExamLPPP, #InterviewBtnExamLPPP{
            background-color: #FEC021 !important;
            color: rgb(0, 0, 0) !important;
            border: none !important;
            padding: 7px 20px !important;
            cursor: pointer !important;
            border-radius: 15px !important;
            box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.40) !important;
        }
        .lppp-interviewBTN {
            margin-left: 40px !important;
            margin-right: 40px !important;
        }
        </style>
      <script>
        // Prevent manual input in date fields
        function preventInput(event) {
            event.preventDefault();
        }
    </script>
   </head>
   <body>
      <?php 
                include '../../../php/LPPPStatus_Popup.php';
                include '../../side_bar_lppp.php';
         ?>
             
      <!-- home content-->

<!-- search and reschedule -->
      <div class="form-group">

      <?php
date_default_timezone_set('Asia/Manila'); // Set the default timezone to Asia/Manila
$currentDate = date('Y-m-d'); // Get the current date without the time part

function hasExamInDatabase($conn) {
    global $currentDate; // Access the $currentDate variable inside the function

    $lpppquery = "SELECT COUNT(*) FROM lppp_temporary_account WHERE status = 'exam' AND exam_date = ?";
    
    // Use prepared statement to avoid SQL injection
    $stmt = mysqli_prepare($conn, $lpppquery);
    mysqli_stmt_bind_param($stmt, "s", $currentDate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $lpppcount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $lpppcount > 0;
}

// Check if there are exam applicants for the current date
$hasExamApplicants = hasExamInDatabase($conn);
// Disable the "Reschedule" button if there are no exam applicants
$rescheduleButtonDisabled = !$hasExamApplicants ? 'disabled' : '';
?>
        <!-- Reschedule Button -->
        <?php
// Check if there are verified applicants
$hasVerifiedApplicants = hasVerifiedStatusInDatabase($conn);

// Disable the "Set Interview" button if there are no verified applicants
$setInterviewButtonDisabled = !$hasVerifiedApplicants ? 'disabled' : '';
?>

<div class="reschedule-button">
    <button id="rescheduleButton" type="button" class="btn btn-primary" onclick="openRescheduleModal()" <?php echo $rescheduleButtonDisabled; ?>>Reschedule</button>
</div>

<input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name" oninput="formatInput(this)">
<button type="button" class="btn btn-primary btn-rad lppp-interviewBTN" id="openModalBtn" <?php echo $setInterviewButtonDisabled; ?>>Set Interview</button>


<?php
  function hasVerifiedStatusInDatabase($conn) {
    $query = "SELECT COUNT(*) FROM lppp_temporary_account WHERE status = 'interview' AND interview_date ='0000-00-00' ";
    $result = mysqli_query($conn, $query);
    $count = mysqli_fetch_row($result)[0];
    return $count > 0;
    // Placeholder value for demonstration
    return false;
}
?>

</div>

<?php
 // Query to count 'verified' accounts
 $lpppreschedCountQuery = "SELECT COUNT(*) AS lpppreschedCount FROM lppp_temporary_account WHERE status = 'exam'";
 $stmtlpppreschedCount = mysqli_prepare($conn, $lpppreschedCountQuery);
 mysqli_stmt_execute($stmtlpppreschedCount);
 $lpppreschedCountResult = mysqli_stmt_get_result($stmtlpppreschedCount);
 $lpppreschedCountRow = mysqli_fetch_assoc($lpppreschedCountResult);
 
 // Store the count of 'resched' accounts in a variable
 $lpppreschedCount = $lpppreschedCountRow['lpppreschedCount'];

?>

<!-- Set reschedule Modal (hidden by default) -->
<div id="reschedulemyModal" class="modal">
   <div class="modal-content">
      <span class="close" id="closeModalBtnReschedule">&times;</span>
      <div class="modal-body">
      <form method="post" id="rescheduleExamModal" action="<?php echo $_SERVER['PHP_SELF']; ?>">
               <h2 style="text-align: center;">Reschedule today's applicants</h2>  
               <p style="margin-top: -15px; text-align: center; opacity: 0.8;">For applicants that have <strong>"Exam"</strong> status</p>
                  <div class="form-group">
                     <label for="exam_date">Date</label>
                     <input type="date" name="exam_date" id="exam_date" class="form-control" required onkeydown="preventInput(event)"
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
                        <input type="number" name="exam_hours" id="exam_hours" class="form-control" min="1" max="12" required>
                        <span style="margin: 0 5px;">:</span>
                        <input type="number" name="exam_minutes" id="exam_minutes" class="form-control" min="0" max="59" required>
                        <select class="form-control" name="exam_ampm" id="exam_ampm" required>
                           <option value="AM">AM</option>
                           <option value="PM">PM</option>
                        </select>
                     </div>
                  </div>
                  <span id="error-message" style="text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <label for="limit">Qty</label>
                     <input type="number" class="form-control" name="limit" id="reschedlimit" min="1" max="<?php echo $todayexamCount; ?>" required>
                  </div>
                  <span id="error-message-limit" style="color: red; text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <button type="button" class="btn btn-primary" id="rescheduleBtnExamLPPP" onclick="openRescheduleExamLPPP(), closeRescheduleModal()" disabled>Reschedule</button>
                  </div>
               </form>
      </div>
   </div>
</div>

<!-- Set reschedule Modal (hidden by default) -->
<div id="InterviewmyModal" class="modal">
   <div class="modal-content">
      <span class="close" id="closeModalBtnInterview">&times;</span>
      <div class="modal-body">
      <form method="post" id="InterviewExamModal" action="<?php echo $_SERVER['PHP_SELF']; ?>">
               <h2 style="text-align: center;">Set Interview Date</h2>
               <p style="margin-top: -15px; text-align: center; opacity: 0.8;">For applicants that have <strong>"Interview"</strong> status</p>
                  <div class="form-group">
                     <label for="interview_date">Date</label>
                     <input type="date" name="interview_date" id="set_interview_date" class="form-control" required onkeydown="preventInput(event)"
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
                        <input type="number" name="interview_hours" id="set_interview_hours" class="form-control" min="1" max="12" required>
                        <span style="margin: 0 5px;">:</span>
                        <input type="number" name="interview_minutes" id="set_interview_minutes" class="form-control" min="0" max="59" required>
                        <select class="form-control" name="interview_ampm" id="set_interview_ampm" required>
                           <option value="AM">AM</option>
                           <option value="PM">PM</option>
                        </select>
                     </div>
                  </div>
                  <span id="interview-error-message" style="text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <label for="limit">Qty</label>
                     <input type="number" class="form-control" name="limit" id="SetInterviewlimit" min="1" max="<?php echo $interviewCount; ?>" required>
                  </div>
                  <span id="interview-error-message-limit" style="color: red; text-align: center; display: flex; justify-content: center;"></span>
                  <div class="form-group">
                     <button type="button" class="btn btn-primary" id="InterviewBtnExamLPPP" onclick="openInterviewExamLPPP(), closeInterviewModal()" disabled>Set Interview</button>
                  </div>
               </form>
      </div>
   </div>
</div>
  <!-- end search and reschedule -->
<!-- table for displaying the applicant list -->
<div class="background">
<div class="applicant-info">

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
      <table>
         <tr>
            <th>NO.</th>
            <th>CONTROL NUMBER</th>
            <th>LAST NAME</th>
            <th>FIRST NAME</th>
            <!-- <th>BARANGAY</th> -->
            <th>STATUS</th>
            <th>EXAM DATE</th>
            <th>INTERVIEW DATE</th>
         </tr>
         <?php
$counter = 1;

         // Display applicant info using a table
         while ($row = mysqli_fetch_assoc($result)) {
            // Inside the loop that displays applicant info
            echo '<tr class="applicant-row contents" data-exam-date="' . $row['exam_date'] . '" onclick="seeMore(\'' . $row['lppp_reg_form_id'] . '\')" style="cursor: pointer;">';
               echo '<td><strong>' . $counter++ . '</strong></td>';
            echo '<td>' . strtoupper($row['control_number']) . '</td>';
            echo '<td>' . strtoupper($row['last_name']) . '</td>';
            echo '<td>' . strtoupper($row['first_name']) . '</td>';
            // echo '<td>' . strtoupper($row['barangay']) . '</td>';
            echo '<td>' . strtoupper($row['status']) . '</td>';
            echo '<td>' . strtoupper($row['exam_date']) . '</td>';
     echo '<td>' . (strtoupper($row['interview_date']) === '0000-00-00' ? 'N/A' : strtoupper($row['interview_date'])) . '</td>';

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
      <script  src="../../../js/LPPPExam.js"></script>
      <script  src="../../../js/LPPPInterview.js"></script>
      <script  src="../../../js/LPPPSetInterview.js"></script>
      <script  src="../../../js/LPPPStatus_Popup.js"></script>
      <script  src="../../../js/rescheduleEXAM.js"></script>

<script>

 function seeMore(id) {
    // Redirect to a page where you can retrieve the reserved data based on the given ID
    window.location.href = "lppp_information.php?lppp_reg_form_id=" + id;
}    

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
    const rescheduledateInputEXAM = document.getElementById('exam_date');
    const reschedulehoursInputEXAM = document.getElementById('exam_hours');
    const rescheduleminutesInputEXAM = document.getElementById('exam_minutes');
    const rescheduleperiodInputEXAM = document.getElementById('exam_ampm');
    const reschedulelimitInputEXAM = document.getElementById('reschedlimit');
    const rescheduleerrorMessageEXAM = document.getElementById('error-message');
    const rescheduleerrorMessageEXAMLimit = document.getElementById('error-message-limit');

    // Add input event listeners to date/time inputs
    rescheduledateInputEXAM.addEventListener('input', validateDateTimeInputResched);
    reschedulehoursInputEXAM.addEventListener('input', validateDateTimeInputResched);
    rescheduleminutesInputEXAM.addEventListener('input', validateDateTimeInputResched);
    rescheduleperiodInputEXAM.addEventListener('input', validateDateTimeInputResched);

    // Add input event listener to the limit input
    reschedulelimitInputEXAM.addEventListener('input', validaterescheduleLimitInput);

    function validateDateTimeInputResched() {
    const selectedDate = new Date(rescheduledateInputEXAM.value);
    const hours = parseInt(reschedulehoursInputEXAM.value);
    const minutes = parseInt(rescheduleminutesInputEXAM.value);
    const period = rescheduleperiodInputEXAM.value;

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
    const inputElements = [rescheduledateInputEXAM, reschedulehoursInputEXAM, rescheduleminutesInputEXAM, rescheduleperiodInputEXAM];

    if (isDateTimeValid) {
        // If input is valid, remove 'invalid' class
        inputElements.forEach((element) => {
            element.classList.remove('invalid');
        });

        selectedDate.setHours(adjustedHours, minutes, 0, 0);
        const currentDate = new Date();

        if (selectedDate >= currentDate) {
            rescheduleerrorMessageEXAM.textContent = '';
        }  else {
            inputElements.forEach((element) => {
                element.classList.add('invalid');
            });
            rescheduleerrorMessageEXAM.textContent = 'Invalid Date and time';
            rescheduleerrorMessageEXAM.style.color= 'red';
        }
    } else {
        // If input is invalid, add 'invalid' class
        inputElements.forEach((element) => {
            element.classList.add('invalid');
        });
        rescheduleerrorMessageEXAM.textContent = 'Ensure the date and time are not earlier than the current time.';
        rescheduleerrorMessageEXAM.style.color= 'gray';

    }
}

function validaterescheduleLimitInput() {
    const limit = parseInt(reschedulelimitInputEXAM.value);
    const isLimitValid =
        !isNaN(limit) &&
        limit >= 1 &&
        limit <= <?php echo $todayexamCount; ?>;

    // Add or remove 'invalid' class based on validation
    if (isLimitValid) {
        reschedulelimitInputEXAM.classList.remove('invalid');
        rescheduleerrorMessageEXAMLimit.textContent = '';
    } else {
        reschedulelimitInputEXAM.classList.add('invalid');
        rescheduleerrorMessageEXAMLimit.textContent = 'Quantity cannot exceed to <?php echo $todayexamCount; ?>.';
        rescheduleerrorMessageEXAM.style.color= 'red';

    }
}

// Get references to the required input fields and the save button
const requiredInputsEXAM = document.querySelectorAll('#rescheduleExamModal [required]');
const rescheduleBtnExam = document.getElementById('rescheduleBtnExamLPPP');

// Function to check if all required inputs are valid
function checkRequiredInputsReschedule() {
    const allInputsValidReschedule = Array.from(requiredInputsEXAM).every((input) => {
        return input.value.trim() !== '' && !input.classList.contains('invalid');
    });

    if (allInputsValidReschedule) {
        rescheduleBtnExam.removeAttribute('disabled');
    } else {
        rescheduleBtnExam.setAttribute('disabled', 'true');
    }
}

// Add input event listeners to required input fields
requiredInputsEXAM.forEach((input) => {
    input.addEventListener('input', checkRequiredInputsReschedule);
});

// Initial check
checkRequiredInputsReschedule();
</script>


<script>
    const interviewdateInput = document.getElementById('set_interview_date');
    const interviewhoursInput = document.getElementById('set_interview_hours');
    const interviewminutesInput = document.getElementById('set_interview_minutes');
    const interviewperiodInput = document.getElementById('set_interview_ampm');
    const interviewlimitInput = document.getElementById('SetInterviewlimit');
    const interviewerrorMessage = document.getElementById('interview-error-message');
    const interviewerrorMessageLimit = document.getElementById('interview-error-message-limit');

    // Add input event listeners to date/time inputs
    interviewdateInput.addEventListener('input', validateDateTimeInputINTERV);
    interviewhoursInput.addEventListener('input', validateDateTimeInputINTERV);
    interviewminutesInput.addEventListener('input', validateDateTimeInputINTERV);
    interviewperiodInput.addEventListener('input', validateDateTimeInputINTERV);

    // Add input event listener to the limit input
    interviewlimitInput.addEventListener('input', validateinterviewLimitInput);

    function validateDateTimeInputINTERV() {
    const selectedDate = new Date(interviewdateInput.value);
    const hours = parseInt(interviewhoursInput.value);
    const minutes = parseInt(interviewminutesInput.value);
    const period = interviewperiodInput.value;

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
    const inputElements = [interviewdateInput, interviewhoursInput, interviewminutesInput, interviewperiodInput];

    if (isDateTimeValid) {
        // If input is valid, remove 'invalid' class
        inputElements.forEach((element) => {
            element.classList.remove('invalid');
        });

        selectedDate.setHours(adjustedHours, minutes, 0, 0);
        const currentDate = new Date();

        if (selectedDate >= currentDate) {
            interviewerrorMessage.textContent = '';
        }  else {
            inputElements.forEach((element) => {
                element.classList.add('invalid');
            });
            interviewerrorMessage.textContent = 'Invalid Date and time';
            interviewerrorMessage.style.color= 'red';
        }
    } else {
        // If input is invalid, add 'invalid' class
        inputElements.forEach((element) => {
            element.classList.add('invalid');
        });
        interviewerrorMessage.textContent = 'Ensure the date and time are not earlier than the current time.';
        interviewerrorMessage.style.color= 'gray';

    }
}

function validateinterviewLimitInput() {
    const limit = parseInt(interviewlimitInput.value);
    const isLimitValid =
        !isNaN(limit) &&
        limit >= 1 &&
        limit <= <?php echo $interviewCount; ?>;

    // Add or remove 'invalid' class based on validation
    if (isLimitValid) {
        interviewlimitInput.classList.remove('invalid');
        interviewerrorMessageLimit.textContent = '';
    } else {
        interviewlimitInput.classList.add('invalid');
        interviewerrorMessageLimit.textContent = 'Quantity cannot exceed to <?php echo $interviewCount; ?>.';
        interviewerrorMessage.style.color= 'red';

    }
}

// Get references to the required input fields and the save button
const requiredInputs = document.querySelectorAll('#InterviewExamModal [required]');
const interviewBtnExam = document.getElementById('InterviewBtnExamLPPP');

// Function to check if all required inputs are valid
function checkRequiredInputsINTERVule() {
    const allInputsValidINTERVule = Array.from(requiredInputs).every((input) => {
        return input.value.trim() !== '' && !input.classList.contains('invalid');
    });

    if (allInputsValidINTERVule) {
        interviewBtnExam.removeAttribute('disabled');
    } else {
        interviewBtnExam.setAttribute('disabled', 'true');
    }
}

// Add input event listeners to required input fields
requiredInputs.forEach((input) => {
    input.addEventListener('input', checkRequiredInputsINTERVule);
});

// Initial check
checkRequiredInputsINTERVule();

</script>
</body>
</html>