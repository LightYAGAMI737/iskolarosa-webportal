<?php
include 'config_iskolarosa_db.php';

// Start the session
session_start();

// Function to sanitize user input for HTML display
function sanitizeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Set the timezone to 'Asia/Manila'
date_default_timezone_set('Asia/Manila');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize for XSS prevention
    $qualifications = sanitizeInput($_POST['qualifications']);
    $requirements = sanitizeInput($_POST['requirements']);
    $startDate = sanitizeInput($_POST['startDate']);
    $startTime = sanitizeInput($_POST['startTime']);
    $endDate = sanitizeInput($_POST['endDate']);
    $endTime = sanitizeInput($_POST['endTime']);

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Check if the current date and time are before the specified start time
    if ($currentDateTime < "$startDate $startTime") {
        $toggleValue = 0; // Uncheck the toggle button
    } else {
        $toggleValue = isset($_POST['toggleButton']) ? 1 : 0;
    }

    // Perform SQL insertion with prepared statements
    $sql = "INSERT INTO ceap_configuration (qualifications, requirements, start_date, start_time, end_date, end_time, toggle_value) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssssi", $qualifications, $requirements, $startDate, $startTime, $endDate, $endTime, $toggleValue);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Log the CEAP configuration update action
        $employee_username = $_SESSION['username']; // Get the admin username from the session
        $action = "Updated CEAP Configuration: Qualifications, Requirements, Application Period"; // Define the action

        // Insert a new log entry into the employee_logs table
        $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $employee_username, $action);
        mysqli_stmt_execute($stmt);

        mysqli_close($conn);
        header("Location: ../ceap_configuration.php");
        exit;
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);

?>