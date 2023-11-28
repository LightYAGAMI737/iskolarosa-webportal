<?php

require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php'; 


include '../admin-side/php/config_iskolarosa_db.php';

function handleEmptyValue($value) {
  return empty($value) ? "N/A" : $value;
}
 // Function to send email using PHPMailer
 function sendEmail($email, $username)
 {
 
     require_once '../admin-side/php/PHPMailerConfigure.php';

       // Email content format
       $mail->IsHTML(true); // Set email format to HTML
     // Set up email content with inline CSS styles
     $emailContent = '<html>
 <head>
 <style>
     body {
         font-family: Arial, sans-serif;
         line-height: 1.6;
     }
     .greeting {
         font-size: 18px;
         font-weight: bold;
         color: #007bff;
         margin-bottom: 10px;
     }
     .login-details {
         font-size: 16px;
         margin-bottom: 15px;
     }
     .disclaimer {
         font-size: 12px;
         color: #999;
     }
 </style>
 </head>
 <body>
     <div class="greeting">Greetings,</div>
     <div>As you begin your journey towards a bright academic future, we hope this email finds you in good spirits. On behalf of the City Scholarship Office, congratulations on successfully registering as an iSKOLAR under the<strong> Libreng Pagpapaaral sa Pribadong Paaralan!</strong></div>
     <br>
     <div class="login-details">
         Please find your Control Number below:<br>
         <strong>Control Number:</strong> ' . $username . '<br>
     </div>
     <div>
         Once the verification process is complete, we will notify you promptly, and you will receive a formal confirmation of your Iskolar status.
     </div>
     <br>
     <div>In the meantime, we encourage you to stay engaged with our updates and announcements. Thank you iSKOLARS!</div>
     <br>
     <div>Warm Regards,<br>
     City Scholarship Office</div>
     <br>
     <div>[iSKOLAROSA LOGO]</div>
     <br>
     <div class="disclaimer">
     <center>
         <strong>DISCLAIMER AND CONFIDENTIALITY NOTICE:</strong><br><br>
        <strong> THIS IS A CITY SCHOLARSHIP OFFICE AND ISKOLAROSA EMAIL ACCOUNT</strong><br><br>
         The information contained in this e-mail, including those in its attachments, is confidential and intended only for the person(s) or entity(ies) to which it is addressed. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you are not an intended recipient, you must not read, copy, store, disclose, distribute this message, or act in reliance upon the information contained in it. If you received this e-mail in error, please contact the sender, delete the material from any computer or system and do not forward it or any part of it to anyone else. Thank you for your cooperation and understanding.
         </center>
     
         </div>
 </body>
 </html>';
 
     $mail->setFrom('iskolarosa@gmail.com', 'City Scholarship Office'); // Replace this with the sender's email and name
     $mail->addAddress($email); // Recipient email from the form
     $mail->Subject = 'New User Registration';
 
     $mail->Body = $emailContent;
 
     // Send the email
     if ($mail->send()) {
         return true; // Email sent successfully
     } else {
         echo "Mailer Error: " . $mail->ErrorInfo;
         return false; // Failed to send email
     }
 }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $suffix_name = $_POST['suffix_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];
    $place_of_birth = $_POST['place_of_birth'];
    $religion = $_POST['religion'];
    $contact_number = $_POST['contact_number'];
    $active_email_address = $_POST['active_email_address'];
    $house_number = $_POST['house_number'];
    $province = $_POST['province'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    
// Set default values for Father's information
$guardian_firstname = htmlspecialchars($_POST['guardian_firstname']);
$guardian_lastname = htmlspecialchars($_POST['guardian_lastname']);
$guardian_occupation = htmlspecialchars($_POST['guardian_occupation']);
$guardian_relationship = htmlspecialchars($_POST['guardian_relationship']);
$guardian_monthly_income = htmlspecialchars($_POST['guardian_monthly_income']);
$guardian_annual_income = htmlspecialchars($_POST['guardian_annual_income']);

    $elementary_school = $_POST['elementary_school'];
    $elementary_year = $_POST['elementary_year'];
    $school_address = $_POST['school_address'];
    $grade_level = 7;

    function sanitizeFileName($filename) {
      // Remove potentially dangerous characters
      $filename = preg_replace("/[^\w\d.-]/", "", $filename);
      return $filename;
  }
  
// Sanitize and validate file names
$uploadVotersParent = sanitizeFileName($_FILES['uploadVotersParent']['name']);
$uploadITR = sanitizeFileName($_FILES['uploadITR']['name']);
$uploadResidency = sanitizeFileName($_FILES['uploadResidency']['name']);
$uploadGrade = sanitizeFileName($_FILES['uploadGrade']['name']);
$uploadPhotoJPG = sanitizeFileName($_FILES['uploadPhotoJPG']['name']);


$uploadVotersParent_tmp = $_FILES['uploadVotersParent']['tmp_name'];
$uploadVotersParent_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_VotersParent.pdf';

$uploadITR_tmp = $_FILES['uploadITR']['tmp_name'];
$uploadITR_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_ITR.pdf';

$uploadResidency_tmp = $_FILES['uploadResidency']['tmp_name'];
$uploadResidency_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Residency.pdf';

$uploadGrade_tmp = $_FILES['uploadGrade']['tmp_name'];
$uploadGrade_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Grade.pdf';

$uploadPhotoJPG_tmp = $_FILES['uploadPhotoJPG']['tmp_name'];
$uploadPhotoJPG_path = 'applicant2x2/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_2x2_Picture.jpg';


       // Function to check if the uploaded file is a PDF
       function isPdfFile($filename)
       {
           $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
           return $fileExtension === 'pdf';
       }
   
       // Function to check if the uploaded file is a JPG
       function isJpgFile($filename)
       {
           $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
           return $fileExtension === 'jpg';
       }
   

   
       if (!file_exists($uploadVotersParent_path) && isPdfFile($uploadVotersParent)) {
           move_uploaded_file($uploadVotersParent_tmp, $uploadVotersParent_path);
       }
   
       if (!file_exists($uploadITR_path) && isPdfFile($uploadITR)) {
           move_uploaded_file($uploadITR_tmp, $uploadITR_path);
       }
   
       if (!file_exists($uploadResidency_path) && isPdfFile($uploadResidency)) {
           move_uploaded_file($uploadResidency_tmp, $uploadResidency_path);
       }
   
       if (!file_exists($uploadGrade_path) && isPdfFile($uploadGrade)) {
           move_uploaded_file($uploadGrade_tmp, $uploadGrade_path);
       }
   
       // Move the files only if they don't already exist and are JPG files
       if (!file_exists($uploadPhotoJPG_path) && isJpgFile($uploadPhotoJPG)) {
           move_uploaded_file($uploadPhotoJPG_tmp, $uploadPhotoJPG_path);
       }

    // N/A if no input
    $middle_name = handleEmptyValue($middle_name);
    $religion = handleEmptyValue($religion);
    $suffix_name = handleEmptyValue($suffix_name);

 // Check for duplication based on first name, last name, and date of birth
$duplicateCheckQueryNameDOB = "SELECT lppp_reg_form_id FROM lppp_reg_form WHERE last_name = ? AND first_name = ? AND date_of_birth = ?";
$stmtDuplicateCheckNameDOB = mysqli_prepare($conn, $duplicateCheckQueryNameDOB);
mysqli_stmt_bind_param($stmtDuplicateCheckNameDOB, "sss", $last_name, $first_name, $date_of_birth);
mysqli_stmt_execute($stmtDuplicateCheckNameDOB);
$duplicateCheckResultNameDOB = mysqli_stmt_get_result($stmtDuplicateCheckNameDOB);

// Check for duplication based on guardian first name and last name
$duplicateCheckQueryGuardian = "SELECT lppp_reg_form_id FROM lppp_reg_form WHERE guardian_firstname = ? AND guardian_lastname = ?";
$stmtDuplicateCheckGuardian = mysqli_prepare($conn, $duplicateCheckQueryGuardian);
mysqli_stmt_bind_param($stmtDuplicateCheckGuardian, "ss", $guardian_firstname, $guardian_lastname);
mysqli_stmt_execute($stmtDuplicateCheckGuardian);
$duplicateCheckResultGuardian = mysqli_stmt_get_result($stmtDuplicateCheckGuardian);

if (mysqli_num_rows($duplicateCheckResultNameDOB) > 0) {
    // Display a duplicate record error message for matching first names and last names
    echo '<div class="ceapRegForm">Applicant already exists. Check your email for your temporary account. <p style="font-size: 18px;"> If this is a mistake, you may visit the scholarship office.</p><p style="font-size: 20px;">You can close this window now.</p></div>';
} elseif (mysqli_num_rows($duplicateCheckResultGuardian) > 0) {
    // Display a duplicate record error message for matching guardian names
    echo '<div class="ceapRegForm">Only one applicant per family is allowed.</br> <p style="font-size: 20px;"> If this is a mistake, you may visit the scholarship office.</p></div>';
} else {
      $insertQuery = "INSERT INTO lppp_reg_form (last_name, first_name, middle_name, suffix_name, date_of_birth, age, gender, civil_status, place_of_birth, religion, contact_number, active_email_address, house_number, province, municipality, barangay, guardian_lastname, guardian_firstname, guardian_relationship, guardian_occupation, guardian_monthly_income, guardian_annual_income, elementary_school, elementary_year, school_address, grade_level, uploadVotersParent, uploadResidency, uploadITR, uploadGrade, uploadPhotoJPG) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtInsert = mysqli_prepare($conn, $insertQuery);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmtInsert, "sssssisssssssssssssssssssssssss", $last_name, $first_name, $middle_name, $suffix_name, $date_of_birth, $age, $gender, $civil_status, $place_of_birth, $religion, $contact_number, $active_email_address, $house_number, $province, $municipality, $barangay, $guardian_lastname, $guardian_firstname, $guardian_relationship, $guardian_occupation, $guardian_monthly_income, $guardian_annual_income, $elementary_school, $elementary_year, $school_address, $grade_level, $uploadVotersParent, $uploadResidency, $uploadITR, $uploadGrade, $uploadPhotoJPG);

        if (mysqli_stmt_execute($stmtInsert)) {
            $lppp_reg_form_id = mysqli_stmt_insert_id($stmtInsert);

               // Generate control number
               $year = date("Y");
               $id_in_thousand = str_pad($lppp_reg_form_id, 4, '0', STR_PAD_LEFT);
               if ($barangay == 'DON JOSE') {
                   $barangay = 'DNJS';
               }
               $control_number = $year . '-' . $id_in_thousand . '-' . substr($barangay, 0, 4);

            $insertTempAccountQuery = "INSERT INTO lppp_temporary_account (lppp_reg_form_id, status) VALUES (?, 'In Progress')";
            $stmtTempAccount = mysqli_prepare($conn, $insertTempAccountQuery);
            mysqli_stmt_bind_param($stmtTempAccount, "i", $lppp_reg_form_id);

            if (mysqli_stmt_execute($stmtTempAccount)) {
              // Insert the control number into the `lppp_reg_form` table
              $updateQuery = "UPDATE lppp_reg_form SET control_number = ? WHERE lppp_reg_form_id = ?";
              $stmtUpdate = mysqli_prepare($conn, $updateQuery);
              mysqli_stmt_bind_param($stmtUpdate, "si", $control_number, $lppp_reg_form_id);

              if (mysqli_stmt_execute($stmtUpdate)) {
              $emailSent = sendEmail($active_email_address, $control_number);
              if ($emailSent) {
                echo 'success';
            } else {
                echo "error";
            }
        } else {
            // Handle the error if the update query fails
            echo "Error updating lppp_reg_form table: " . mysqli_error($conn);
        }
     } else {
         // Handle the error if the insert query for temporary_account fails
         echo "Error inserting into lppp_temporary_account table: " . mysqli_error($conn);
     }
}
    }
}

?>