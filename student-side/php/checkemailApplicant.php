<?php
function validateUpdateEmailAndCheckApplicant($emailInputDuplicateCEAP) {
    include '../../admin-side/php/config_iskolarosa_db.php';
    
    // Check if the active_email_address exists for a different employee
    $query = "SELECT * FROM ceap_reg_form WHERE active_email_address = '$emailInputDuplicateCEAP'";
    $result = mysqli_query($conn, $query);

    if ($result) {
         // Check if any rows are returned
         if (mysqli_num_rows($result) > 0) {
            return true; // Email already exists
        } else {
            return false; // Email is unique
        }
    } else {
        echo "Database query error: " . mysqli_error($conn);
        return 'database_error'; // Error in the database query
    }
}

$emailInputDuplicateCEAP = $_POST['email'];

// Initialize error message
$emailError = "";

// Check if email already exists
if (validateUpdateEmailAndCheckApplicant($emailInputDuplicateCEAP)) {
        echo 'email_exist';
}else{
    echo 'not_exists';
}
?>
