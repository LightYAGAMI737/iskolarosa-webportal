<?php
session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

$currentPage = 'lppp_list';
$currentSubPage = 'LPPP';

include '../../../php/config_iskolarosa_db.php';

// Get the lppp_reg_form_id parameter from the URL
if (isset($_GET['lppp_reg_form_id'])) {
    $LPPPregFormID = $_GET['lppp_reg_form_id'];
} else {
    echo 'No applicant selected.';
    exit();
}

$id = $_GET['lppp_reg_form_id'];
$query = "SELECT * FROM lppp_reg_form WHERE lppp_reg_form_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id); // "i" indicates an integer parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $applicantInfo = mysqli_fetch_assoc($result);
} else {
    echo 'Applicant not found.';
    exit();
}

  // Prepare the second query
  $tempAccountSqlTable = "
  SELECT DISTINCT p.last_name, p.first_name, p.lppp_reg_form_id, t.status, p.form_submitted, t.reason, t.status_updated_at, t.interview_date, e.employee_username AS updated_by, l.previous_status AS prevSTAT , l.updated_status AS currentSTAT , l.timestamp
  FROM lppp_reg_form p
  JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
  LEFT JOIN applicant_status_logs l ON p.lppp_reg_form_id = l.lppp_reg_form_id
  LEFT JOIN employee_logs e ON l.employee_logs_id = e.employee_logs_id
  WHERE p.lppp_reg_form_id = ?
  ORDER BY l.timestamp ASC";
  
  $stmtTable = mysqli_prepare($conn, $tempAccountSqlTable);
  mysqli_stmt_bind_param($stmtTable, "s", $id); // Bind control number parameter
  mysqli_stmt_execute($stmtTable);
  $tempAccountResultTable = mysqli_stmt_get_result($stmtTable);
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
      <link rel="stylesheet" href="../../../css/status_popup.css">
      <link rel="stylesheet" href="../../../css/ceap_information.css">
      <style> 
/* Styles for the table */
.table-status {
    margin: 50px 100px;
    overflow-x: auto;
}

.table-status table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.table-status th, .table-status td {
    border: 1px solid #000;
    padding: 10px;
    text-align: left;
}

         </style>
   </head>
   <body>
      <?php 
         include '../../../php/LPPPStatus_Popup.php';
         include '../../side_bar_lppp_grantee.php';
         ?>
         
      <!-- home content-->    
   
<!-- table for displaying the applicant info -->
<div class="table-section">
    <!-- Back button -->
    <div class="back-button-container">
        <a href="#" class="back-button" onclick="goBack()">
            <i><i class="ri-close-circle-line"></i></i>
        </a>
    </div>
<!-- Table 1: Personal Info -->
<div class="applicant-info">
    <h2 style="margin-top: -55px;">Personal Information</h2>
    
    <table>
        
        <?php foreach ($applicantInfo as $field => $value) : ?>
            <?php if (in_array($field, [
                'control_number', 'last_name', 'first_name', 'middle_name', 'suffix_name',
                'date_of_birth','gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number',
                'active_email_address', 'house_number', 'province', 'municipality', 'barangay'
            ])) : ?>
                <tr>
                <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                  <td>
                     <?php
                        if ($field === 'date_of_birth') {
                            echo $value; // Display date of birth
                        } else {
                            echo $value;
                        }
                        ?>
                  </td>
               </tr>
               <?php if ($field === 'date_of_birth') : ?>
               <tr>
                  <th>Age:</th>
                  <td>
                     <?php
                        // Calculate age from date of birth
                        $birthDate = new DateTime($value);
                        $currentDate = new DateTime();
                        $age = $currentDate->diff($birthDate);
                        
                        // Check if the birthday has occurred in the current year
                        if (($currentDate < $birthDate->modify('+' . $age->y . ' years'))) {
                            $age->y--; // Decrement the age by 1
                            $birthDate->modify('-1 year'); // Adjust the birthdate for correct calculation
                        }
                        
                        // Reset the birthdate to its original value
                        $birthDate->modify('+' . $age->y . ' years');
                        
                        echo $age->y . ' years old'; // Display calculated age
                        ?>
                  </td>
               </tr>
               <?php endif; ?>
               <?php endif; ?>
               <?php endforeach; ?>
            </table>
         </div>

