<?php
session_start();
// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['control_number'])) {
   // You can either show a message or redirect to the login page
   echo 'You need to log in to access this page.';
   // OR
    header("Location: index.php"); // Redirect to the login page
   exit();
}
include '../../admin-side/php/config_iskolarosa_db.php';

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the current year
$currentYear = date('Y');
$lastYear = $currentYear - 1;
                     
if (isset($_SESSION['control_number'])) {
$control_number = $_SESSION['control_number'];
}else {
echo 'No applicant selected.';
exit();
}
// Retrieve data from the ceap_personal_account table based on control_number
$tempAccountSql = "SELECT p.last_name, p.first_name, p.school_name,p.guardian_firstname,p.guardian_lastname,p.middle_name, p.suffix_name, p.control_number, t.status 
FROM ceap_reg_form p
JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id 
WHERE p.control_number = ?";
$stmt = mysqli_prepare($conn, $tempAccountSql);
mysqli_stmt_bind_param($stmt, "s", $control_number);
mysqli_stmt_execute($stmt);
$tempAccountResult = mysqli_stmt_get_result($stmt);
  
    // Fetch the applicant's information
    if (mysqli_num_rows($tempAccountResult) > 0) {
        // Information of applicant-name-control number
        $applicantData = mysqli_fetch_assoc($tempAccountResult);
        $first_name = $applicantData['first_name'];
        $last_name = $applicantData['last_name'];
        $school_name = $applicantData['school_name'];// Uppercase the school name
        $uppercaseSchoolName = strtoupper($school_name);
        $control_number = $applicantData['control_number'];
        $guardian_firstname = $applicantData['guardian_firstname'];
        $guardian_lastname = $applicantData['guardian_lastname'];
        }
?>