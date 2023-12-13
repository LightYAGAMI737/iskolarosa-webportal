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
         <!-- Table 1: Personal Info -->
         <div class="applicant-info">
            <h2 style="margin-top: -55px;">Personal Information</h2>
            <table>
               <?php foreach ($applicantInfo as $field => $value) : ?>
               <?php if (in_array($field, [
                  'control_number', 'last_name', 'first_name', 'middle_name', 'suffix_name',
                  'date_of_birth', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number',
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
            <table>
               <tr>
                  <td>
                     <div class="file-group">
                        <?php
                           // Loop through uploaded files and display them in groups of three
                           $fileCounter = 0;
                           
                           // Path to Ghostscript executable
                           $ghostscriptPath = 'C:\Program Files\gs10.01.2\bin\gswin64c.exe';  // Replace with your Ghostscript path
                           
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
                           
                           // Convert PDF files to images
                           foreach ($pdfFiles as $key => $pdfFile) {
                           $outputImage = '../../../../ceap-reg-form/converted-images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg'; // Replace with the desired output image path and extension
                           $imageFiles[$key] = $outputImage;
                           
                           // Command to convert PDF to image using Ghostscript
                           $command = '"' . $ghostscriptPath . '" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -r300 -sOutputFile="' . $outputImage . '" "' . $pdfFile . '"';
                           
                           // Execute the Ghostscript command
                           exec($command);
                           
                           
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
      <div id="reasonModal" class="modal">
         <div class="modal-content">
            <span class="close" onclick="closeReasonModal()">&times;</span>
            <h2>Enter Reason</h2>
            <input type="text" name="reason" id="disqualificationReason" minlength="10" maxlength="255" placeholder="Enter reason for disqualification">
            <button id="submitReason" onclick="submitStatusAndReason()" class="disabled">Submit</button>
         </div>
      </div>
      <footer class="footer">
         <?php
            // Fetch the applicant's status from the database
            $query = "SELECT status FROM temporary_account WHERE ceap_reg_form_id = ?";
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
                echo '<button onclick="openReasonModal(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">DISQUALIFIED</button>';
                echo '<button onclick="openVerifiedPopup()" style="background-color: #FEC021;" class="status-button">VERIFIED</button>';
            } elseif ($applicantStatus === 'Verified') {
                echo '<button onclick="openReasonModal(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">DISQUALIFIED</button>';
            } elseif ($applicantStatus === 'Disqualified') {
                echo '<button onclick="openVerifiedPopup()" style="background-color: #FEC021; margin-right: 100px;" class="status-button">VERIFIED</button>';
            } elseif ($applicantStatus === 'interview') {
                echo '<button onclick="openReasonModal(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">NOT GRANTEE</button>';
                echo '<button onclick="openGranteePopup()" style="background-color: #FEC021;" class="status-button">GRANTEE</button>';
            }
            ?>
      </footer>
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='../../../js/unpkg-layout.js'></script>
      <script  src="../../../js/side_bar.js"></script>
      <script  src="../../../js/status_popup.js"></script>
      <script  src="../../../js/updateStatusDisqualified.js"></script>
      <script  src="../../../js/updateStatusVerified.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script type="text/javascript">
         var ceapRegFormId = <?php echo $ceapRegFormId; ?>;
      </script>

   </body>
</html>