<!-- Table 2: Family Background -->
<div class="applicant-info">
    <h2>Family Background</h2>
    <table>
        <?php foreach ($applicantInfo as $field => $value) : ?>
            <?php if (in_array($field, [
                'guardian_lastname','guardian_firstname', 'guardian_occupation', 'guardian_relationship',
                'guardian_monthly_income', 'guardian_annual_income'
            ])) : ?>
                <tr>
                    <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                    <td><?php echo $value; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>

<!-- Table 3: Educational Background -->
<div class="applicant-info">
    <h2>Educational Background</h2>
    <table>
        
        <?php foreach ($applicantInfo as $field => $value) : ?>
            <?php if (in_array($field, [
                'elementary_school', 'elementary_year',
                'school_address', 'student_id_no'
            ])) : ?>
                <tr>
                    <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                    <td><?php echo $value; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
<?php
$queryScore = "SELECT status, applicant_score FROM lppp_temporary_account WHERE lppp_temporary_account_id = ?";
$stmtScore = mysqli_prepare($conn, $queryScore);
mysqli_stmt_bind_param($stmtScore, "i", $id); // "i" indicates an integer parameter
mysqli_stmt_execute($stmtScore);
$resultScore = mysqli_stmt_get_result($stmtScore);

if (mysqli_num_rows($resultScore) > 0) {
    $applicantInfoScore = mysqli_fetch_assoc($resultScore);

    // Check if the status is "interview" before displaying the table
    if ($applicantInfoScore['status'] === 'interview') {
?>
        <!-- Table 4: Applicant Score -->
        <div class="applicant-info">
            <h2>Applicant Score</h2>
            <table>
                <?php foreach ($applicantInfoScore as $field => $value) : ?>
                    <?php if (in_array($field, [
                        'applicant_score',
                    ])) : ?>
                        <tr>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                            <td><?php echo $value; ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>
<?php
    } 
} else {
    echo 'Applicant not found.';
    exit();
}
?>

 <!-- table for displaying the uploaded files as images -->
 <div class="uploaded-files">
            <table>
               <tr>
                  <td>
                     <div class="file-group">
                     <?php
// Ensure Imagick is installed and enabled
if (!extension_loaded('imagick')) {
    echo 'Imagick extension is not available.';
    // Handle the situation where Imagick is not available
    exit;
}

// Loop through uploaded files and display them in groups of three
$fileCounter = 0;

$pdfFiles = array(
    'uploadVotersParent' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersParent.pdf',
    'uploadITR' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_ITR.pdf',
    'uploadResidency' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Residency.pdf',
    'uploadGrade' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Grade.pdf'
);

// Output image file paths
$imageFiles = array();
foreach ($pdfFiles as $key => $pdfFile) {
    $outputImage = '../../../../lppp-reg-form/converted-images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg';

    try {
        $imagick = new Imagick();
        $imagick->readImage($pdfFile);
        $imagick->setIteratorIndex(0); // Adjust the page index if needed

        // Optional: Set resolution and background color
        // $imagick->setResolution(300, 300);
        // $imagick->setImageBackgroundColor('white');

        $imagick->setImageCompressionQuality(100);
        $imagick->setImageFormat('jpg');
        $imagick->writeImage($outputImage);
        $imagick->destroy();

        // Log success
        echo "<script>console.log('Conversion success for $key. Output Image: $outputImage');</script>";
    } catch (Exception $e) {
        // Log error
        echo "<script>console.error('Error converting $key:', '" . $e->getMessage() . "', PDF File: $pdfFile, Output Image: $outputImage');</script>";
    }
}
                           echo "<h2 class='to_center'>Scanned Documents</h2>";
                           // Voters applicant
                           echo '<table class="table" style="width: 80%;">';
                           echo "<tbody>";
                           echo "<tr>";
                           // Voters Cert Parent
                           echo "<td>";
                           echo "<label>Voters Certificate Parent</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           
                           // TAX
                           echo "<td>";
                           echo "<label>Income Tax Return</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg'></div>";
                           echo "</div>";
                           echo "</td>";


                           // Residency
                           echo "<tr>";
                           echo "<td>";
                           echo "<label>Residency</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
           
                   
                           
                           // GRADE\        
                           echo "<td>";
                           echo "<label>GWA for Current Sem</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../lppp-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "</tr>";
                           echo "</tbody>";
                           echo "</table>";
                           
                           //2x2
                           echo "<td>";
                           echo "<label>Applicant 2x2 Picture</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../lppp-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../lppp-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "</tr>";
                           
                           echo "</tbody>";
                           echo "</table>";
                           
                           
                           ?>
                     </div>
                  </td>
               </tr>
            </table>
         </div>
      </div>
      <!-- end applicant info -->
        <!-- Modal for entering reason -->
        <div id="reasonModalLPPP" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeReasonModalLPPP()">&times;</span>
                <h2>Enter Reason</h2>
        <!-- Replace text input with select dropdown -->
        <select name="reason" id="disqualificationReasonLPPP" class="selectReason" onchange="checkOtherOption()">
            <option value="" disabled selected>Select a reason</option>
            <option value="Failure to Follow Instructions">Failure to Follow Instructions</option>
            <option value="Inaccurate or False Information">Inaccurate or False Information</option>
            <option value="Incomplete Application">Incomplete Application</option>
            <option value="Failure to Meet Eligibility Requirements">Failure to Meet Eligibility Requirements</option>
            <option value="Academic Dishonesty">Academic Dishonesty</option>
            <option value="Exceeding Income Limits">Exceeding Income Limits</option>
            <option value="Discrepancies in Academic Records">Discrepancies in Academic Records</option>
            <option value="Non-compliance with Additional Requirements">Non-compliance with Additional Requirements</option>
            <option value="Others">Others</option>
        </select>
        <!-- Retain the text input for "others" -->
        <input type="text" name="otherReason" id="otherReason" minlength="5" maxlength="150" placeholder="Enter other reason" style="display: none;">
                <button id="submitReasonLPPP" onclick="submitStatusAndReasonLPPP()" class="disabled">Submit</button>
            </div>
        </div>
        
