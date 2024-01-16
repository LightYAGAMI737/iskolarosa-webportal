<?php
include 'config_iskolarosa_db.php';
require_once 'email_update_status_interviewLPPP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $status = isset($_POST["status"]) ? htmlspecialchars($_POST["status"]) : '';
    $applicantId = isset($_POST["id"]) ? $_POST["id"] : '';

    // Validate and sanitize the score
    $applicantScore = isset($_POST["applicantScore"]) ? filter_var($_POST["applicantScore"], FILTER_VALIDATE_INT) : null;

    // Update the status and score in the database using prepared statements
    $updateQuery = "UPDATE lppp_temporary_account SET status = ?, applicant_score = ? WHERE lppp_reg_form_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sii", $status, $applicantScore, $applicantId);
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
    $emailSent = sendEmail($recipientEmail, $control_number, $status, $applicantScore);

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
