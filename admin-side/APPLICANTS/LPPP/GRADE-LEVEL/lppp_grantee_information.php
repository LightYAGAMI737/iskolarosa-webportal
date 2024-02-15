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
    $ceapRegFormId = $_GET['lppp_reg_form_id'];
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
    <div class="update-button-container">
        <form method="post" action="update_grade_level.php"> <!-- Add a form -->
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
                'guardian_name', 'guardian_occupation', 'guardian_relationship',
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

<div class="applicant-info">
    <h2>Educational Background</h2>
    <table>
            <?php foreach ($applicantInfo as $field => $value) : ?>
                <?php if (in_array($field, [
                    'elementary_school', 'elementary_year',
                    'school_address', 'grade_level'
                ])) : ?>
                    <tr>
                        <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                        <td>
                            <?php if ($field === 'grade_level') : ?>
                                <select name="grade_level" id="GradeLVL" style="width: 100px; padding: 5px; border: 1px solid #ccc;"
                                onchange="validateInput(this);">
                            <?php
                            $currentGradeLevel = $value; // Set the current grade level from the database
                            $minAllowed = $currentGradeLevel; // Minimum allowed grade level
                            $maxAllowed = 12; // Maximum allowed grade level

                            // Generate the dropdown options
                            for ($i = $minAllowed; $i <= $maxAllowed; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>

                            <?php else : ?>
                                <?php echo $value; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </form>
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
  <div style="display: flex; justify-content: center;">
            <button type="submit" id="UpdateGradeLVL" class = "update-btn" name="update_info" disabled>Update</button>
            <input type="hidden" name="id" value="<?php echo $ceapRegFormId; ?>">
  </div>
        </form>
    </div>
</div>
</div>

<!-- end applicant info -->

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
$query = "SELECT status, interview_date FROM lppp_temporary_account WHERE lppp_reg_form_id = ?";
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
            echo '<button onclick="updateStatus(\'Disqualified\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Disqualified</button>';
            echo '<button onclick="updateStatus(\'Verified\', ' . $id . ')" style="background-color: #FEC021;" class="status-button">Verified</button>';
        } elseif ($applicantStatus === 'exam') {
            echo '<form id="scoreForm">';
            echo '<label for="applicantScore">Enter Applicant Score:</label>';
            echo '<input type="number" id="applicantScore" name="applicantScore" min="0" max="100" required>';
            echo '<input type="hidden" id="applicantId" value="' . $id . '">';
            echo '<input type="submit" value="Submit">';
            echo '</form>';
        } elseif ($applicantStatus === 'interview' && $applicantInterviewDate === '0000-00-00') {
            // ... your code for interview status with '0000-00-00' date ...
        } elseif ($applicantStatus === 'interview') {
            echo '<button onclick="updateStatus(\'Fail\', ' . $id . ')" style="background-color: #A5040A; margin-right: 100px;" class="status-button">Not Grantee</button>';
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
      <script src='../../../js/unpkg-layout.js'></script><script  src="../../../js/side_bar.js"></script>


      
<script>
 
 function seeMore(id) {
     // Redirect to a page where you can retrieve the reserved data based on the given ID
     window.location.href = "lppp_information.php?lppp_reg_form_id=" + id;
 }

const gradeLVLSelect = document.getElementById("GradeLVL");
const updateGradeLVLButton = document.getElementById("UpdateGradeLVL");

let initialGradeLevel = gradeLVLSelect.value; // Store the initial grade level

gradeLVLSelect.addEventListener("change", () => {
  const currentGradeLevel = gradeLVLSelect.value;
  updateGradeLVLButton.disabled = currentGradeLevel === initialGradeLevel;
});
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

function expandImage(img) {
   var imageUrl = img.src;
   window.open(imageUrl, "_blank"); // Open the image in a new tab/window
}

function collapseImage(element) {
    element.style.display = 'none';
}


     // Function to go back to the previous page
   
function goBack() {
      window.history.back();
  }

</script>


   </body>
</html>