<script>
    // Function to show/hide the text input for "others" based on the selected option
    function checkOtherOption() {
        var reasonDropdown = document.getElementById("disqualificationReasonLPPP");
        var otherReasonInput = document.getElementById("otherReason");

        if (reasonDropdown.value === "Others") {
            otherReasonInput.style.display = "block";
        } else {
            otherReasonInput.style.display = "none";
        }

    }
</script>

        <div id="reasonModalLPPPFail" class="modal">
         <div class="modal-content">
            <span class="close" onclick="closeReasonModalLPPPFail()">&times;</span>
            <h2>Enter Reason</h2>
        <!-- Replace text input with select dropdown -->
        <select name="reasonFail" id="FailReasonLPPP" class="selectReason" onchange="checkOtherOptionFail()">
            <option value="" disabled selected>Select a reason</option>
            <option value="Failure to Follow Instructions">Failure to Follow Instructions</option>
            <option value="Inaccurate or False Information">Inaccurate or False Information</option>
            <option value="Incomplete Application">Incomplete Application</option>
            <option value="Failure to Meet Eligibility Requirements">Failure to Meet Eligibility Requirements</option>
            <option value="Academic Dishonesty">Academic Dishonesty</option>
            <option value="Exceeding Income Limits">Exceeding Income Limits</option>
            <option value="Discrepancies in Academic Records">Discrepancies in Academic Records</option>
            <option value="Non-compliance with Additional Requirements">Non-compliance with Additional Requirements</option>
            <option value="OthersFail">Others</option>
        </select>
        <!-- Retain the text input for "others" -->
        <input type="text" name="otherReasonFail" id="otherReasonFail" minlength="5" maxlength="150" placeholder="Enter other reason" style="display: none;">
            <button id="submitReasonFail" onclick="submitStatusAndReasonLPPPFail()" class="disabled">Submit</button>
         </div>
      </div>
<script>
    // Function to show/hide the text input for "others" based on the selected option
    function checkOtherOptionFail() {
        var reasonDropdownFail = document.getElementById("FailReasonLPPP");
        var otherReasonInputFail = document.getElementById("otherReasonFail"); // Corrected ID

        if (reasonDropdownFail.value === "OthersFail") {
            otherReasonInputFail.style.display = "block";
        } else {
            otherReasonInputFail.style.display = "none";
        }
    }
</script>
      <div class="applicant-history">

<div class="table-status">
    <table>
        <thead>
            <tr>
                <th>Updated Date</th>
                <th>Status</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $interviewDisplayed = false; // Initialize the variable to track 'interview' status

            // Fetch all rows in an array
$tempAccountRows = mysqli_fetch_all($tempAccountResultTable, MYSQLI_ASSOC);
echo '<h4>Applicant Status History</h4>';

