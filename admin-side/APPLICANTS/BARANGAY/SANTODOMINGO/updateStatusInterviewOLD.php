<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../../php/config_iskolarosa_db.php';
    require_once '../../../php/email_update_status_interview.php';

    $currentStatus = 'Verified';
    $currentDirectory = basename(__DIR__);
    $currentBarangay = $currentDirectory;
    $currentStatus = mysqli_real_escape_string($conn, $currentStatus);

    $interviewDate = $_POST['interview_date'];
    $interview_hours = $_POST['interview_hours'];
    $interview_minutes = $_POST['interview_minutes'];
    $interview_ampm = $_POST['interview_ampm'];
    $limit = $_POST['limit'];

    if (!empty($interviewDate) && !empty($interview_hours) && !empty($interview_minutes) && !empty($interview_ampm) && !empty($limit)) {
        $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
        $limit = intval($limit);

        $qualifiedQuery = "SELECT *
            FROM ceap_personal_account p
            WHERE p.status = ? AND p.barangay = ?
            LIMIT ?";

        $stmt = mysqli_prepare($conn, $qualifiedQuery);
        mysqli_stmt_bind_param($stmt, "ssi", $currentStatus, $currentBarangay, $limit);
        mysqli_stmt_execute($stmt);
        $qualifiedResult = mysqli_stmt_get_result($stmt);

        $updateCount = 0;

        while ($row = mysqli_fetch_assoc($qualifiedResult)) {
            if ($updateCount >= $limit) {
                break;
            }

            $ceapRegFormId = $row['ceap_personal_account_id']; // Assuming this is the correct variable

            // Retrieve the previous status from the database
            $prevStatusQuery = "SELECT status FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
            $stmtPrev = mysqli_prepare($conn, $prevStatusQuery);
            mysqli_stmt_bind_param($stmtPrev, "i", $ceapRegFormId);
            mysqli_stmt_execute($stmtPrev);
            mysqli_stmt_bind_result($stmtPrev, $previousStatus);
            mysqli_stmt_fetch($stmtPrev);
            mysqli_stmt_close($stmtPrev);

            // Update the interview date and time
            $updateTimeQuery = "UPDATE ceap_personal_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ? WHERE ceap_personal_account_id = ?";
            $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
            mysqli_stmt_bind_param($stmtTimeUpdate, "siisi", $interviewDate, $interview_hours, $interview_minutes, $interview_ampm, $ceapRegFormId);
            mysqli_stmt_execute($stmtTimeUpdate);

            // Use mysqli_stmt_affected_rows instead of $stmt->affected_rows
            if (mysqli_stmt_affected_rows($stmtTimeUpdate) > 0) {
                // Update the status to 'interview'
                $statusUpdateQuery = "UPDATE ceap_personal_account SET status = 'interview' WHERE ceap_personal_account_id = ?";
                $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
                mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceapRegFormId);
                mysqli_stmt_execute($stmtStatusUpdate);
                
                // Close the statement
                mysqli_stmt_close($stmtStatusUpdate);

                // Retrieve the employee_logs_id based on the employee username
                $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
                $stmtEmployeeId = mysqli_prepare($conn, $employeeIdQuery);
                mysqli_stmt_bind_param($stmtEmployeeId, "s", $_SESSION["username"]);
                mysqli_stmt_execute($stmtEmployeeId);
                mysqli_stmt_bind_result($stmtEmployeeId, $employeeLogsId);
                mysqli_stmt_fetch($stmtEmployeeId);
                mysqli_stmt_close($stmtEmployeeId);

                $status = 'interview'; // Assign a value to $status before the insert query
                // Log the status change in the applicant_status_logs table
                date_default_timezone_set('Asia/Manila');
                $currentTimeStatus = date('Y-m-d H:i:s');
                // Log the status change in the applicant_status_logs table using a prepared statement
                $logQuery = "INSERT INTO old_grantee_status_logs (old_previous_status, old_updated_status, ceap_personal_account_id, employee_logs_id, timestamp) VALUES (?, ?, ?, ?, ?)";
                $stmtLog = $conn->prepare($logQuery);
                $stmtLog->bind_param("ssiis", $previousStatus, $status, $ceapRegFormId, $employeeLogsId,$currentTimeStatus);
                $stmtLog->execute();

                // Fetch the applicant's email address and control number from the database
                $applicantEmailQuery = "SELECT active_email_address, control_number FROM ceap_personal_account WHERE ceap_personal_account_id = ?";
                $stmtApplicantEmail = mysqli_prepare($conn, $applicantEmailQuery);
                mysqli_stmt_bind_param($stmtApplicantEmail, "i", $ceapRegFormId);
                mysqli_stmt_execute($stmtApplicantEmail);
                mysqli_stmt_bind_result($stmtApplicantEmail, $applicantEmail, $control_number);
                mysqli_stmt_fetch($stmtApplicantEmail);
                mysqli_stmt_close($stmtApplicantEmail);

                // Send an email to the applicant
                $recipientEmail = $applicantEmail; // Use the fetched applicant email
                $emailSent = sendEmailInterview($recipientEmail, $control_number, $status, $interviewDate);

                if ($emailSent) {
                    echo 'success'; // Update, log, and email sending were successful
                } else {
                    echo 'email_error'; // Email sending failed, but the update and log were successful
                }
            } else {
                echo 'error'; // Update failed
            }
            $updateCount++;
        }

        // Close the main prepared statement
        mysqli_stmt_close($stmt);
    }
}
?>