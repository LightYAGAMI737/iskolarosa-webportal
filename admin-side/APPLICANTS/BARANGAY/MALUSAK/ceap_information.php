<?php
   session_start();
   if (!isset($_SESSION['username'])) {
       echo 'You need to log in to access this page.';
       exit();
   }
   
   $currentPage = 'ceap_list';
   $currentSubPage = 'new applicant';
   
   include '../../../php/config_iskolarosa_db.php';
   
   if (isset($_GET['ceap_reg_form_id'])) {
       $ceapRegFormId = $_GET['ceap_reg_form_id'];
   } else {
       echo 'No applicant selected.';
       exit();
   }
   
   $id = $_GET['ceap_reg_form_id'];
   $query = "SELECT * FROM ceap_reg_form WHERE ceap_reg_form_id = ?";
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "i", $id); 
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
SELECT DISTINCT p.last_name, p.first_name, p.ceap_reg_form_id, p.form_submitted, t.status, t.reason, t.status_updated_at, t.interview_date, e.employee_username AS updated_by, l.previous_status AS prevSTAT , l.updated_status AS currentSTAT , l.timestamp
FROM ceap_reg_form p
JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
LEFT JOIN applicant_status_logs l ON p.ceap_reg_form_id = l.ceap_reg_form_id
LEFT JOIN employee_logs e ON l.employee_logs_id = e.employee_logs_id
WHERE p.ceap_reg_form_id = ?
ORDER BY l.timestamp ASC";

$stmtTable = mysqli_prepare($conn, $tempAccountSqlTable);
mysqli_stmt_bind_param($stmtTable, "s", $id); // Bind control number parameter
mysqli_stmt_execute($stmtTable);
$tempAccountResultTable = mysqli_stmt_get_result($stmtTable);

   // Fetch the applicant's status from the database
   $query = "SELECT status,reason, interview_date FROM temporary_account WHERE ceap_reg_form_id = ?";
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "i", $id);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   
   if (mysqli_num_rows($result) > 0) {
       $row = mysqli_fetch_assoc($result);
       $applicantStatus = $row['status'];
    $applicantreason = $row['reason'];
    $applicantinterview_date = $row['interview_date'];

   } else {
       $applicantStatus = ''; // Set a default value if status is not found
   }
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
      <link rel="stylesheet" href="../../../css/ceap_information.css">
      <link rel="stylesheet" href="../../../css/status_popup.css">
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
         include '../../../php/status_popup.php';
         include '../../../php/confirmStatusPopUp.php';
         include '../../side_bar_barangay_information.php';
         
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
         
         <div class="applicant-info">
    <h2 style="margin-top: -40px;">Personal Information</h2>
    <table>
        <?php 
           // Define the desired sequence of fields
           $desiredFields = ['control_number', 'last_name', 'first_name', 'middle_name', 'date_of_birth', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number', 'active_email_address', 'house_number', 'province', 'municipality', 'barangay', 'control_number'];
           
           foreach ($desiredFields as $field) : ?>
               <?php if (isset($applicantInfo[$field]) && $applicantInfo[$field] !== 'N/A') : ?>
                   <tr>
                       <th><?php echo ucwords(str_replace('_', ' ', $field)); ?>:</th>
                       <td>
                           <?php 
                           if ($field === 'date_of_birth') {
                               echo $applicantInfo[$field]; // Display date of birth
                           } elseif ($field === 'last_name' && isset($applicantInfo['suffix_name']) && $applicantInfo['suffix_name'] !== 'N/A') {
                               echo $applicantInfo['last_name'] . ' ' . $applicantInfo['suffix_name']; // Display last name and suffix
                           } else {
                               echo $applicantInfo[$field];
                           }
                           ?>
                       </td>
                   </tr>
                   <?php if ($field === 'date_of_birth') : ?>
                   <tr>
                       <th>Age:</th>
                       <td>
                           <?php
                           // Calculate age
                           $birthDate = new DateTime($applicantInfo[$field]);
                           $currentDate = new DateTime();
                           $age = $currentDate->diff($birthDate);
                           echo $age->y . ' years old';
                           ?>
                       </td>
                   </tr>
                   <?php endif; ?>
               <?php endif; ?>
           <?php endforeach; ?>
       </table>
   </div>
   
<!-- Table 3: Educational Background -->
<div class="applicant-info">
    <h2>Educational Background</h2>
    <table>
        <?php 
            $desiredSequence = [
                'elementary_school', 'secondary_school', 'senior_high_school', 'school_name',  'school_type', 'school_address',
                'course_enrolled', 'year_level', 'current_semester', 'no_of_units',  
                'graduating', 'expected_year_of_graduation',
                 'student_id_no'
            ];

            foreach ($desiredSequence as $field) : 
                if (isset($applicantInfo[$field])) : ?>
                    <?php if ($field === 'school_name') : ?>
                        <tr>
                            <th><?php echo "Tertiary School Name:"; ?></th>
                            <td><?php echo $applicantInfo[$field]; ?></td>
                        </tr>
                        <?php elseif ($field === 'student_id_no') : ?>
                        <tr>
                            <th><?php echo "Student ID No. :"; ?></th>
                            <td><?php echo $applicantInfo[$field]; ?></td>
                        </tr>
                     
                    <?php elseif (strpos($field, '_school') !== false && isset($applicantInfo[str_replace('_school', '_year', $field)])) : ?>
                        <tr>
                            <th><?php echo ucwords(str_replace('_', ' ', str_replace('_school', '', $field))) . ':'; ?></th>
                            <td><?php echo $applicantInfo[$field] . ', (' . $applicantInfo[str_replace('_school', '_year', $field)] . ')'; ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)) . ':'; ?></th>
                            <td><?php echo $applicantInfo[$field]; ?></td>
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
                  'guardian_firstname', 'guardian_lastname','guardian_occupation', 'guardian_relationship',
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
         <!-- table for displaying the uploaded files as images -->
         <div class="uploaded-files">
         <h2 class='to_center'>Scanned Documents</h2>
            <table>
               <tr>
                  <td>
                     <div class="file-group">
                      <?php
