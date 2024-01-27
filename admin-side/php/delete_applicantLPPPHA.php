<?php
// Start the session
session_start();

include '../php/config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $applicantId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($applicantId === false || $applicantId === null) {
        // Handle invalid input for applicantId (e.g., not an integer)
        echo 'Invalid input for applicantId.';
        exit;
    }

    // SQL query to fetch the previous status from lppp_temporary_account
    $prevStatusQueryTEMPORARY = "SELECT status FROM lppp_temporary_account WHERE lppp_reg_form_id = ?";
    
    $stmtPrevTEMPORARY = $conn->prepare($prevStatusQueryTEMPORARY);
    $stmtPrevTEMPORARY->bind_param("i", $applicantId);
    $stmtPrevTEMPORARY->execute();
    $stmtPrevTEMPORARY->bind_result($previousStatusTEMPORARY);
    $stmtPrevTEMPORARY->fetch();
    $stmtPrevTEMPORARY->close();
    
    // SQL query to update 'is_deleted' in lppp_reg_form
    $updateQueryCEAP = "UPDATE lppp_reg_form SET is_deleted = TRUE WHERE lppp_reg_form_id = ?";
    
    $stmtCEAP = $conn->prepare($updateQueryCEAP);
    $stmtCEAP->bind_param("i", $applicantId);
    
    // SQL query to update 'status' in lppp_temporary_account
    $updateQueryTEMPORARY = "UPDATE lppp_temporary_account SET status = 'Deleted' WHERE lppp_reg_form_id = ?";
    
    $stmtTEMPORARY = $conn->prepare($updateQueryTEMPORARY);
    $stmtTEMPORARY->bind_param("i", $applicantId);
    
    // Execute both update queries within a transaction
    mysqli_begin_transaction($conn);

    $updateCEAP = $stmtCEAP->execute();
    $updateTEMPORARY = $stmtTEMPORARY->execute();

    if ($updateCEAP && $updateTEMPORARY) {
        // Log the status change in the applicant_status_logs table
        $logQuery = "INSERT INTO applicant_status_logs (previous_status, updated_status, lppp_reg_form_id, employee_logs_id) VALUES (?, ?, ?, ?)";
        
        // Retrieve the employee_logs_id based on the employee username (similar to the reference code)
        $employeeUsername = $_SESSION["username"];
        $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
        $stmtEmployeeId = $conn->prepare($employeeIdQuery);
        $stmtEmployeeId->bind_param("s", $employeeUsername);
        $stmtEmployeeId->execute();
        $stmtEmployeeId->bind_result($employeeLogsId);
        $stmtEmployeeId->fetch();
        $stmtEmployeeId->close();

        // Log the status change with the previously fetched status
        $stmtLog = $conn->prepare($logQuery);
        $status = 'deleted'; // Set the updated status as 'deleted'
        $stmtLog->bind_param("ssii", $previousStatusTEMPORARY, $status, $applicantId, $employeeLogsId);
        $stmtLog->execute();
        
        mysqli_commit($conn); // Both updates and log were successful, commit the transaction
        echo 'success'; // Update and log were successful
    } else {
        mysqli_rollback($conn); // Rollback the transaction if any update fails
        echo 'error'; // Update failed
    }

    $stmtCEAP->close();
    $stmtTEMPORARY->close();
}

mysqli_close($conn);
?>
