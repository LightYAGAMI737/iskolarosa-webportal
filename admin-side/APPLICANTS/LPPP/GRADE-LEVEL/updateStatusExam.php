<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../../php/config_iskolarosa_db.php';
    require_once '../../../php/email_update_status_examLPPP.php'; 

    // Process and update interview dates
    $interviewDate = $_POST['exam_date'];
    $exam_hour = $_POST['exam_hour'];
    $exam_minutes = $_POST['exam_minutes'];
    $exam_ampm = $_POST['exam_ampm'];
    $limit = $_POST['limit'];

    if (!empty($interviewDate) && !empty($exam_hour) && !empty($exam_minutes) && !empty($exam_ampm) && !empty($limit)) {
        $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
        $limit = intval($limit);

        $qualifiedQuery = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, p.lppp_reg_form_id
    FROM lppp_reg_form p
    INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
    WHERE t.status = 'Verified'
    LIMIT ?";
        $stmt = mysqli_prepare($conn, $qualifiedQuery);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $qualifiedResult = mysqli_stmt_get_result($stmt);

        $updateCount = 0; // Track the number of applicants updated
        while ($row = mysqli_fetch_assoc($qualifiedResult)) {
            if ($updateCount >= $limit) {
                break; // Stop updating once the limit is reached
            }
            $adminUsername = $_SESSION['username'];
            $LPPPregFormID = $row['lppp_reg_form_id'];

            // Retrieve the employee logs ID based on the logged-in user's username
            $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
            $stmtEmployeeId = mysqli_prepare($conn, $employeeIdQuery);
            mysqli_stmt_bind_param($stmtEmployeeId, "s", $adminUsername);
            mysqli_stmt_execute($stmtEmployeeId);
            mysqli_stmt_bind_result($stmtEmployeeId, $employeeLogsId);
            mysqli_stmt_fetch($stmtEmployeeId);
            mysqli_stmt_close($stmtEmployeeId);

            $updateTimeQuery = "UPDATE lppp_temporary_account SET exam_date = ?, exam_hour = ?, exam_minute = ?, exam_period = ?, updated_by = ? WHERE lppp_reg_form_id = ?";
            $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
            mysqli_stmt_bind_param($stmtTimeUpdate, "siiisi", $interviewDate, $exam_hour, $exam_minutes, $exam_ampm, $adminUsername, $LPPPregFormID);
            mysqli_stmt_execute($stmtTimeUpdate);

            // Update the status to 'exam'
            $statusUpdateQuery = "UPDATE lppp_temporary_account SET status = 'exam' WHERE lppp_reg_form_id = ?";
            $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
            mysqli_stmt_bind_param($stmtStatusUpdate, "i", $LPPPregFormID);
            mysqli_stmt_execute($stmtStatusUpdate);
            
            date_default_timezone_set('Asia/Manila');
            $currentTimeStatus = date('Y-m-d H:i:s');
            // Insert log
            $logQuery = "INSERT INTO applicant_status_logs (previous_status, updated_status, lppp_reg_form_id, employee_logs_id, timestamp) VALUES (?, ?, ?, ?,?)";
            $stmtLog = mysqli_prepare($conn, $logQuery);
            $previousStatus = 'Verified';
            $updatedStatus = 'exam';
            mysqli_stmt_bind_param($stmtLog, "ssiis", $previousStatus, $updatedStatus, $LPPPregFormID, $employeeLogsId, $currentTimeStatus);
            mysqli_stmt_execute($stmtLog);

            // Fetch the applicant's email address and control number from the database
            $applicantEmailQuery = "SELECT active_email_address, control_number FROM lppp_reg_form WHERE lppp_reg_form_id = ?";
            $stmtApplicantEmail = $conn->prepare($applicantEmailQuery);
            $stmtApplicantEmail->bind_param("i", $LPPPregFormID);
            $stmtApplicantEmail->execute();
            $stmtApplicantEmail->bind_result($applicantEmail, $control_number);
            $stmtApplicantEmail->fetch();
            $stmtApplicantEmail->close();

            // Send an email to the applicant with exam date
            $recipientEmail = $applicantEmail; // Use the fetched applicant email
            $emailSent = sendEmailInterview($recipientEmail, $control_number, 'exam', $interviewDate);

            if ($emailSent) {
                echo 'success'; // Update, log, and email sending were successful
            } else {
                echo 'email_error'; // Email sending failed, but the update and log were successful
            }

            $updateCount++;
        }
    } else {
        echo 'error'; // Update failed
    }

    mysqli_close($conn);
}
?>
