<?php
function validateUpdateEmailAndCheckApplicant($emailInputDuplicateCEAP) {
    include '../../admin-side/php/config_iskolarosa_db.php';

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM ceap_reg_form WHERE active_email_address = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $emailInputDuplicateCEAP);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

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
// Close the database connection
mysqli_close($conn);
}

$emailInputDuplicateCEAP = $_POST['email'];

// Initialize error message
$emailError = "";

// Check if email already exists
if (validateUpdateEmailAndCheckApplicant($emailInputDuplicateCEAP)) {
    echo 'email_exist';
} else {
    echo 'not_exists';
}

// Check if $stmt is defined before closing it
if (isset($stmt)) {
    // Close the prepared statement
    mysqli_stmt_close($stmt);
}


?>
