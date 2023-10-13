<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<?php
   require_once '../PHPMailer/PHPMailer.php';
   require_once '../PHPMailer/SMTP.php';
   require_once '../PHPMailer/Exception.php';
   
   include '../admin-side/php/config_iskolarosa_db.php';
   
   function handleEmptyValue($value) {
       return empty($value) ? "N/A" : $value;
   }
   
   // Function to generate a random password
   function generateRandomPassword($length = 8)
   {
       // Characters to use in the password
       $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
       $password = '';
       
       // Generate a random password
       for ($i = 0; $i < $length; $i++) {
           $index = rand(0, strlen($characters) - 1);
           $password .= $characters[$index];
       }
   
       return $password;
   }
   
   // Function to send email using PHPMailer
   function sendEmail($email, $username, $password)
   {
    $mail = new PHPMailer\PHPMailer\PHPMailer(); // Create a new PHPMailer instance
   
    // SMTP settings (replace these with your SMTP server details)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace this with your SMTP server address
    $mail->SMTPAuth = true;
    $mail->Username = 'lancelirabackupfiles@gmail.com'; // Replace this with your SMTP username
    $mail->Password = 'ccrvxxdtujkbqanw'; // Replace this with your SMTP password
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
    $mail->Port = 465; // Use port 465 for SMTPS
    
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
       <div>As you begin your journey towards a bright academic future, we hope this email finds you in good spirits. On behalf of the City Scholarship Office, congratulations on successfully registering as an iSKOLAR under the<strong> College Educational Assistance Program!</strong></div>
       <br>
       <div>To help you get started, we are pleased to provide you with your Temporary Control Number and Password, which will grant you access to your personalized iSKOLAR account on our platform. Take note that these credentials are crucial for verifying your eligibility and confidentiality.</div>
       <br>
       <div class="login-details">
           Please find your login details below:<br>
           <strong>Control Number:</strong> ' . $username . '<br>
           <strong>Password:</strong> ' . $password . '<br>
       </div>
       <div>
           Once the verification process is complete, we will notify you promptly, and you will receive a formal confirmation of your Iskolar status or you can also check the website alongside your credentials to view the status page and check your progress. At that time, you can fully access the benefits and opportunities offered under the City Scholarship Office website\'s iSKOLAROSA.
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
   

   $schoolMappings = [
    "PUPSRC" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PUP SANTA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PUP STA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES STA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES SANTA ROSA" => "POLYTECHNIC UNIVERSITY OF THE PHILIPPINES",
    "PNC" => "PAMANTASAN NG CABUYAO",
    "OLFU" => "OUR LADY OF FATIMA",
    "OLFU SANTA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OLFU STA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OUR LADY OF FATIMA SANTA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
    "OUR LADY OF FATIMA STA ROSA" => "OUR LADY OF FATIMA SANTA ROSA",
];

// Function to map school names
function mapSchoolName($schoolName, $schoolMappings) {
    // Check if the school name is in the mappings, and return the full name if found
    return isset($schoolMappings[$schoolName]) ? $schoolMappings[$schoolName] : $schoolName;
}

   if (isset($_POST['submit'])) {
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_name = htmlspecialchars($_POST['middle_name']);
    $suffix_name = htmlspecialchars($_POST['suffix_name']);
    $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $civil_status = htmlspecialchars($_POST['civil_status']);
    $place_of_birth = htmlspecialchars($_POST['place_of_birth']);
    $religion = htmlspecialchars($_POST['religion']);
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $active_email_address = htmlspecialchars($_POST['active_email_address']);
    $house_number = htmlspecialchars($_POST['house_number']);
    $province = isset($_POST['province']) ? htmlspecialchars($_POST['province']) : "";
    $municipality = isset($_POST['municipality']) ? htmlspecialchars($_POST['municipality']) : "";
    $barangay = htmlspecialchars($_POST['barangay']);
    $guardian_firstname = htmlspecialchars($_POST['guardian_firstname']);
    $guardian_lastname = htmlspecialchars($_POST['guardian_lastname']);
    $guardian_occupation = htmlspecialchars($_POST['guardian_occupation']);
    $guardian_relationship = htmlspecialchars($_POST['guardian_relationship']);
    $guardian_monthly_income = htmlspecialchars($_POST['guardian_monthly_income']);
    $guardian_annual_income = htmlspecialchars($_POST['guardian_annual_income']);
    $elementary_school = htmlspecialchars($_POST['elementary_school']);
    $elementary_year = htmlspecialchars($_POST['elementary_year']);
    $secondary_school = htmlspecialchars($_POST['secondary_school']);
    $secondary_year = htmlspecialchars($_POST['secondary_year']);
    $senior_high_school = htmlspecialchars($_POST['senior_high_school']);
    $senior_high_year = htmlspecialchars($_POST['senior_high_year']);
    $course_enrolled = htmlspecialchars($_POST['course_enrolled']);
    $no_of_units = htmlspecialchars($_POST['no_of_units']);
    $year_level = htmlspecialchars($_POST['year_level']);
    $graduating = htmlspecialchars($_POST['graduating']);
    $school_name = htmlspecialchars($_POST['school_name']);
    $school_type = htmlspecialchars($_POST['school_type']);
    $expected_year_of_graduation = htmlspecialchars($_POST['expected_year_of_graduation']);
    $school_address = htmlspecialchars($_POST['school_address']);
    $student_id_no = htmlspecialchars($_POST['student_id_no']);
    $current_semester = htmlspecialchars($_POST['current_semester']);

       // N/A if no input
       $middle_name = handleEmptyValue($middle_name);
       $religion = handleEmptyValue($religion);
       $suffix_name = handleEmptyValue($suffix_name);
     
     
       // Function to sanitize and validate file names
function sanitizeFileName($filename) {
    // Remove potentially dangerous characters
    $filename = preg_replace("/[^\w\d.-]/", "", $filename);
    return $filename;
}

// Sanitize and validate file names
$uploadVotersApplicant = sanitizeFileName($_FILES['uploadVotersApplicant']['name']);
$uploadVotersParent = sanitizeFileName($_FILES['uploadVotersParent']['name']);
$uploadITR = sanitizeFileName($_FILES['uploadITR']['name']);
$uploadResidency = sanitizeFileName($_FILES['uploadResidency']['name']);
$uploadCOR = sanitizeFileName($_FILES['uploadCOR']['name']);
$uploadGrade = sanitizeFileName($_FILES['uploadGrade']['name']);
$uploadPhotoJPG = sanitizeFileName($_FILES['uploadPhotoJPG']['name']);

// File upload code
$uploadVotersApplicant_tmp = $_FILES['uploadVotersApplicant']['tmp_name'];
$uploadVotersApplicant_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_VotersApplicant.pdf';

$uploadVotersParent_tmp = $_FILES['uploadVotersParent']['tmp_name'];
$uploadVotersParent_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_VotersParent.pdf';

$uploadITR_tmp = $_FILES['uploadITR']['tmp_name'];
$uploadITR_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_ITR.pdf';

$uploadResidency_tmp = $_FILES['uploadResidency']['tmp_name'];
$uploadResidency_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Residency.pdf';

$uploadCOR_tmp = $_FILES['uploadCOR']['tmp_name'];
$uploadCOR_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_COR.pdf';

$uploadGrade_tmp = $_FILES['uploadGrade']['tmp_name'];
$uploadGrade_path = 'pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Grade.pdf';

$uploadPhotoJPG_tmp = $_FILES['uploadPhotoJPG']['tmp_name'];
$uploadPhotoJPG_path = 'applicant2x2/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_2x2_Picture.jpg';

// Rest of your code for file uploads...

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
   
       // Move the files only if they don't already exist and are PDF files
       if (!file_exists($uploadVotersApplicant_path) && isPdfFile($uploadVotersApplicant)) {
           move_uploaded_file($uploadVotersApplicant_tmp, $uploadVotersApplicant_path);
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
   
       if (!file_exists($uploadCOR_path) && isPdfFile($uploadCOR)) {
           move_uploaded_file($uploadCOR_tmp, $uploadCOR_path);
       }
   
       if (!file_exists($uploadGrade_path) && isPdfFile($uploadGrade)) {
           move_uploaded_file($uploadGrade_tmp, $uploadGrade_path);
       }
   
       // Move the files only if they don't already exist and are JPG files
       if (!file_exists($uploadPhotoJPG_path) && isJpgFile($uploadPhotoJPG)) {
           move_uploaded_file($uploadPhotoJPG_tmp, $uploadPhotoJPG_path);
       }
   
       
// Check for duplication based on first name, last name, and date of birth
$duplicateCheckQueryNameDOB = "SELECT ceap_reg_form_id FROM ceap_reg_form WHERE last_name = ? AND first_name = ? AND date_of_birth = ?";
$stmtDuplicateCheckNameDOB = mysqli_prepare($conn, $duplicateCheckQueryNameDOB);
mysqli_stmt_bind_param($stmtDuplicateCheckNameDOB, "sss", $last_name, $first_name, $date_of_birth);
mysqli_stmt_execute($stmtDuplicateCheckNameDOB);
$duplicateCheckResultNameDOB = mysqli_stmt_get_result($stmtDuplicateCheckNameDOB);

// Check for duplication based on guardian first name and last name
$duplicateCheckQueryGuardian = "SELECT ceap_reg_form_id FROM ceap_reg_form WHERE guardian_firstname = ? AND guardian_lastname = ?";
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
    // Map the school name using the function
$school_name = mapSchoolName($school_name, $schoolMappings);

           $insertQuery = "INSERT INTO ceap_reg_form (last_name, first_name, middle_name, suffix_name, date_of_birth, age, gender, civil_status, place_of_birth, religion, contact_number, active_email_address, house_number, province, municipality, barangay, guardian_firstname, guardian_lastname, guardian_occupation, guardian_relationship, guardian_monthly_income, guardian_annual_income, elementary_school, elementary_year, /*elementary_honors,*/ secondary_school, secondary_year, /*secondary_honors,*/ senior_high_school, senior_high_year, /*senior_high_honors,*/ course_enrolled, no_of_units, year_level, graduating, school_name, school_type, expected_year_of_graduation, school_address, current_semester, student_id_no, uploadVotersParent, uploadVotersApplicant, uploadITR, uploadResidency, uploadCOR, uploadGrade, uploadPhotoJPG) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   
           $stmtInsert = mysqli_prepare($conn, $insertQuery);
           mysqli_stmt_bind_param($stmtInsert, "sssssisssssssssssssssssssssssssssssssssssssss", $last_name, $first_name, $middle_name, $suffix_name, $date_of_birth, $age, $gender, $civil_status, $place_of_birth, $religion, $contact_number, $active_email_address, $house_number, $province, $municipality, $barangay, $guardian_firstname, $guardian_lastname, $guardian_occupation, $guardian_relationship, $guardian_monthly_income, $guardian_annual_income, $elementary_school, $elementary_year, $secondary_school, $secondary_year, $senior_high_school, $senior_high_year, $course_enrolled, $no_of_units, $year_level, $graduating, $school_name, $school_type, $expected_year_of_graduation, $school_address, $current_semester, $student_id_no, $uploadVotersParent, $uploadVotersApplicant, $uploadITR, $uploadResidency, $uploadCOR, $uploadGrade, $uploadPhotoJPG);
   
           if (mysqli_stmt_execute($stmtInsert)) {
               $ceap_reg_form_id = mysqli_stmt_insert_id($stmtInsert);
   
               // Generate control number
               $year = date("Y");
               $id_in_thousand = str_pad($ceap_reg_form_id, 4, '0', STR_PAD_LEFT);
               if ($barangay == 'DON JOSE') {
                   $barangay = 'DNJS';
               }
               $control_number = $year . '-' . $id_in_thousand . '-' . substr($barangay, 0, 4);

                 // Generate a random password
    $password = generateRandomPassword();

   // Insert the generated password into the `temporary_account` table
   $insertTempAccountQuery = "INSERT INTO temporary_account (ceap_reg_form_id, ceap_password, username, status) VALUES (?, ?, ?, 'In Progress')";
   $stmtTempAccount = mysqli_prepare($conn, $insertTempAccountQuery);
   mysqli_stmt_bind_param($stmtTempAccount, "iss", $ceap_reg_form_id, $password, $control_number);

   if (mysqli_stmt_execute($stmtTempAccount)) {
           // Success! Password is inserted into the temporary_account table.
   
           // Insert the control number into the `ceap_reg_form` table
           $updateQuery = "UPDATE ceap_reg_form SET control_number = ? WHERE ceap_reg_form_id = ?";
           $stmtUpdate = mysqli_prepare($conn, $updateQuery);
           mysqli_stmt_bind_param($stmtUpdate, "si", $control_number, $ceap_reg_form_id);
   
           if (mysqli_stmt_execute($stmtUpdate)) {
               // Success! Both temporary_account and ceap_reg_form tables are updated.
   
               // Send the email with username and password (using $control_number as the username)
              if (sendEmail($active_email_address, $control_number, $password)) { // Pass the generated password here
        echo '<div class="ceapRegForm">Registration Successful! <br> Check your email for your username and password. <br> <p style="font-size: 20px;">You can close this window now.</p>' . '</div>';
    } else {
        echo "Error: Failed to send email.";
    }
           } else {
               // Handle the error if the update query fails
               echo "Error updating ceap_reg_form table: " . mysqli_error($conn);
           }
        } else {
            // Handle the error if the insert query for temporary_account fails
            echo "Error inserting into temporary_account table: " . mysqli_error($conn);
        }
   }
       }
   }
   ?>
<script>
   // Call JavaScript function to update file names
   updateFileNames();
   
   function updateFileNames() {
     var last_name = "<?php echo $last_name; ?>";
     var first_name = "<?php echo $first_name; ?>";
   
     // Update PDF file names
     var uploadVotersApplicant = last_name + ', ' + first_name + '_ApplicantVoters.pdf';
     var uploadVotersParent = last_name + ', ' + first_name + '_VotersParent.pdf';
     var uploadITR = last_name + ', ' + first_name + '_ITR.pdf';
     var uploadResidency = last_name + ', ' + first_name + '_Residency.pdf';
     var uploadCOR = last_name + ', ' + first_name + '_COR.pdf';
     var uploadGrade = last_name + ', ' + first_name + '_Grade.pdf';
   
     // Update JPG file name
     var uploadPhotoJPG = last_name + ', ' + first_name + '_2x2_Picture.jpg';
   
     $.ajax({
       type: 'POST',
       url: 'update_file_names.php',
       data: {
         ceap_reg_form_id: <?php echo $ceap_reg_form_id; ?>,
         last_name: last_name,
         first_name: first_name,
         uploadVotersApplicant: uploadVotersApplicant,
         uploadVotersParent: uploadVotersParent,
         uploadITR: uploadITR,
         uploadResidency: uploadResidency,
         uploadCOR: uploadCOR,
         uploadGrade: uploadGrade,
         uploadPhotoJPG: uploadPhotoJPG
       },
       success: function(response) {
         console.log(response);
       },
       error: function(xhr, status, error) {
         console.log(error);
       }
     });
   }
</script>
<!DOCTYPE html>
<html>
   <head>
      <title>iSKOLORSA</title>
      <link rel="stylesheet" href="css/control_number.css">   
      
   </head>
   <body>


   </body>
</html>