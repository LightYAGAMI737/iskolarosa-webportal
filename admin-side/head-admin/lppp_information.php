<?php
session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

$currentPage = 'lppp_list';
$currentSubPage = '';

include '../php/config_iskolarosa_db.php';

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
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/ceap_information.css">
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
</style>
   </head>
   <body>
      <?php 
         include 'head_admin_side_bar.php';
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

<!-- Table 3: Educational Background -->
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
            <th>Uploaded Files:</th>
            <td>
                <div class="file-group">
                    <?php
                    // Loop through uploaded files and display them in groups of three
                    $fileCounter = 0;
                    foreach ($applicantInfo as $field => $value) {
                        if (strpos($field, 'upload') !== false && (pathinfo($value, PATHINFO_EXTENSION) === 'pdf')) {
                            // Extract the base file name without extension
                            $baseFileName = $applicantInfo['last_name'] . '_' . $applicantInfo['first_name'] . '_' . $field;
                            $pdfFilePath = '../../ceap_reg_form/pdfFiles/' . $baseFileName . '.pdf';
                            $imageFilePath = '../../ceap_reg_form/images/' . $baseFileName . '.jpg';
                            $thumbnailImagePath = $imageFilePath; // Use the same image for the thumbnail
                            
                            // Command to convert PDF to image using Ghostscript (replace with your Ghostscript path)
                            $ghostscriptPath = 'C:/Program Files/gs/gs9.54.0/bin/gswin64c.exe';
                            $command = '"' . $ghostscriptPath . '" -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -r300 -sOutputFile="' . $imageFilePath . '" "' . $pdfFilePath . '"';
                            exec($command);
                            
                            // Display the image and expanded image
                            echo '<div class="file-image">';
                            echo '<img src="' . $thumbnailImagePath . '" alt="Thumbnail" class="file-image" onclick="expandImage(this)">';
                            echo '<div class="expanded-image" onclick="collapseImage(this)"><img src="' . $imageFilePath . '" alt="Enlarged Image"></div>';
                            echo '</div>';
                            
                            // Close the file-group container and open a new one after displaying three files
                            $fileCounter++;
                            if ($fileCounter % 3 === 0) {
                                echo '</div>'; // Close the current file-group container
                                echo '<div class="file-group">'; // Open a new file-group container
                            }
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
</div>
</div>

<!-- end applicant info -->

        
         <footer class="footer">
       
         <?php
// Fetch the applicant's status from the database
$query = "SELECT status FROM lppp_temporary_account WHERE lppp_reg_form_id = ?";
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
<div class="button-container">
    <button onclick="updateStatus('Disqualified', <?php echo $id; ?>)" class="status-button">Disqualified</button>
    <button onclick="updateStatus('Verified', <?php echo $id; ?>)" class="status-button verified">Verified</button>
    <button onclick="updateStatus('Fail', <?php echo $id; ?>)" class="status-button">Not Grantee</button>
    <button onclick="updateStatus('Grantee', <?php echo $id; ?>)" class="status-button verified">Grantee</button>
</div>


         </footer>
         </main>
         <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>

      
<script>
 
 function seeMore(id) {
     // Redirect to a page where you can retrieve the reserved data based on the given ID
     window.location.href = "ceap_all_disqualified_information.php?lppp_reg_form_id=" + id;
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

    function updateStatus(status, applicantId) {
// Send an AJAX request to update the applicant status
var xhr = new XMLHttpRequest();
xhr.open("POST", "../php/updateStatus.php", true);
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
   </body>
</html>