//Ensure Imagick is installed and enabled
if (!extension_loaded('imagick')) {
    echo 'Imagick extension is not available.';
    // Handle the situation where Imagick is not available
    exit;
}

// Loop through uploaded files and display them in groups of three
$fileCounter = 0;

$pdfFiles = array(
    'uploadVotersApplicant' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersApplicant.pdf',
    'uploadVotersParent' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersParent.pdf',
    'uploadITR' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_ITR.pdf',
    'uploadResidency' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Residency.pdf',
    'uploadCOR' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_COR.pdf',
    'uploadGrade' => '../../../../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Grade.pdf'
);

// Output image file paths
$imageFiles = array();
foreach ($pdfFiles as $key => $pdfFile) {
    $outputImage = '../../../../ceap-reg-form/converted-images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg';

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
                           // Voters applicant
                           echo '<table class="table" style="border: 1px solid black; margin: 0 auto !important;border-collapse: collapse;">';
                           echo "<tbody>";
                           echo "<tr>";
                           echo "<td>";
                           echo "<label>Voters Certificate Applicant</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           
                           // Voters Cert Parent
                           echo "<td>";
                           echo "<label>Voters Certificate Parent</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "</tr>";
                           
                           // TAX
                           echo "<tr>";
                           echo "<td>";
                           echo "<label>Income Tax Return</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           // Residency
                           echo "<td>";
                           echo "<label>Residency</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "</tr>";
                           
                           // COR
                           echo "<tr>";
                           echo "<td>";
                           echo "<label>Certificate of Registration</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           
                           // GRADE
                           echo "<td>";
                           echo "<label>GWA for Current Sem</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "</tr>";
                           
                           
                           // 2x2
                           echo "<tr>";

                           echo "<td>";
                           echo "<label>Applicant 2x2 Picture</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg'></div>";
                           echo "</div>";
                           echo "</td>";
                           echo "<td>";
                          
                           echo "</td>";
                           echo "</tr>";
                           
                           echo "</tbody>";
                           echo "</table>";
                           
                           ?>
                     </div>
                  </td>
               </tr>
            </table>
         
         <?php
         
            echo '<div style= "display: flex; justify-content: center; margin: 50px;">';
            // Check the status and determine which buttons to display
            if ($applicantStatus === 'In Progress') {
                echo '<button onclick="openReasonModal(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">DISQUALIFIED</button>';
                echo '<button onclick="openVerifiedPopup()" style="background-color: #FEC021;" class="status-button">VERIFIED</button>';
            } elseif ($applicantStatus === 'Verified') {
                echo '<button onclick="openReasonModal(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A;" class="status-button">DISQUALIFIED</button>';
            } elseif ($applicantStatus === 'Disqualified') {
                echo '<button onclick="openVerifiedPopup()" style="background-color: #FEC021;" class="status-button">VERIFIED</button>';
            } elseif ($applicantStatus === 'interview') {
                echo '<button onclick="openReasonModalFail(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">NOT GRANTEE</button>';
                echo '<button onclick="openGranteePopup()" style="background-color: #FEC021;" class="status-button">GRANTEE</button>';
            }
            echo '</div>';

            ?>
        
</div>
      </div>
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
}       // Check if the current status is "Disqualified" and display the reason if so
        if ($status == 'Disqualified' || $status ==  'Fail') {
            echo '<tr>';
            echo '<td colspan="3" style="font-style: italic;">Reason for Disqualification: <strong>' . $tempAccountRow['reason'] . '<strong></td>';
            echo '</tr>';
}
            ?>
        </tbody>
    </table>
