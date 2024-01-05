<?php
   session_start();
   
   // Check if the session is not set (user is not logged in)
   if (!isset($_SESSION['username'])) {
       echo 'You need to log in to access this page.';
       exit();
   }
   include '../../../php/config_iskolarosa_db.php';
   
   $currentPage = 'ceap_list';
   $currentSubPage = 'old applicant';
   
   // Get the ceap_personal_account_id parameter from the URL
   if (isset($_GET['ceap_personal_account_id'])) {
       $ceapRegFormIdOLD = $_GET['ceap_personal_account_id'];
   } else {
       echo 'No applicant selected.';
       exit();
   }
   
   $id = $_GET['ceap_personal_account_id'];
   $query = "SELECT * FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
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
         /* Add this CSS to your document or include it in your stylesheet */
         table.updated-info-table {
         width: 100%;
         border-collapse: collapse;
         }
         table.updated-info-table th,
         table.updated-info-table td {
         text-align: left;
         padding: 8px;
         border: 1px solid #ddd;
         }
         table.updated-info-table th {
         background-color: #f2f2f2;
         font-weight: bolder;
         }
         /* Set equal width for the table columns */
         table.updated-info-table th,
         table.updated-info-table td {
         width: 25%; /* You can adjust this percentage to your preference */
         }
      </style>
   </head>
   <body>
      <?php 
       include '../../../php/status_popup_old.php';
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
         <!-- Table for displaying the applicant updates -->
         <div class="applicant-info">
            <h2>Updated Information</h2>
            <table class="updated-info-table">
               <tr>
                  <th></th>
                  <th>Old Information</th>
                  <th>New Information</th>
                  <th>Updated On</th>
               </tr>
               <?php
                  // Define a mapping between field names and labels
                  $fieldLabels = [
                  'guardian_firstname' => 'Guardian First Name',
                  'guardian_lastname' => 'Guardian Last Name',
                  'guardian_occupation' => 'Guardian Occupation',
                  'guardian_relationship' => 'Guardian Relationship',
                  'guardian_monthly_income' => 'Guardian Monthly Income',
                  'guardian_annual_income' => 'Guardian Annual Income',
                  'elementary_school' => 'Elementary School',
                  'elementary_year' => 'Elementary Year',
                  'elementary_honors' => 'Elementary Honors',
                  'secondary_school' => 'Secondary School',
                  'secondary_year' => 'Secondary Year',
                  'secondary_honors' => 'Secondary Honors',
                  'senior_high_school' => 'Senior High School',
                  'senior_high_year' => 'Senior High Year',
                  'senior_high_honors' => 'Senior High Honors',
                  'course_enrolled' => 'Course Enrolled',
                  'no_of_units' => 'Number of Units',
                  'year_level' => 'Year Level',
                  'current_semester' => 'Current Semester',
                  'graduating' => 'Graduating',
                  'school_name' => 'School Name',
                  'school_type' => 'School Type',
                  'expected_year_of_graduation' => 'Expected Year of Graduation',
                  'school_address' => 'School Address',
                  'student_id_no' => 'Student ID Number',
                  
                  // Add more field names and labels as needed
                  ];
                  
                  // Query the update history for this applicant
                  $queryUpdateHistory = "SELECT FieldToUpdate, OldValue, NewValue, UpdatedOn FROM ceap_applicantupdates WHERE ceap_personal_account_id = ?";
                  $stmtUpdateHistory = mysqli_prepare($conn, $queryUpdateHistory);
                  mysqli_stmt_bind_param($stmtUpdateHistory, "i", $id);
                  mysqli_stmt_execute($stmtUpdateHistory);
                  $resultUpdateHistory = mysqli_stmt_get_result($stmtUpdateHistory);
                  
                  // Display update history in the table
                  while ($rowUpdateHistory = mysqli_fetch_assoc($resultUpdateHistory)) {
                  $fieldToUpdate = $rowUpdateHistory['FieldToUpdate'];
                  $oldValue = $rowUpdateHistory['OldValue'];
                  $newValue = $rowUpdateHistory['NewValue'];
                  $label = isset($fieldLabels[$fieldToUpdate]) ? $fieldLabels[$fieldToUpdate] : $fieldToUpdate; // Get the label or use field name if not found
                  
                  echo '<tr>';
                  echo '<td>' . $label . '</td>';
                  echo '<td>' . $oldValue . '</td>';
                  echo '<td>' . $newValue . '</td>';
                  echo '<td>' . $rowUpdateHistory['UpdatedOn'] . '</td>';
                  echo '</tr>';
                  }
                  
                  mysqli_stmt_close($stmtUpdateHistory);
                  
                    ?>
            </table>
         </div>
         <br>
         <!-- Table 1: Personal Info -->
         <div class="applicant-info">
            <h2 style="margin-top: -55px;">Personal Information</h2>
            <table>
               <?php foreach ($applicantInfo as $field => $value) : ?>
               <?php if (in_array($field, [
                  'control_number', 'last_name', 'first_name', 'middle_name', 'suffix_name',
                  'date_of_birth', 'age', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number',
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
                  'guardian_firstname', 'guardian_lastname', 'guardian_occupation', 'guardian_relationship',
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
                  'elementary_school', 'elementary_year', 'elementary_honors',
                  'secondary_school', 'secondary_year', 'secondary_honors',
                  'senior_high_school', 'senior_high_year', 'senior_high_honors',
                  'course_enrolled', 'no_of_units', 'year_level', 'current_semester',
                  'graduating', 'school_name', 'school_type', 'expected_year_of_graduation',
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
                           echo "<h2 class='to_center'>Scanned Documents</h2>";
                           // Voters applicant
                           
                           echo '<table class="table" style="width: 80%;">';
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
                           
                           echo "</tbody>";
                           echo "</table>";
                           
                           // GRADE
                           echo "<td>";
                           echo "<label>Applicant 2x2 Picture</label>";
                           echo "<div class='image'>";
                           echo "<img src='../../../../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg' onclick='expandImage(this)' class='smaller-image'>";
                           echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg'></div>";
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
      <div id="reasonModalOLD" class="modal">
         <div class="modal-content">
            <span class="close" onclick="closeReasonModalOLD()">&times;</span>
            <h2>Enter Reason</h2>
            <input type="text" name="reason" id="disqualificationReasonOLD" minlength="10" maxlength="255" placeholder="Enter reason for disqualification">
            <button id="submitReason" onclick="submitStatusAndReasonOLD()" class="disabled">Submit</button>
         </div>
      </div>
      <div id="reasonModalFail" class="modal">
         <div class="modal-content">
            <span class="close" onclick="closeReasonModalFailOLD()">&times;</span>
            <h2>Enter Reason</h2>
            <input type="text" name="reasonFail" id="FailReason" minlength="10" maxlength="255" placeholder="Enter reason for failing">
            <button id="submitReasonFail" onclick="submitStatusAndReasonFailOLD()" class="disabled">Submit</button>
         </div>
      </div>
      <footer class="footer">
         <?php
            // Fetch the applicant's status from the database
            $query = "SELECT status FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $applicantStatus = $row['status'];
            } else {
                $applicantStatus = ''; // Set a default value if status is not found
            }
            
            // Check the status and determine which buttons to display
            if ($applicantStatus === 'In Progress') {
                echo '<button onclick="openReasonModalOLD(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Disqualified</button>';
                echo '<button onclick="openVerifiedPopupOLD()" style="background-color: #FEC021;" class="status-button">Verified</button>';
            } elseif ($applicantStatus === 'Verified') {
                echo '<button onclick="openReasonModalOLD(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Disqualified</button>';
            } elseif ($applicantStatus === 'Disqualified') {
                echo '<button onclick="openVerifiedPopupOLD()" style="background-color: #FEC021; margin-right: 100px;" class="status-button">Verified</button>';
            } elseif ($applicantStatus === 'interview') {
                echo '<button onclick="openReasonModalFailOLD(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Not Grantee</button>';
                echo '<button onclick="openGranteePopupOLD(\'Grantee\', ' . $id . ')" style="background-color: #FEC021;" class="status-button">Grantee</button>';
            }
            ?>
      </footer>
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/status_popupOLD.js"></script>
      <script  src="../../../js/updateStatusDisqualifiedOLD.js"></script>
      <script  src="../../../js/updateStatusFailOLD.js"></script>
      <script  src="../../../js/updateStatusVerifiedOLD.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script type="text/javascript">
         var ceapRegFormIdOLD = <?php echo $ceapRegFormIdOLD; ?>;
      </script>
   </body>
</html>