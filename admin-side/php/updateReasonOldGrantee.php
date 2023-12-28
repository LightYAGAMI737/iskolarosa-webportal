<?php
// Start the session
session_start();

include 'config_iskolarosa_db.php';
require_once 'email_update_status_reason.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_STRING);
    $applicantId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $reason = filter_input(INPUT_POST, "reason", FILTER_SANITIZE_STRING);
    $employeeUsername = $_SESSION["username"];
    
    // Check if input validation failed
    if ($status === false || $applicantId === false || $reason === false || $applicantId === null) {
        $response = 'error'; // Invalid input
    } else {
        // Retrieve the previous status from the database
        $prevStatusQuery = "SELECT status FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
        $stmtPrev = $conn->prepare($prevStatusQuery);
        $stmtPrev->bind_param("i", $applicantId);
        $stmtPrev->execute();
        $stmtPrev->bind_result($previousStatus);
        $stmtPrev->fetch();
        $stmtPrev->close();

        // Update the status and reason in the database using prepared statements
        $updateQuery = "UPDATE ceap_personal_account SET status = ?, reason = ? WHERE ceap_personal_account_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $status, $reason, $applicantId);

        if ($stmt->execute()) {
            // Retrieve the employee_logs_id based on the employee username
            $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
            $stmtEmployeeId = $conn->prepare($employeeIdQuery);
            $stmtEmployeeId->bind_param("s", $employeeUsername);
            $stmtEmployeeId->execute();
            $stmtEmployeeId->bind_result($employeeLogsId);
            $stmtEmployeeId->fetch();
            $stmtEmployeeId->close();

            // Log the status change in the applicant_status_logs table
            $logQuery = "INSERT INTO old_grantee_status_logs (old_previous_status, old_updated_status, ceap_personal_account_id, employee_logs_id) VALUES (?, ?, ?, ?)";
            $stmtLog = $conn->prepare($logQuery);
            $stmtLog->bind_param("ssii", $previousStatus, $status, $applicantId, $employeeLogsId);
            $stmtLog->execute();
            
            // Fetch the applicant's email address and control number from the database
            $applicantEmailQuery = "SELECT active_email_address, control_number FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
            $stmtApplicantEmail = $conn->prepare($applicantEmailQuery);
            $stmtApplicantEmail->bind_param("i", $applicantId);
            $stmtApplicantEmail->execute();
            $stmtApplicantEmail->bind_result($applicantEmail, $control_number);
            $stmtApplicantEmail->fetch();
            $stmtApplicantEmail->close();
    
            // Send an email to the applicant
            $recipientEmail = $applicantEmail; // Use the fetched applicant email
            $emailSent = sendEmail($recipientEmail, $control_number, $status, $reason);
    
            if ($emailSent) {
                $response = 'success'; // Update, log, and email sending were successful
            } else {
                $response = 'email_error'; // Email sending failed, but the update and log were successful
            }
        } else {
            $response = 'error'; // Update failed
        }
    }
}

mysqli_close($conn);

echo $response;
?>