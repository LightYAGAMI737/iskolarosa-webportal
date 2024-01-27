<?php
// Enable error reporting and display errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    trigger_error('User not logged in.', E_USER_ERROR);
}

include 'config_iskolarosa_db.php';
require_once 'email_update_infoHA.php'; 

// Check if the form was submitted
if (isset($_POST['update_all_info'])) {
    // Get the lppp_reg_form_id from the hidden input field
    $LPPPRegFormId = $_POST['lppp_reg_form_id'];
    $control_number = $_POST['control_number'];
    // Update Personal Information fields
    $personalFieldsToUpdate = [
        'control_number', 'last_name', 'first_name', 'middle_name', 'suffix_name',
        'date_of_birth', 'gender', 'civil_status', 'place_of_birth',
        'religion', 'contact_number', 'active_email_address', 'house_number',
        'province', 'municipality', 'barangay'
    ];
    foreach ($personalFieldsToUpdate as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $query = "UPDATE lppp_reg_form SET $field = ? WHERE lppp_reg_form_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $LPPPRegFormId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Error executing statement: ' . mysqli_stmt_error($stmt));
            }
        }
    }

    // Update Family Background fields
    $familyFieldsToUpdate = [
        'guardian_firstname', 'guardian_lastname', 
        'guardian_occupation', 'guardian_relationship', 
        'guardian_monthly_income', 'guardian_annual_income'
    ];
    foreach ($familyFieldsToUpdate as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $query = "UPDATE lppp_reg_form SET $field = ? WHERE lppp_reg_form_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $LPPPRegFormId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Error executing statement: ' . mysqli_stmt_error($stmt));
            }
        }
    }

    // Update Educational Background fields
    $educationalFieldsToUpdate = [
        'elementary_school', 'elementary_year', 
        'school_address', 'grade_level'
    ];
    foreach ($educationalFieldsToUpdate as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $query = "UPDATE lppp_reg_form SET $field = ? WHERE lppp_reg_form_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $LPPPRegFormId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Error executing statement: ' . mysqli_stmt_error($stmt));
            }
        }
    }
  // Call sendEmail function after the data has been updated
  $email = $_POST['active_email_address'];
  $control_numbers = $control_number;

  // Send the email with the updated status message
  if (sendEmail($email, $control_number)) {
      echo 'Email sent successfully. Redirecting...';
      header("Location: ../lppp_list.php");
      exit();
  } else {
      trigger_error('Failed to send email.', E_USER_ERROR);
  }
} else {
  // No fields were changed
  echo 'No fields were changed. Redirecting...';
  header("Location: ../lppp_list.php");
  exit();
}
?>