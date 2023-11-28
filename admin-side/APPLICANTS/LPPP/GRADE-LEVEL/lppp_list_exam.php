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
   


   if (isset($_POST['saveBtn'])) {
    // Process and update interview dates
    $interviewDate = $_POST['interview_date'];
    $interview_hour = $_POST['interview_hour']; 
    $interview_minutes = $_POST['interview_minutes']; 
    $interview_ampm = $_POST['interview_ampm'];
    $limit = $_POST['limit'];
 
    if (!empty($interviewDate) && !empty($interview_hour) && !empty($interview_minutes) && !empty($interview_ampm) && !empty($limit)) {
        $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
        $limit = intval($limit);
 
        $qualifiedQuery = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, p.lppp_reg_form_id
        FROM lppp_reg_form p
        INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
        WHERE t.status = 'interview' AND interview_date = '0000-00-00'
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
 $adminUsername = $_SESSION['username'];
 $ceapRegFormId = $row['lppp_reg_form_id'];
 
 $updateTimeQuery = "UPDATE lppp_temporary_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ?, updated_by = ? WHERE lppp_reg_form_id = ?";
 $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
 mysqli_stmt_bind_param($stmtTimeUpdate, "siiisi", $interviewDate, $interview_hour, $interview_minutes, $interview_ampm, $adminUsername, $ceapRegFormId);
 mysqli_stmt_execute($stmtTimeUpdate);
 
 // Update the status to 'interview'
 $statusUpdateQuery = "UPDATE lppp_temporary_account SET status = 'interview' WHERE lppp_reg_form_id = ?";
 $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
 mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceapRegFormId);
 mysqli_stmt_execute($stmtStatusUpdate);
 
 $updateCount++;
 
 }
 
 // Redirect to prevent form resubmission
 header("Location: " . $_SERVER['REQUEST_URI']);
 exit();
 }
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
 $lpppverifiedCountQuery = "SELECT COUNT(*) AS lpppverifiedCount FROM lppp_temporary_account WHERE status = 'interview'  AND interview_date ='0000-00-00' ";
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
      <link rel="stylesheet" href="../../../css/ceap_interview.css">
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
                include '../../side_bar_lppp.php';

         ?>
             
      <!-- home content-->

<!-- search and reschedule -->
      <div class="form-group">

        <!-- Reschedule Button -->
        <?php
// Check if there are verified applicants
$hasVerifiedApplicants = hasVerifiedStatusInDatabase($conn);
// Check if there are exam applicants for the current date
$hasExamApplicants = hasExamInDatabase($conn);
// Disable the "Reschedule" button if there are no exam applicants
$rescheduleButtonDisabled = !$hasExamApplicants ? 'disabled' : '';

// Disable the "Set Interview" button if there are no verified applicants
$setInterviewButtonDisabled = !$hasVerifiedApplicants ? 'disabled' : '';
?>

<div class="reschedule-button">
    <button id="rescheduleButton" type="button" class="btn btn-primary"  onclick="openRescheduleModal()" <?php echo $rescheduleButtonDisabled; ?>>Reschedule</button>
</div>

<input type="text" name="search" class="form-control" id="search" placeholder="Search by Control Number or Last name" oninput="formatInput(this)">

<button type="button" class="btn btn-primary" style="margin-right: 10px;" onclick="searchApplicants()">Search</button>

<button type="button" class="btn btn-primary btn-rad" id="openModalBtn" <?php echo $setInterviewButtonDisabled; ?>>Set Interview</button>


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