</div>
</div>
      <!-- end applicant info -->
     <!-- Modal for entering reason -->
     <div id="reasonModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeReasonModal()">&times;</span>
        <h2>Enter Reason</h2>
        <!-- Replace text input with select dropdown -->
        <select name="reason" id="disqualificationReason" class="selectReason" onchange="checkOtherOption()">
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
        <button id="submitReason" onclick="submitStatusAndReason()" class="disabled">Submit</button>
    </div>
</div>

<script>
    // Function to show/hide the text input for "others" based on the selected option
    function checkOtherOption() {
        var reasonDropdown = document.getElementById("disqualificationReason");
        var otherReasonInput = document.getElementById("otherReason");

        if (reasonDropdown.value === "Others") {
            otherReasonInput.style.display = "block";
        } else {
            otherReasonInput.style.display = "none";
        }

    }
</script>

      <div id="reasonModalFail" class="modal">
         <div class="modal-content">
            <span class="close" onclick="closeReasonModalFail()">&times;</span>
            <h2>Enter Reason</h2>
        <!-- Replace text input with select dropdown -->
        <select name="reasonFail" id="FailReason" class="selectReason" onchange="checkOtherOptionFail()">
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
            <button id="submitReasonFail" onclick="submitStatusAndReasonFail()" class="disabled">Submit</button>
         </div>
      </div>
      
<script>
    // Function to show/hide the text input for "others" based on the selected option
    function checkOtherOptionFail() {
        var reasonDropdownFail = document.getElementById("FailReason");
        var otherReasonInputFail = document.getElementById("otherReasonFail"); // Corrected ID

        if (reasonDropdownFail.value === "OthersFail") {
            otherReasonInputFail.style.display = "block";
        } else {
            otherReasonInputFail.style.display = "none";
        }
    }
</script>

      <!-- <footer class="footer">
       
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/status_popup.js"></script>
      <script  src="../../../js/updateStatusDisqualified.js"></script>
      <script  src="../../../js/updateStatusFail.js"></script>
      <script  src="../../../js/updateStatusVerified.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script type="text/javascript">
         var ceapRegFormId = <?php echo $ceapRegFormId; ?>;
      </script>
<script>
    function expandImage(img) {
   var imageUrl = img.src;
   window.open(imageUrl, "_blank"); // Open the image in a new tab/window
}
    </script>
   </body>
</html>