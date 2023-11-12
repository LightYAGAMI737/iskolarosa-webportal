<?php
function validateEmailAndCheck($emailInputDuplicate) {
    include 'config_iskolarosa_db.php';

    $query = "SELECT * FROM employee_list WHERE email = '$emailInputDuplicate'";
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
        return false; // Error in the database query
    }
}

// Get the email from the POST request
$emailInputDuplicate = $_POST['email'];

// Initialize error message
$emailError = "";

// Check if email already exists
if (validateEmailAndCheck($emailInputDuplicate)) {
        echo 'email_exist';
}else{
    echo 'email_unique';
}
?>