$inProgressDisplayed = false; // Initialize flag to track if the "In Progress" row has been displayed

for ($i = 0; $i < count($tempAccountRows); $i++) {
    $tempAccountRow = $tempAccountRows[$i];
    $updated_date = $tempAccountRow['timestamp'];
    $UpdatedDateFormatted = date('F d, Y', strtotime($updated_date));
    $interview_date = $tempAccountRow['interview_date'];
    $dateFormatted = date('F d, Y', strtotime($interview_date));
    $status_updated_at = $tempAccountRow['status_updated_at'];
    $status_updated_atFormatted = date('F d, Y', strtotime($status_updated_at));
    $form_submitted = $tempAccountRow['form_submitted'];
    $form_submittedFormatted = date('F d, Y', strtotime($form_submitted));
    $status = $tempAccountRow['status']; // Fetch the current status
    $updatedBy = $tempAccountRow['updated_by']; // You need to fetch and populate this value

    // Check if the status is "In Progress" and it hasn't been displayed yet
    if (!$inProgressDisplayed) {
        // Display the "In Progress" row
        echo '<tr>';
        echo '<td data-label="Date:">' . $form_submittedFormatted . '</td>';
        echo '<td data-label="Status:">IN PROGRESS</td>';
        echo '<td data-label="Approved by:">-</td>';
        echo '</tr>';
        $inProgressDisplayed = true; // Set the flag to true to indicate that the "In Progress" row has been displayed
    }

    // Check if this row has a previous status
    if (!empty($tempAccountRow['currentSTAT']) && $status != 'In Progress') {
        // Display a new row for the previous status
        echo '<tr>';
        echo '<td data-label="Date:">' . $UpdatedDateFormatted . '</td>';
        echo '<td data-label="Status:">' . strtoupper($tempAccountRow['currentSTAT']) . '</td>';
        echo '<td data-label="Approved by:">' . ($status == 'In Progress' ? '-' : $updatedBy) . '</td>';
        echo '</tr>';
    }
}
            ?>
        </tbody>
    </table>
</div>
</div>
         <footer class="footer">
       
         <?php
// Fetch the applicant's status from the database
$query = "SELECT status, interview_date, applicant_score FROM lppp_temporary_account WHERE lppp_reg_form_id = ?";
$stmt = mysqli_prepare($conn, $query);

// Assuming $id is the applicant's ID
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Get the result of the query
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    // Check if a row was fetched
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Get status and interview_date from the fetched row
        $applicantStatus = $row['status'];
        $applicantInterviewDate = $row['interview_date'];

        // Check the status and determine which buttons to display
        if ($applicantStatus === 'In Progress') {
            echo '<button onclick="openReasonModalLPPP(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Disqualified</button>';
            echo '<button onclick="openLPPPVerifiedPopup()" style="background-color: #FEC021;" class="status-button">Verified</button>';
        } elseif ($applicantStatus === 'exam') {
            echo '<form id="scoreForm">';
            echo '<label for="applicantScore" class="ScoreLabel">Enter Applicant Score (0-100):</label>';
            echo '<input type="text" class="ScoreInput" id="applicantScore" name="applicantScore" minlength="1" maxlength="3" required>';
            echo '<input type="hidden" id="LPPPapplicantId" value="' . $id . '">';
            echo '<button type="button" id="ScoreFormBTNSubmit" class="ScoreBTN" value="Submit" onclick="openExamScorePopup()" disabled>Submit</button>';
            echo '<br><span id="ScoreInputError" style="color: red; display: inline-block; margin-bottom: 30px;"></span>';
            echo '</form>';
        } elseif ($applicantStatus === 'interview' && $applicantInterviewDate === '0000-00-00') {
        } elseif ($applicantStatus === 'interview') {
            echo '<button onclick="openReasonModalLPPPFail(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Not Grantee</button>';
            echo '<button onclick="openLPPPGranteePopup()" style="background-color: #FEC021;" class="status-button">Grantee</button>';
        }
    } else {
        // No row found
        $applicantStatus = '';
        $applicantInterviewDate = '';
    }
} else {
    // Error in executing query
    echo "Error executing query: " . mysqli_error($conn);
}
?>
         </footer>
         </main>
         <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/LPPPStatus_Popup.js"></script>
      <script  src="../../../js/LPPPReasonModal.js"></script>
      <script  src="../../../js/LPPPReasonModalFail.js"></script>
      <script  src="../../../js/scoreInputValidation.js"></script>
      
      <script type="text/javascript">
         var LPPPregFormID = <?php echo $LPPPregFormID; ?>;
      </script>
      <script>
  // Add an event listener for the Enter key on the input field
  document.getElementById('applicantScore').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      // Prevent the default Enter key behavior
      event.preventDefault();
    }
  });

  // Add an event listener for the button click
  document.getElementById('ScoreFormBTNSubmit').addEventListener('click', function() {
    // Call your custom function (e.g., openExamScorePopup()) or any other logic
    openExamScorePopup();
  });
