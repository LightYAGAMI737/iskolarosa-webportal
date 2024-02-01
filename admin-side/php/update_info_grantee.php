<?php
session_start();

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['username'])) {
    error_log('Error: User not logged in.');
    echo 'You need to log in to access this page.';
    exit();
}

include 'config_iskolarosa_db.php';

// Check if the form was submitted
if (isset($_POST['update_all_info'])) {
    // Get the ceap_personal_account_id from the hidden input field
    $ceapRegFormId = $_POST['ceap_personal_account_id'];

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
            $query = "UPDATE ceap_personal_account SET $field = ? WHERE ceap_personal_account_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $ceapRegFormId);
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
            $query = "UPDATE ceap_personal_account SET $field = ? WHERE ceap_personal_account_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $ceapRegFormId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Error executing statement: ' . mysqli_stmt_error($stmt));
            }
        }
    }

    // Update Educational Background fields
    $educationalFieldsToUpdate = [
        'elementary_school', 'elementary_year', 'elementary_honors',
        'secondary_school', 'secondary_year', 'secondary_honors',
        'senior_high_school', 'senior_high_year', 'senior_high_honors',
        'course_enrolled', 'no_of_units', 'year_level', 'current_semester',
        'graduating', 'school_name', 'school_type', 'expected_year_of_graduation',
        'school_address', 'student_id_no'
    ];
    foreach ($educationalFieldsToUpdate as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $query = "UPDATE ceap_personal_account SET $field = ? WHERE ceap_personal_account_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                error_log('Error preparing statement: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, "si", $value, $ceapRegFormId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log('Error executing statement: ' . mysqli_stmt_error($stmt));
            }
        }
    }

    echo '<script>window.location.href = "../old_ceap_list.php";</script>';
} else {
    error_log('Error: Form not submitted.');
    echo 'Form not submitted.';
}
?>
