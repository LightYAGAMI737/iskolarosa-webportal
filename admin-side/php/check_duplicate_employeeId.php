<?php
function validateemployeeIdAndCheck($employeeIdInputDuplicate) {
    include 'config_iskolarosa_db.php';
    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM employee_list WHERE employee_id_no = ?";
    // Initialize the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "s", $employeeIdInputDuplicate);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Store the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // Check if any rows are returned
        if (mysqli_num_rows($result) > 0) {
            return true; // employeeId already exists
        } else {
            return false; // employeeId is unique
        }
    } else {
        echo "Database query error: " . mysqli_error($conn);
        return false; // Error in the database query
    }
}

// Get the employeeId from the POST request
$employeeIdInputDuplicate = $_POST['employeeId'];

// Initialize error message
$employeeIdError = "";

// Check if employeeId already exists
if (validateemployeeIdAndCheck($employeeIdInputDuplicate)) {
    echo 'employeeId_exist';
} else {
    echo 'employeeId_unique';
}
?>
