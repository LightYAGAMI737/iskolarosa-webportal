<?php
include '../../../php/config_iskolarosa_db.php';

// Check if the reschedule form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $limit = $_POST['limit']; // Number of applicants to reschedule
    $rescheduleDate = $_POST['interview_date'];
    $rescheduleHours = $_POST['interview_hours'];
    $rescheduleMinutes = $_POST['interview_minutes'];
    $reschedulePeriod = $_POST['interview_ampm'];

    // Set the timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    $currentStatus = 'interview';
    $currentDirectory = basename(__DIR__);
    $currentBarangay = $currentDirectory;
    // Assuming $currentStatus is also a variable you need to sanitize
    $currentStatus = mysqli_real_escape_string($conn, $currentStatus);

    // Create a prepared statement to select the applicants to be rescheduled
    $rescheduleQuery = "SELECT t.* 
        FROM temporary_account t
        INNER JOIN ceap_reg_form p
        ON p.ceap_reg_form_id = t.ceap_reg_form_id
        WHERE p.barangay = ? AND t.status = ? AND t.interview_date = ?
        LIMIT ?";

    $stmt = mysqli_prepare($conn, $rescheduleQuery);
    mysqli_stmt_bind_param($stmt, 'sssi', $currentBarangay, $currentStatus, $currentDate, $limit);
    mysqli_stmt_execute($stmt);
    $rescheduleResult = mysqli_stmt_get_result($stmt);

    // Log the query for debugging
    error_log("Reschedule Query: " . $rescheduleQuery);

    // Update the interview date for each applicant using prepared statement
    while ($row = mysqli_fetch_assoc($rescheduleResult)) {
        $tempAccountId = $row['temporary_account_id'];

        // Create a prepared statement to update the interview date in the temporary_account table
        $updateQuery = "UPDATE temporary_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ? WHERE temporary_account_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ssssi', $rescheduleDate, $rescheduleHours, $rescheduleMinutes, $reschedulePeriod, $tempAccountId);
        mysqli_stmt_execute($updateStmt);

        // Log the update for debugging
        error_log("Update Query: " . $updateQuery);
    }

    // Log success message for debugging
    error_log("Success: Rescheduled $limit applicants.");

    // Include currentBarangay in the response
    echo "success";
    exit();
}
?>
