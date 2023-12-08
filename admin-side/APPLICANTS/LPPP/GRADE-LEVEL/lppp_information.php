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
                'date_of_birth', 'age', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number',
                'active_email_address', 'house_number', 'province', 'municipality', 'barangay'
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
                           // Loop through uploaded files and display them in groups of three
                           $fileCounter = 0;
                           
                           // Path to Ghostscript executable
                           $ghostscriptPath = 'C:\Program Files\gs10.01.2\bin\gswin64c.exe';  // Replace with your Ghostscript path
                           
                           $pdfFiles = array(
                           'uploadVotersParent' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersParent.pdf',
                           'uploadITR' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_ITR.pdf',
                           'uploadResidency' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Residency.pdf',
                           'uploadCOR' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_COR.pdf',
                           'uploadGrade' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Grade.pdf'
                           );
                           
                           // Output image file paths
                           $imageFiles = array();
                           
                           // Convert PDF files to images
                           foreach ($pdfFiles as $key => $pdfFile) {
                           $outputImage = '../../../../lppp-reg-form/converted-images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg'; // Replace with the desired output image path and extension
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
                <input type="text" name="reason" id="disqualificationReasonLPPP" minlength="10" maxlength="255" placeholder="Enter reason for disqualification">
                <button id="submitReasonLPPP" onclick="submitStatusAndReasonLPPP()" class="disabled">Submit</button>
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
            echo '<input type="number" class="ScoreInput" id="applicantScore" name="applicantScore" min="0" max="100" required>';
            echo '<input type="hidden" id="applicantId" value="' . $id . '">';
            echo '<button type="button" id="ScoreFormBTN" class="ScoreBTN" value="Submit">Submit</button>';
            echo '</form>';
        } elseif ($applicantStatus === 'interview' && $applicantInterviewDate === '0000-00-00') {
        } elseif ($applicantStatus === 'interview') {
            echo '<button onclick="openReasonModalLPPP(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Not Grantee</button>';
            echo '<button onclick="updateStatus(\'Grantee\', ' . $id . ')" style="background-color: #FEC021;" class="status-button">Grantee</button>';
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
      <script type="text/javascript">
         var LPPPregFormID = <?php echo $LPPPregFormID; ?>;
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

function expandImage(element) {
    var expandedImage = element.nextElementSibling;
    expandedImage.style.display = 'flex';
}

function collapseImage(element) {
    element.style.display = 'none';
}


     // Function to go back to the previous page
   
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
        var applicantId = document.getElementById("applicantId").value;

        // Additional validation for applicantScore
        if (isNaN(applicantScore) || applicantScore < 0 || applicantScore > 100) {
            alert('Please enter a valid score between 0 and 100.');
            return;
        }
        // Determine the new status based on the entered score
        var newStatus = applicantScore >= 75 ? 'interview' : 'Fail';

        // Log the values to the console
        console.log('Applicant Score:', applicantScore);
        console.log('Applicant ID:', applicantId);
        console.log('New Status:', newStatus);

        // Call the updateStatus function to update status via AJAX
        updateStatusLPPPEXAM(newStatus, applicantScore, applicantId);
    });
}

function updateStatusLPPPEXAM(status, applicantScore, applicantId) {
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
    xhr.send("status=" + status + "&applicantScore=" + applicantScore + "&id=" + applicantId);
}

function updateStatusLPPP(status, applicantId) {
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
        xhr.send("status=" + status + "&id=" + applicantId);
    }
</script>


   </body>
</html>