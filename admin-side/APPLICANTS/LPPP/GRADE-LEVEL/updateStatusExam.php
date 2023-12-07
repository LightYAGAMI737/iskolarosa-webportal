<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include '../../../php/config_iskolarosa_db.php';

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
        
        $updateTimeQuery = "UPDATE lppp_temporary_account SET exam_date = ?, exam_hour = ?, exam_minute = ?, exam_period = ?, updated_by = ? WHERE lppp_reg_form_id = ?";
        $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
        mysqli_stmt_bind_param($stmtTimeUpdate, "siiisi", $interviewDate, $exam_hour, $exam_minutes, $exam_ampm, $adminUsername, $LPPPregFormID);
        mysqli_stmt_execute($stmtTimeUpdate);
        
        // Update the status to 'interview'
        $statusUpdateQuery = "UPDATE lppp_temporary_account SET status = 'exam' WHERE lppp_reg_form_id = ?";
        $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
        mysqli_stmt_bind_param($stmtStatusUpdate, "i", $LPPPregFormID);
        mysqli_stmt_execute($stmtStatusUpdate);
        
        $updateCount++;

        echo 'success';
 
 }
 }
 }
?>