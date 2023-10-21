<?php
include '../php/config_iskolarosa_db.php';

// Start the session
session_start();

// Function to sanitize user input for HTML display
function sanitizeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize for XSS prevention
    $qualifications = sanitizeInput($_POST['qualifications']);
    $requirements = sanitizeInput($_POST['requirements']);
    $startDate = sanitizeInput($_POST['startDate']);
    $startTime = sanitizeInput($_POST['startTime']);
    $endDate = sanitizeInput($_POST['endDate']);
    $endTime = sanitizeInput($_POST['endTime']);

    // Retrieve the toggle button value
    $toggleValue = isset($_POST['toggleButton']) ? 1 : 0;

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO lppp_configuration (qualifications, requirements, start_date, start_time, end_date, end_time, toggle_value) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $qualifications, $requirements, $startDate, $startTime, $endDate, $endTime, $toggleValue);

    // Perform the prepared statement
    if ($stmt->execute()) {
        // Log the LPPP configuration update action
        $employee_username = $_SESSION['username']; // Get the admin username from the session
        $action = "Updated LPPP Configuration: Qualifications, Requirements, Application Period"; // Define the action

        // Insert a new log entry into the employee_logs table
        $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $employee_username, $action);
        $stmt->execute();

        mysqli_close($conn);
        header("Location: lppp_configuration.php");
        exit;
    } else {
        echo "Error inserting data: " . $stmt->error;
    }
}

// Close the database connection
mysqli_close($conn);
?>
