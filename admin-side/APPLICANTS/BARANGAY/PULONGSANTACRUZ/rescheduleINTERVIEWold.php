<?php
include '../../../php/config_iskolarosa_db.php';

// Check if the reschedule form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $limit = $_POST['limit']; // Number of applicants to reschedule
    $rescheduleOLDDate = $_POST['interview_date'];
    $rescheduleOLDHours = $_POST['interview_hours'];
    $rescheduleOLDMinutes = $_POST['interview_minutes'];
    $rescheduleOLDPeriod = $_POST['interview_ampm'];

    // Set the timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

    $currentStatus = 'interview';
    $currentDirectory = basename(__DIR__);
    $currentBarangay = $currentDirectory;
    // Assuming $currentStatus is also a variable you need to sanitize
    $currentStatus = mysqli_real_escape_string($conn, $currentStatus);

    // Create a prepared statement to select the applicants to be rescheduled
    $rescheduleOLDQuery = "SELECT t.* 
        FROM ceap_personal_account t
        WHERE t.barangay = ? AND t.status = ? AND t.interview_date = ?
        LIMIT ?";

    $stmt = mysqli_prepare($conn, $rescheduleOLDQuery);
    mysqli_stmt_bind_param($stmt, 'sssi', $currentBarangay, $currentStatus, $currentDate, $limit);
    mysqli_stmt_execute($stmt);
    $rescheduleOLDResult = mysqli_stmt_get_result($stmt);

    // Log the query for debugging
    error_log("Reschedule Query: " . $rescheduleOLDQuery);

    // Update the interview date for each applicant using prepared statement
    while ($row = mysqli_fetch_assoc($rescheduleOLDResult)) {
        $tempAccountId = $row['ceap_personal_account_id'];

        // Create a prepared statement to update the interview date in the ceap_personal_account table
        $updateQuery = "UPDATE ceap_personal_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ? WHERE ceap_personal_account_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ssssi', $rescheduleOLDDate, $rescheduleOLDHours, $rescheduleOLDMinutes, $rescheduleOLDPeriod, $tempAccountId);
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