<?php
  function hasexamInDatabase($conn) {
    $lpppquery = "SELECT COUNT(*) FROM lppp_temporary_account WHERE status = 'exam' AND exam_date = CURDATE()";
    $result = mysqli_query($conn, $lpppquery);
    $lpppcount = mysqli_fetch_row($result)[0];
    return $lpppcount > 0;

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
      <span class="close" id="closeModalBtn">&times;</span>
      <div class="modal-body">
         <form method="post" action="rescheduleEXAM.php">
         <h3 style="text-align: center;">Reschedule today's applicant.</h3>

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
            <div class="form-group">
               <label for="limit">Qty</label>
               <input type="number" class="form-control" name="limit" id="reschedlimit" min="1" max="<?php echo $lpppreschedCount; ?>"     required>
            </div>
            <div class="form-group">
               <button type="submit" name="rescheduleBtn" id="rescheduleBtn" style="background-color:#FEC021 !important; color: black;" class="btn btn-primary">Reschedule</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   
    const limitInput = document.getElementById('reschedlimit');

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
                  <input type="number" name="interview_hour" id="interview_hour" class="form-control" min="1" max="12" required>
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
               <input type="number" class="form-control" name="limit" id="limit" min="1" max="<?php echo $lpppverifiedCount; ?>" required>
            </div>
            <div class="form-group">
               <button type="submit" name="saveBtn" id="saveBtn" class="btn btn-primary">Set</button>
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
        <div class="filter-icon">
            <label for="filterLabel" id="filterLabel"  onclick="toggleFilterDropdown()">
                <i class="ri-filter-line"></i> Filter
            </label>
            <div class="filter-dropdown" id="filterDropdown">
                <ul>
                    <li><a href="#" id="interview_date_for_month" onclick="filterApplicants('interview_date_for_month')">interview Date for Month</a></li>
                    <li><a href="#" id="interview_date_today" onclick="filterApplicants('interview_date_today')">interview Date Today</a></li>
                </ul>
            </div>
            
        </div>
      <table>
         <tr>
            <th>NO.</th>
            <th>CONTROL NUMBER</th>
            <th>LAST NAME</th>
            <th>FIRST NAME</th>
            <th>BARANGAY</th>
            <th>STATUS</th>
            <th>EXAM</th>
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
            echo '<td>' . strtoupper($row['barangay']) . '</td>';
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
      <script src='../../../js/unpkg-layout.js'></script><script  src="../../../js/side_bar.js"></script>


      
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
function filterApplicants(selectedFilter) {
    // Store the selected filter in localStorage
    localStorage.setItem('selectedFilter', selectedFilter);

    // Get all rows in the table
    var rows = $('.applicant-row');

    if (selectedFilter === 'interview_date_today') {
        var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

        // Show all rows before applying the filter
        rows.show();

        // Hide rows where interview_date is not today
        rows.each(function () {
            var interviewDate = $(this).data('interview-date');
            if (interviewDate !== today) {
                $(this).hide();
            }
        });
    } else if (selectedFilter === 'interview_date_for_month') {
        // Show all rows before applying the filter
        rows.show();

        // Sort rows by interview_date
        rows.sort(function(a, b) {
            var interviewDateA = $(a).data('interview-date');
            var interviewDateB = $(b).data('interview-date');
            return interviewDateA.localeCompare(interviewDateB);
        });

        // Append sorted rows to the table
        $('.applicant-info table').append(rows);
    }

    // Update the filter dropdown label
    var filterLabel = document.getElementById("filterLabel");
    if (selectedFilter === 'interview_date_today') {
        filterLabel.innerHTML = '<i class="ri-filter-line"></i> Filter';
    } else if (selectedFilter === 'interview_date_for_month') {
        filterLabel.innerHTML = '<i class="ri-filter-line"></i> Filter';
    }
}

// Retrieve the selected filter option from localStorage and apply the filter
$(document).ready(function() {
    var selectedFilter = localStorage.getItem('selectedFilter');
    if (selectedFilter) {
        filterApplicants(selectedFilter);
    }
});

</script>

<script>
    function toggleFilterDropdown() {
        var filterDropdown = document.getElementById("filterDropdown");
        if (filterDropdown.style.display === "none") {
            filterDropdown.style.display = "block";
        } else {
            filterDropdown.style.display = "none";
        }
    }

    // Close the dropdown when clicking outside of it
    window.onclick = function(event) {
        if (!event.target.matches('#filterLabel')) {
            var dropdown = document.getElementById("filterDropdown");
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        }
    }

    function openRescheduleModal() {
    var modal = document.getElementById("reschedulemyModal");
    modal.style.display = "block";
}

</script>

<script>
    // Function to open the reschedule modal
    function openRescheduleModal() {
        var rescheduleButton = document.getElementById("rescheduleButton");
        if (!rescheduleButton.disabled) {
            var modal = document.getElementById("reschedulemyModal");
            modal.style.display = "block";
        }
    }

    // Function to close the modal
    function closeRescheduleModal() {
        var modal = document.getElementById("reschedulemyModal");
        modal.style.display = "none";
    }

    // Close the modal when the close button is clicked
    document.getElementById("closeModalBtn").addEventListener("click", function() {
        closeRescheduleModal();
    });

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById("reschedulemyModal");
        if (event.target === modal) {
            closeRescheduleModal();
        }
    }

    // Check if there are any applicants with exam date set to today and status 'exam'
    checkTodayExam();

    function checkTodayExam() {
        var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
         
         var hasTodayInterview = false; // Flag to track if there are applicants with today's interview
     
         $('.contents').each(function () {
             var examDate = $(this).data('exam-date');
             if (examDate === today) {
                 hasTodayexam = true;
                 return false; // Exit the loop early since we found a match
             }
         });
     
         // Disable the "Reschedule" button if no applicants have exam_date today
         var rescheduleButton = document.getElementById("rescheduleButton");
         if (!hasTodayexam) {
             rescheduleButton.disabled = true;
         }
     }
     
     // DOMContentLoaded event
     document.addEventListener("DOMContentLoaded", function() {
         filterApplicants('exam_date_today');
         const hoursInput = document.getElementById("exam_hours");
         hoursInput.addEventListener("input", function() {
             const hoursValue = parseInt(hoursInput.value);
             if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
                 hoursInput.value = '';
             }
         });
     
         const minutesInput = document.getElementById("exam_minutes");
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
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const hoursInput = document.getElementById("interview_hour");
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
        const hoursInput = document.getElementById("exam_hour");
        hoursInput.addEventListener("input", function() {
      
      const hoursValue = parseInt(hoursInput.value);
      if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
          hoursInput.value = '';
      }
  });

  const minutesInput = document.getElementById("exam_minutes");
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

    </script>
</body>
</html>