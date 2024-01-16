<?php
include 'config_iskolarosa_db.php';
require_once 'email_update_status_lppp.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];
    $applicantId = $_POST["id"];
    
    // Update the status in the database
    $updateQuery = "UPDATE lppp_temporary_account SET status = ? WHERE lppp_reg_form_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $applicantId);
    $stmt->execute();

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

mysqli_close($conn);
?>