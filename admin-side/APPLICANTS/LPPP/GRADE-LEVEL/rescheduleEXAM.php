<?php
// Include configuration and functions
session_start();
include '../../php/config_iskolarosa_db.php';

// Check if the reschedule form is submitted
if (isset($_POST['rescheduleBtn'])) {
   // Process and update exam dates
   $examDate = $_POST['exam_date'];
   $exam_hour = $_POST['exam_hour']; 
   $exam_minutes = $_POST['exam_minutes']; 
   $exam_ampm = $_POST['exam_ampm'];
   $limit = $_POST['limit'];

   if (!empty($examDate) && !empty($exam_hour) && !empty($exam_minutes) && !empty($exam_ampm) && !empty($limit)) {
       $examDate = mysqli_real_escape_string($conn, $examDate);
       $limit = intval($limit);

       $qualifiedQuery = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, p.lppp_reg_form_id
       FROM lppp_reg_form p
       INNER JOIN lppp_temporary_account t ON p.lppp_reg_form_id = t.lppp_reg_form_id
       WHERE t.status = 'exam'
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

           $updateTimeQuery = "UPDATE lppp_temporary_account SET exam_date = ?, exam_hour = ?, exam_minute = ?, exam_period = ?, updated_by = ? WHERE lppp_reg_form_id = ?";
           $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
           mysqli_stmt_bind_param($stmtTimeUpdate, "siiisi", $examDate, $exam_hour, $exam_minutes, $exam_ampm, $adminUsername, $ceapRegFormId);
           mysqli_stmt_execute($stmtTimeUpdate);

           // Update the status to 'exam'
           $statusUpdateQuery = "UPDATE lppp_temporary_account SET status = 'exam' WHERE lppp_reg_form_id = ?";
           $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
           mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceapRegFormId);
           mysqli_stmt_execute($stmtStatusUpdate);

           $updateCount++;
       }

       // Display success message or perform any additional actions
       echo "Applicants rescheduled successfully.";

       // Redirect to another page
       header("Location: lppp_list_exam.php");
       exit();
   }
}
?>
