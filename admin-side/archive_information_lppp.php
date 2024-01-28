<?php
session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

$currentPage = 'archive';
$currentSubPage = '';

include './php/config_iskolarosa_db.php';

// Get the lppp_reg_form_id parameter from the URL
if (isset($_GET['lppp_reg_form_id'])) {
    $LPPPRegFormId = $_GET['lppp_reg_form_id'];
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
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
      <link rel="stylesheet" href="./css/remixicon.css">
      <link rel='stylesheet' href='./css/unpkg-layout.css'>
      <link rel="stylesheet" href="./css/side_bar.css">
      <link rel="stylesheet" href="./css/ceap_information.css">
      <style>
    .button-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .status-button {
        background-color: #A5040A;
        padding: 5px 15px;
        border: none;
        color: white;
        cursor: pointer;
    }

    .status-button.verified {
        background-color: #A5040A;
    }
    /* Apply styles to the entire form */


/* Style the table */
.applicant-info table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

/* Style table headers */
.applicant-info th {
    text-align: left;
    padding: 8px;
    font-weight: bold;
}

/* Style table data cells */
.applicant-info td {
    padding: 8px;
}

/* Style the input fields */
.applicant-info input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

/* Add some spacing between sections */
.applicant-info {
    margin-bottom: 40px;
}

/* Style the section headings */
.applicant-info h2 {
    color: Black;
    padding: 10px;
    border-radius: 5px 5px 0 0;
    margin: 0;
}

/* Add a little extra spacing between form sections */
.applicant-info + .applicant-info {
    margin-top: 20px;
}
fieldset {
    border: none;
}

</style>
   </head>
   <body>
      <?php 
         include './php/head_admin_side_bar.php';
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
                    // Loop through uploaded files and display them in groups of three
                    $fileCounter = 0;
                 
// Path to Ghostscript executable
$ghostscriptPath = 'C:\Program Files\gs10.01.2\bin\gswin64c.exe';  // Replace with your Ghostscript path

$pdfFiles = array(
    'uploadVotersApplicant' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersApplicant.pdf',
    'uploadVotersParent' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersParent.pdf',
    'uploadITR' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_ITR.pdf',
    'uploadResidency' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Residency.pdf',
    'uploadCOR' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_COR.pdf',
    'uploadGrade' => '../../../lppp_reg_form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Grade.pdf'
);

// Output image file paths
$imageFiles = array();

// Convert PDF files to images
foreach ($pdfFiles as $key => $pdfFile) {
  $outputImage = 'C:/xampp/htdocs/iskolarosa-4.0/lppp_reg_form/images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg'; // Replace with the desired output image path and extension
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
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg'></div>";
  echo "</div>";
  echo "</td>";
  
  // Voters Cert Parent
  echo "<td>";
  echo "<label>Voters Certificate Parent</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  // TAX
  echo "<tr>";
  echo "<td>";
  echo "<label>Income Tax Return</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg'></div>";
  echo "</div>";
  echo "</td>";
  // Residency
  echo "<td>";
  echo "<label>Residency</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  // COR
  echo "<tr>";
  echo "<td>";
  echo "<label>Certificate of Registration</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg'></div>";
  echo "</div>";
  echo "</td>";
  
  // GRADE
  echo "<td>";
  echo "<label>GWA for Current Sem</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  echo "</tbody>";
  echo "</table>";
  
  // GRADE
  echo "<td>";
  echo "<label>Applicant 2x2 Picture</label>";
  echo "<div class='image'>";
  echo "<img src='../../../lppp_reg_form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../../../lppp_reg_form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg'></div>";
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

    
         </main>
         <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>
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

   </body>
</html>