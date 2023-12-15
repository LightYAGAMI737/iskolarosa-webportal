<?php
session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

$currentPage = 'ceap_list';
$currentSubPage = 'CEAP';

include './php/config_iskolarosa_db.php';

// Get the ceap_reg_form_id parameter from the URL
if (isset($_GET['ceap_reg_form_id'])) {
    $ceapRegFormId = $_GET['ceap_reg_form_id'];
} else {
    echo 'No applicant selected.';
    exit();
}

$id = $_GET['ceap_reg_form_id'];
$query = "SELECT * FROM ceap_reg_form WHERE ceap_reg_form_id = ?";
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
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
      <style>
        .invalid {
            border-color: red !important;
        }
    .button-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .status-button {
        background-color: #A5040A;
        padding: 5px 15px;
        border: none;
        color: white;
        cursor: pointer;
    }

    /* Apply styles to the entire form */

.delete {
    float: right;
}
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
fieldset:disabled input, select{
    border: none !important;
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
    <form id="update-form" method="post" action="./php/update_personal_info.php">
        <fieldset id="personal-info-fields" disabled>    
            <table>
            <tr>
                <th>Status:</th>
                <td> <?php echo $applicantStatus; ?> </td>
            </tr>
                <?php foreach ($applicantInfo as $field => $value) : ?>
                    <?php if (in_array($field, ['control_number', 'last_name', 'first_name', 'middle_name', 'suffix_name', 'date_of_birth', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number', 'active_email_address', 'house_number', 'province', 'municipality', 'barangay'])) : ?>
                      
                        <tr>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                            <td>
                                <?php switch ($field) {
                                    case 'last_name':
                                    case 'first_name':
                                    case 'middle_name':
                                    case 'religion':
                                    case 'contact_number':
                                    case 'place_of_birth':
                                    case 'house_number':
                                          // Editable text fields
                                          echo '<input type="text" name="' . $field . '" id="' . $field . '"  value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                          break;
                                    case 'suffix_name':
                                          // Editable text fields
                                          echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                          echo '<div id="suffix_options" class="suffix-options"></div>';
                                          break;
                                    case 'date_of_birth':
                                          // Editable text fields
                                          echo '<input type="date" name="' . $field . '" id="' . $field . '"  value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                          break;
                                    case 'active_email_address':
                                         // Editable text fields
                                          echo '<input type="email" name="' . $field . '" id="' . $field . '"  value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                          break;
                                    case 'barangay':
                                        // Select options for Barangay
                                        echo '<select name="barangay">';
                                        $barangayOptions = ['APLAYA', 'BALIBAGO', 'CAINGIN', 'DILA', 'DITA', 'DON JOSE', 'IBABA', 'KANLURAN', 'LABAS', 'MACABLING', 'MALITLIT', 'MALUSAK', 'MARKET AREA', 'POOC', 'PULONG SANTA CRUZ', 'SANTO DOMINGO', 'SINALHAN', 'TAGAPO'];

                                        foreach ($barangayOptions as $option) {
                                            echo '<option value="' . $option . '" ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                                    case 'gender':
                                        // Select options for Gender
                                        echo '<select name="gender">';
                                        $genderOptions = ['Male', 'Female'];

                                        foreach ($genderOptions as $option) {
                                            echo '<option value="' . $option . '" ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                                    case 'civil_status':
                                        // Select options for Civil Status
                                        echo '<select name="civil_status">';
                                        $civilStatusOptions = ['Single', 'Married'];

                                        foreach ($civilStatusOptions as $option) {
                                            echo '<option value="' . $option . '" ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                                            
                                    case 'control_number':
                                    case 'municipality':
                                    case 'province':
                                        // Make certain fields non-editable by adding the "disabled" attribute
                                        echo '<input type="text" name="' . $field . '" value="' . $value . '" disabled>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;

                                    default:
                                        // Editable text fields for any other case
                                        echo '<input type="text" name="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                                } ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </fieldset>
        </div>


<!-- Table 3: Educational Background -->
<div class="applicant-info">
    <h2>Educational Background</h2>
    <fieldset id="educational-background-fields" disabled>
        <table>
            <?php foreach ($applicantInfo as $field => $value) : ?>
                <?php if (in_array($field, ['elementary_school', 'elementary_year', 'elementary_honors', 'secondary_school', 'secondary_year', 'secondary_honors', 'senior_high_school', 'senior_high_year', 'senior_high_honors', 'course_enrolled', 'no_of_units', 'year_level', 'current_semester', 'graduating', 'school_name', 'school_type', 'expected_year_of_graduation', 'school_address', 'student_id_no'])) : ?>
                    <tr>
                        <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                        <td>
                        <?php switch ($field) {
                            case 'elementary_school':
                            case 'secondary_school':
                            case 'senior_high_school':
                            case 'course_enrolled':
                            case 'school_name':
                            case 'school_type':
                            case 'school_address':
                                // Editable text fields
                                echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;

                            case 'elementary_year':
                                // Select element for elementary_year
                                echo '<select  name="' . $field . '" id="' . $field . '" value="' . $value . '"';
                                $currentYear = date("Y");
                                for ($year = 2017; $year >= 2000; $year--) {
                                    echo '<option value="' . $year . '"' . ($value == $year ? 'selected' : '') . '>' . $year . '</option>';
                                }
                                echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;

                                case 'secondary_year':
                                case 'senior_high_year':
                                case 'expected_year_of_graduation':
                                        // Select element for secondary_year, senior_high_year, expected_year_of_graduation
                                        echo '<select name="' . $field . '" id="' . $field . '" class="year-graduated" required>';
                                        echo '<option value="' . $value . '"></option>';
                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;

                            case 'year_level':
                                        // Select options for Civil Status
                                        echo '<select name="year_level" id="year_level">';
                                        $year_level = ['One', 'Two', 'Three', 'Four', 'Five', 'Six'];

                                        foreach ($year_level as $option) {
                                            echo '<option value="' . $option . '" ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                            case 'current_semester':
                                        // Select options for Civil Status
                                        echo '<select name="current_semester" id="current_semester">';
                                        $current_semester = ['One', 'Two', 'Three'];

                                        foreach ($current_semester as $option) {
                                            echo '<option value="' . $option . '" ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                            case 'graduating':
                                        // Select options for Civil Status
                                        echo '<select name="graudating" id="graduating">';
                                        $graudating = ['No', 'Yes'];

                                        foreach ($graudating as $option) {
                                            echo '<option value="' . $option . '"  ' . ($value === $option ? 'selected' : '') . '>' . $option . '</option>';
                                        }

                                        echo '</select>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                        break;
                            case 'student_id_no':
                                // Editable text fields with specific validation if needed
                                echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;

                            case 'no_of_units':
                                echo '<input type="number" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;

                            case 'elementary_honors':
                            case 'secondary_honors':
                            case 'senior_high_honors':
                                // Editable text fields with specific validation if needed
                                echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;

                            default:
                                // Editable text fields for any other case
                                echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                break;
                        } ?>

                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </fieldset>
</div>
  
    

<!-- Table 2: Family Background -->
<div class="applicant-info">
    <h2>Family Background</h2>
    <fieldset id="family-background-fields" disabled>
        <table>
            <?php foreach ($applicantInfo as $field => $value) : ?>
                <?php if (in_array($field, ['guardian_firstname', 'guardian_lastname', 'guardian_occupation', 'guardian_relationship', 'guardian_monthly_income', 'guardian_annual_income'])) : ?>
                    <tr>
                        <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                        <td>
                            <?php switch ($field) {
                                case 'guardian_firstname':
                                case 'guardian_lastname':
                                case 'guardian_occupation':
                                case 'guardian_relationship':
                                    // Editable text fields
                                    echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                    break;
              
                                case 'guardian_monthly_income':
                                    // Editable text fields
                                    echo '<input type="text" name="' . $field . '" id="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                    break; 

                                case 'guardian_annual_income':
                                    // Make certain fields non-editable by adding the "disabled" attribute
                                    echo '<input type="text" name="' . $field . '" id="' . $field . '"  value="' . $value . '" disabled>';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                    break;
                                default:
                                    // Editable text fields for any other case
                                    echo '<input type="text" name="' . $field . '" value="' . $value . '">';
                                          echo '<span class="' . $field . '_error" id="' . $field . '_error"></span>';
                                    break;
                            } ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </fieldset>
</div>

<!-- table for displaying the uploaded files as images -->
<div class="uploaded-files">
    <table>
        <tr>
            <th>Uploaded Files:</th>
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
    'uploadVotersApplicant' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersApplicant.pdf',
    'uploadVotersParent' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_VotersParent.pdf',
    'uploadITR' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_ITR.pdf',
    'uploadResidency' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Residency.pdf',
    'uploadCOR' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_COR.pdf',
    'uploadGrade' => '../ceap-reg-form/pdfFiles/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_Grade.pdf'
);

// Output image file paths
$imageFiles = array();
foreach ($pdfFiles as $key => $pdfFile) {
    $outputImage = '../ceap-reg-form/converted-images/' . $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $key . '.jpg';

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
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersApplicant.jpg'></div>";
  echo "</div>";
  echo "</td>";
  
  // Voters Cert Parent
  echo "<td>";
  echo "<label>Voters Certificate Parent</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadVotersParent.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  // TAX
  echo "<tr>";
  echo "<td>";
  echo "<label>Income Tax Return</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadITR.jpg'></div>";
  echo "</div>";
  echo "</td>";
  // Residency
  echo "<td>";
  echo "<label>Residency</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadResidency.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  // COR
  echo "<tr>";
  echo "<td>";
  echo "<label>Certificate of Registration</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadCOR.jpg'></div>";
  echo "</div>";
  echo "</td>";
  
  // GRADE
  echo "<td>";
  echo "<label>GWA for Current Sem</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/converted-images/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_uploadGrade.jpg'></div>";
  echo "</div>";
  echo "</td>";
  echo "</tr>";
  
  echo "</tbody>";
  echo "</table>";
  
  // GRADE
  echo "<td>";
  echo "<label>Applicant 2x2 Picture</label>";
  echo "<div class='image'>";
  echo "<img src='../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg' onclick='expandImage(this)' class='smaller-image'>";
  echo "<div class='expanded-image' onclick='collapseImage(this)'><img src='../ceap-reg-form/applicant2x2/" . $applicantInfo['last_name'] . "_" . $applicantInfo['first_name'] . "_2x2_Picture.jpg'></div>";
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

<input type="hidden" name="ceap_reg_form_id" value="<?php echo $ceapRegFormId; ?>">
    <!-- <button id="edit-button" class="status-button" type="button">Edit</button>
    <button type="submit" name="update_all_info" id="saveChanges" class="status-button" disabled>Save Changes</button>
<button onclick="deleteApplicant(<?php echo $id; ?>)" class="status-button delete">Delete</button> -->
</form>

</div>
</div>

<!-- end applicant info -->

        
         <footer class="footer">
<!--        

<div class="button-container">
    <button onclick="updateStatus('Disqualified', <?php echo $id; ?>)" class="status-button" <?php echo ($applicantStatus == 'Disqualified') ? 'disabled' : ''; ?>>Disqualified</button>
    <button onclick="updateStatus('Verified', <?php echo $id; ?>)" class="status-button" <?php echo ($applicantStatus == 'verified') ? 'disabled' : ''; ?>>Verified</button>
    <button onclick="updateStatus('Fail', <?php echo $id; ?>)" class="status-button" <?php echo ($applicantStatus == 'Fail') ? 'disabled' : ''; ?>>Not Grantee</button>
    <button onclick="updateStatus('Grantee', <?php echo $id; ?>)" class="status-button" <?php echo ($applicantStatus == 'Grantee') ? 'disabled' : ''; ?>>Grantee</button>
</div> -->


         </footer>
         </main>
         <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script>
      <script  src="./js/side_bar.js"></script>
      <script  src="./js/validateCeapInfo.js"></script>

      



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

    function updateStatus(status, applicantId) {
// Send an AJAX request to update the applicant status
var xhr = new XMLHttpRequest();
xhr.open("POST", "./php/updateStatus.php", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xhr.onreadystatechange = function () {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    // Handle the response here
    var response = xhr.responseText.trim(); // Trim whitespace from the response text
    if (response === 'success') {
      alert('Status updated successfully.');
      // Go back to the previous page
      goBack();
    } else {
      alert('Failed to update status.');
    }
  }
};
xhr.send("status=" + status + "&id=" + applicantId);
}
</script>
<script>
    const editButton = document.getElementById('edit-button');
    const personalInfoFields = document.getElementById('personal-info-fields');
    const familyBackgroundFields = document.getElementById('family-background-fields');
    const educationalBackgroundFields = document.getElementById('educational-background-fields');

    editButton.addEventListener('click', () => {
        personalInfoFields.disabled = !personalInfoFields.disabled;
        familyBackgroundFields.disabled = !familyBackgroundFields.disabled;
        educationalBackgroundFields.disabled = !educationalBackgroundFields.disabled;

        editButton.textContent = personalInfoFields.disabled ? 'Edit' : 'Cancel';
    });
</script>

<script>
function deleteApplicant(applicantId) {
    if (confirm("Are you sure you want to delete this applicant?")) {
        // Send an AJAX request to update is_deleted
        $.ajax({
            type: "POST",
            url: "./php/delete_applicant.php", // Replace with the actual URL to your PHP script
            data: {
                id: applicantId
            },
            success: function(response) {
                if (response === 'success') {
                    // Refresh the page or perform any other actions
                    goBack(); // You can use location.reload() to refresh the page
                } else {
                    alert("Failed to delete the applicant.");
                }
            }
        });
    }
}
</script>

   </body>
</html>