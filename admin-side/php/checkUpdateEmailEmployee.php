<?php
function validateUpdateEmailAndCheck($employeeId, $emailInputDuplicate) {
    include 'config_iskolarosa_db.php';

    // Check if the email exists for a different employee
    $query = "SELECT * FROM employee_list WHERE email = '$emailInputDuplicate' AND employee_id != $employeeId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if any rows are returned
        if (mysqli_num_rows($result) > 0) {
            return 'email_exist'; // Email exists for another employee
        } else {
            return 'email_unique'; // Email is unique
        }
    } else {
        echo "Database query error: " . mysqli_error($conn);
        return 'database_error'; // Error in the database query
    }
}

// Get the email and employeeId from the POST request
$emailInputDuplicate = $_POST['email'];
$employeeId = $_POST['employee_id'];

// Initialize error message
$emailError = "";

// Check if the email matches the current employee
if ($employeeId && validateUpdateEmailAndCheck($employeeId, $emailInputDuplicate) === 'email_unique') {
    echo 'true'; // Email matches the current employee
} else {
    // Check if the email exists for another employee
    $result = validateUpdateEmailAndCheck($employeeId, $emailInputDuplicate);
    echo $result; // 'email_exist' or 'database_error'
}
?>
