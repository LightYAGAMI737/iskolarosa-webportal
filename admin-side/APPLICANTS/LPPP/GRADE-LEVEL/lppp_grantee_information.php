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
    <?php if ($field === 'age' || $field === 'contact_number') : ?>
        <input type="text" name="<?php echo $field; ?>" value="<?php echo $value; ?>" <?php echo $field === 'age' ? 'pattern="[0-9]+" title="Please enter a valid numerical value."' : ''; ?> <?php echo $field === 'age' ? 'min="11" max="18"' : ''; ?>>
    <?php elseif ($field === 'barangay') : ?>
        <select name="<?php echo $field; ?>">
            <option value="APLAYA" <?php if ($value === 'APLAYA') echo 'selected'; ?>>APLAYA</option>
            <option value="BALIBAGO" <?php if ($value === 'BALIBAGO') echo 'selected'; ?>>BALIBAGO</option>
            <option value="CAINGIN" <?php if ($value === 'CAINGIN') echo 'selected'; ?>>CAINGIN</option>
            <option value="DILA" <?php if ($value === 'DILA') echo 'selected'; ?>>DILA</option>
            <option value="DITA" <?php if ($value === 'DITA') echo 'selected'; ?>>DITA</option>
            <option value="DON JOSE" <?php if ($value === 'DON JOSE') echo 'selected'; ?>>DON JOSE</option>
            <option value="IBABA" <?php if ($value === 'IBABA') echo 'selected'; ?>>IBABA</option>
            <option value="KANLURAN" <?php if ($value === 'KANLURAN') echo 'selected'; ?>>KANLURAN</option>
            <option value="LABAS" <?php if ($value === 'LABAS') echo 'selected'; ?>>LABAS</option>
            <option value="MACABLING" <?php if ($value === 'MACABLING') echo 'selected'; ?>>MACABLING</option>
            <option value="MALITLIT" <?php if ($value === 'MALITLIT') echo 'selected'; ?>>MALITLIT</option>
            <option value="MALUSAK" <?php if ($value === 'MALUSAK') echo 'selected'; ?>>MALUSAK</option>
            <option value="MARKET AREA" <?php if ($value === 'MARKET AREA') echo 'selected'; ?>>MARKET AREA</option>
            <option value="POOC" <?php if ($value === 'POOC') echo 'selected'; ?>>POOC</option>
            <option value="PULONG SANTA CRUZ" <?php if ($value === 'PULONG SANTA CRUZ') echo 'selected'; ?>>PULONG SANTA CRUZ</option>
            <option value="SANTO DOMINGO" <?php if ($value === 'SANTO DOMINGO') echo 'selected'; ?>>SANTO DOMINGO</option>
            <option value="SINALHAN" <?php if ($value === 'SINALHAN') echo 'selected'; ?>>SINALHAN</option>
            <option value="TAGAPO" <?php if ($value === 'TAGAPO') echo 'selected'; ?>>TAGAPO</option>

            <!-- Add more options as needed -->
        </select>
    <?php else : ?>
        <?php echo $value; ?>
    <?php endif; ?>
</td>
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
                                <select name="grade_level" style="width: 100px; padding: 5px; border: 1px solid #ccc;"
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
    'uploadCOR' => '../../../../lppp-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_COR.pdf',
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
  
            <button type="submit" name="update_info">Update</button>
            <input type="hidden" name="id" value="<?php echo $ceapRegFormId; ?>">
        </form>
    </div>
</div>
</div>

<!-- end applicant info -->

        
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


   </body>
</html>