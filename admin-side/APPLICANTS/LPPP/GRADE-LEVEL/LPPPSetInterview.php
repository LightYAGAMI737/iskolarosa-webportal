<?php
// Include configuration and functions
session_start();
include '../../../php/config_iskolarosa_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process and update interview dates
    $interviewDate = $_POST['interview_date'];
    $interview_hour = $_POST['interview_hours']; 
    $interview_minutes = $_POST['interview_minutes']; 
    $interview_ampm = $_POST['interview_ampm'];
    $limit = $_POST['limit'];
 
    if (!empty($interviewDate) && !empty($interview_hour) && !empty($interview_minutes) && !empty($interview_ampm) && !empty($limit)) {
        $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
        $limit = intval($limit);
 
        $qualifiedQuery = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, p.lppp_reg_form_id
        FROM lppp_reg_form p
        INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
        WHERE t.status = 'interview' AND interview_date = '0000-00-00'
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
 $ceapRegFormId = $row['lppp_reg_form_id'];
 
 $updateTimeQuery = "UPDATE lppp_temporary_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ?, updated_by = ? WHERE lppp_reg_form_id = ?";
 $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
 mysqli_stmt_bind_param($stmtTimeUpdate, "siiisi", $interviewDate, $interview_hour, $interview_minutes, $interview_ampm, $adminUsername, $ceapRegFormId);
 mysqli_stmt_execute($stmtTimeUpdate);
 
 // Update the status to 'interview'
 $statusUpdateQuery = "UPDATE lppp_temporary_account SET status = 'interview' WHERE lppp_reg_form_id = ?";
 $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
 mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceapRegFormId);
 mysqli_stmt_execute($stmtStatusUpdate);
 
 $updateCount++;
 
 }
 
 // Redirect to prevent form resubmission
 header("Location: " . $_SERVER['REQUEST_URI']);
 exit();
 }
 }
?>