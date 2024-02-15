<?php
// Start the session
session_start();

include 'config_iskolarosa_db.php';
require_once 'email_update_status_lppp.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $status = htmlspecialchars($_POST["status"], ENT_QUOTES, 'UTF-8');
    $applicantId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $employeeUsername = $_SESSION["username"];

    if ($applicantId === false || $applicantId === null) {
        // Handle invalid input for the applicantId (e.g., not an integer)
        echo 'Invalid input for applicantId.';
        exit;
    }

    // Retrieve the previous status from the database
    $prevStatusQuery = "SELECT status FROM lppp_temporary_account WHERE lppp_reg_form_id = ?";
    $stmtPrev = $conn->prepare($prevStatusQuery);
    $stmtPrev->bind_param("i", $applicantId);
    $stmtPrev->execute();
    $stmtPrev->bind_result($previousStatus);
    $stmtPrev->fetch();
    $stmtPrev->close();

    // Update the status in the database using a prepared statement
    $updateQuery = "UPDATE lppp_temporary_account SET status = ? WHERE lppp_reg_form_id = ?";
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

        date_default_timezone_set('Asia/Manila');
        $currentTimeStatus = date('Y-m-d H:i:s');
        // Log the status change in the applicant_status_logs table using a prepared statement
        $logQuery = "INSERT INTO applicant_status_logs (previous_status, updated_status, lppp_reg_form_id, employee_logs_id, timestamp) VALUES (?, ?, ?, ?, ?)";
        $stmtLog = $conn->prepare($logQuery);
        $stmtLog->bind_param("ssiis", $previousStatus, $status, $applicantId, $employeeLogsId,$currentTimeStatus);
        $stmtLog->execute();

        // Fetch the applicant's email address and control number from the database
        $applicantEmailQuery = "SELECT active_email_address, control_number FROM lppp_reg_form WHERE lppp_reg_form_id = ?";
        $stmtApplicantEmail = $conn->prepare($applicantEmailQuery);
        $stmtApplicantEmail->bind_param("i", $applicantId);
        $stmtApplicantEmail->execute();
        $stmtApplicantEmail->bind_result($applicantEmail, $control_number);
        $stmtApplicantEmail->fetch();
        $stmtApplicantEmail->close();

        // Send an email to the applicant
        $recipientEmail = $applicantEmail; // Use the fetched applicant email
        $emailSent = sendEmail($recipientEmail, $control_number, $status);

        if ($emailSent) {
            echo 'success'; // Update, log, and email sending were successful
        } else {
            echo 'email_error'; // Email sending failed, but the update and log were successful
        }
        } else {
            echo 'error'; // Update failed
        }
}

mysqli_close($conn);
?>