</script>
<script>
 
 function seeMore(id) {
    // Redirect to the page for updating grade_level based on the given ID
    window.location.href = "lppp_grantee_information.php?lppp_reg_form_id=" + id;
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

const ExamScorePopup = document.getElementById('openExamScorePopup');
function openExamScorePopup() {
    ExamScorePopup.style.display = "block";
}

const LPPPGranteePopup = document.getElementById('openLPPPGranteePopup');
function openLPPPGranteePopup() {
    LPPPGranteePopup.style.display = "block";
}
const LPPPFailPopup = document.getElementById('LPPPFailPopUp');
function openLPPPFailPopup() {
    LPPPFailPopup.style.display = "block";
}

function closeLPPPStatusPopup() {
    if (ExamScorePopup) {
        ExamScorePopup.style.display = "none";
    }
    if (LPPPFailPopup) {
        LPPPFailPopup.style.display = "none";
    }
    if (LPPPGranteePopup) {
        LPPPGranteePopup.style.display = "none";
    }
 
}

const cancelButtons = document.querySelectorAll(".cancel-button");
cancelButtons.forEach((cancelButton) => {
  cancelButton.addEventListener("click", closeLPPPStatusPopup);
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

function expandImage(img) {
   var imageUrl = img.src;
   window.open(imageUrl, "_blank"); // Open the image in a new tab/window
}

function collapseImage(element) {
    element.style.display = 'none';
}

function goBack() {
      window.history.back();
  }

</script>
<script>
    var scoreForm = document.getElementById("scoreForm");
if (scoreForm) {
    var scoreBtn = document.getElementById("ScoreFormBTN");

    scoreBtn.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the form from submitting

        var applicantScore = parseInt(document.getElementById("applicantScore").value);
        var LPPPapplicantId = document.getElementById("LPPPapplicantId").value;

        // Additional validation for applicantScore
        if (isNaN(applicantScore) || applicantScore < 0 || applicantScore > 100) {
            alert('Please enter a valid score between 0 and 100.');
            return;
        }
        // Determine the new status based on the entered score
        var newStatus = applicantScore >= 75 ? 'interview' : 'Fail';

        // Log the values to the console
        console.log('Applicant Score:', applicantScore);
        console.log('Applicant ID:', LPPPapplicantId);
        console.log('New Status:', newStatus);

        // Call the updateStatus function to update status via AJAX
        updateStatusLPPPEXAM(newStatus, applicantScore, LPPPapplicantId);
    });
}

function updateStatusLPPPEXAM(status, applicantScore, LPPPapplicantId) {
    // Send an AJAX request to update the applicant status and score
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../../php/updateStatusLPPPExam.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('XHR Status:', xhr.status);
            if (xhr.status === 200) {
                // Handle the response here
                var response = xhr.responseText.trim();
                console.log('Response from server:', response);
                if (response === 'success') {
                   // closeExamPopupScore();
                    openconfirmationLPPPpopup();
                } else {
                    alert('Failed to updates status.');
                    console.error('Failed to make the request. HTTP status:', xhr.status);
                }
            } else {
                console.error('Failed to make the request. HTTP status:', xhr.status);
            }
        }
    };
    xhr.send("status=" + status + "&applicantScore=" + applicantScore + "&id=" + LPPPapplicantId);
}

function updateStatusLPPP(status, LPPPapplicantId) {
        // Send an AJAX request to update the applicant status
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../../php/updateStatusLPPP.php", true); // Replace with the actual URL
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Handle the response here
                var response = xhr.responseText.trim(); // Trim whitespace from the response text
                if (response === 'success') {
                    openconfirmationLPPPpopup();
                } else {
                    alert('Failed to update status.');
                }
            }
        };
        xhr.send("status=" + status + "&id=" + LPPPapplicantId);
    }
</script>


   </body>
</html>