<?php
// Start the session
session_start();

include 'config_iskolarosa_db.php';
require_once 'email_update_status_reason.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
// Sanitize user input
$status = htmlspecialchars($_POST["status"], ENT_QUOTES, 'UTF-8');
$applicantId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$reasonFail = htmlspecialchars($_POST["reasonFail"], ENT_QUOTES, 'UTF-8');
$employeeUsername = $_SESSION["username"];

    
    // Check if input validation failed
    if ($status === false || $applicantId === false || $reasonFail === false || $applicantId === null) {
        $response = 'error'; // Invalid input
    }

        // Retrieve the previous status from the database
        $prevStatusQuery = "SELECT status FROM temporary_account WHERE ceap_reg_form_id = ?";
        $stmtPrev = $conn->prepare($prevStatusQuery);
        $stmtPrev->bind_param("i", $applicantId);
        $stmtPrev->execute();
        $stmtPrev->bind_result($previousStatus);
        $stmtPrev->fetch();
        $stmtPrev->close();

        // Update the status and reason in the database using prepared statements
        $updateQuery = "UPDATE temporary_account SET status = ?, reason = ? WHERE ceap_reg_form_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $status, $reasonFail, $applicantId);

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
            $logQuery = "INSERT INTO applicant_status_logs (previous_status, updated_status, ceap_reg_form_id, employee_logs_id, timestamp) VALUES (?, ?, ?, ?, ?)";
            $stmtLog = $conn->prepare($logQuery);
            $stmtLog->bind_param("ssiis", $previousStatus, $status, $applicantId, $employeeLogsId,$currentTimeStatus);
            $stmtLog->execute();
            
          // Fetch the applicant's email address and control number from the database
          $applicantEmailQuery = "SELECT active_email_address, control_number FROM ceap_reg_form WHERE ceap_reg_form_id = ?";
          $stmtApplicantEmail = $conn->prepare($applicantEmailQuery);
          $stmtApplicantEmail->bind_param("i", $applicantId);
          $stmtApplicantEmail->execute();
          $stmtApplicantEmail->bind_result($applicantEmail, $control_number);
          $stmtApplicantEmail->fetch();
          $stmtApplicantEmail->close();
  
          // Send an email to the applicant
          $recipientEmail = $applicantEmail; // Use the fetched applicant email
          $emailSent = sendEmail($recipientEmail, $control_number, $status, $reasonFail);
  
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
