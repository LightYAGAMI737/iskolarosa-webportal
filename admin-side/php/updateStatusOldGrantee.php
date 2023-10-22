<?php

// Start the session
session_start();

include 'config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $applicantId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $employeeUsername = $_SESSION["username"];

    if ($applicantId === false || $applicantId === null) {
        // Handle invalid input for the applicantId (e.g., not an integer)
        echo 'Invalid input for applicantId.';
        exit;
    }

    // Retrieve the previous status from the database
    $prevStatusQuery = "SELECT status FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
    $stmtPrev = $conn->prepare($prevStatusQuery);
    $stmtPrev->bind_param("i", $applicantId);
    $stmtPrev->execute();
    $stmtPrev->bind_result($previousStatus);
    $stmtPrev->fetch();
    $stmtPrev->close();

    // Update the status in the database using a prepared statement
    $updateQuery = "UPDATE ceap_personal_account SET status = ? WHERE ceap_personal_account_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $applicantId);
    
    if ($stmt->execute()) {
        // Retrieve the employee_logs_id based on the employee username
        $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
        $stmtEmployeeId = $conn->prepare($employeeIdQuery);
        $stmtEmployeeId->bind_param("s", $employeeUsername);
        $stmtEmployeeId->execute();
        $stmtEmployeeId->bind_result($employeeLogsId);
        $stmtEmployeeId->fetch();
        $stmtEmployeeId->close();

        // Log the status change in the applicant_status_logs table using a prepared statement
        $logQuery = "INSERT INTO old_grantee_status_logs (old_previous_status, old_updated_status, ceap_personal_account_id, employee_logs_id) VALUES (?, ?, ?, ?)";
        $stmtLog = $conn->prepare($logQuery);
        $stmtLog->bind_param("ssii", $previousStatus, $status, $applicantId, $employeeLogsId);
        $stmtLog->execute();
        
        $response = 'success'; // Update and log were successful
    } else {
        $response = 'error'; // Update failed
    }

    echo $response; // Send the response back to the JavaScript code
}

mysqli_close($conn);
?>
