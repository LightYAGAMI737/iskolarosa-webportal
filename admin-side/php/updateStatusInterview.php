<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process and update interview dates
    $interviewDate = $_POST['interview_date'];
    $interview_hours = $_POST['interview_hours'];
    $interview_minutes = $_POST['interview_minutes'];
    $interview_ampm = $_POST['interview_ampm'];
    $limit = $_POST['limit'];

    include 'config_iskolarosa_db.php';

    if (!empty($interviewDate) && !empty($interview_hours) && !empty($interview_minutes) && !empty($interview_ampm) && !empty($limit)) {
        $interviewDate = mysqli_real_escape_string($conn, $interviewDate);
        $limit = intval($limit);
    
        $qualifiedQuery = "SELECT t.*, UPPER(p.first_name) AS first_name, UPPER(p.last_name) AS last_name, UPPER(p.barangay) AS barangay, p.control_number, p.date_of_birth, p.ceap_reg_form_id
        FROM ceap_reg_form p
        INNER JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
        WHERE t.status = 'Verified' AND p.barangay = 'aplaya'
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

            $ceapRegFormId = $row['ceap_reg_form_id'];
            $employeeUsername = $_SESSION["username"];

            // Retrieve the previous status from the database
            $prevStatusQuery = "SELECT status FROM temporary_account WHERE ceap_reg_form_id = ?";
            $stmtPrev = $conn->prepare($prevStatusQuery);
            $stmtPrev->bind_param("i", $ceapRegFormId);
            $stmtPrev->execute();
            $stmtPrev->bind_result($previousStatus);
            $stmtPrev->fetch();
            $stmtPrev->close();

            $updateTimeQuery = "UPDATE temporary_account SET interview_date = ?, interview_hour = ?, interview_minute = ?, interview_period = ? WHERE ceap_reg_form_id = ?";
            $stmtTimeUpdate = mysqli_prepare($conn, $updateTimeQuery);
            mysqli_stmt_bind_param($stmtTimeUpdate, "siisi", $interviewDate, $interview_hours, $interview_minutes, $interview_ampm, $ceapRegFormId);
            mysqli_stmt_execute($stmtTimeUpdate);

            // Update the status to 'interview'
            $statusUpdateQuery = "UPDATE temporary_account SET status = 'interview' WHERE ceap_reg_form_id = ?";
            $stmtStatusUpdate = mysqli_prepare($conn, $statusUpdateQuery);
            mysqli_stmt_bind_param($stmtStatusUpdate, "i", $ceapRegFormId);
            mysqli_stmt_execute($stmtStatusUpdate);

            mysqli_stmt_close($stmtTimeUpdate);
            mysqli_stmt_close($stmtStatusUpdate);

            if ($stmt->affected_rows > 0) {
                // Retrieve the employee_logs_id based on the employee username
                $employeeIdQuery = "SELECT employee_logs_id FROM employee_logs WHERE employee_username = ?";
                $stmtEmployeeId = $conn->prepare($employeeIdQuery);
                $stmtEmployeeId->bind_param("s", $employeeUsername);
                $stmtEmployeeId->execute();
                $stmtEmployeeId->bind_result($employeeLogsId);
                $stmtEmployeeId->fetch();
                $stmtEmployeeId->close();

                $status = 'interview'; // Assign a value to $status before the insert query
                // Log the status change in the applicant_status_logs table using a prepared statement
                $logQuery = "INSERT INTO applicant_status_logs (previous_status, updated_status, ceap_reg_form_id, employee_logs_id) VALUES (?, ?, ?, ?)";
                $stmtLog = $conn->prepare($logQuery);
                $stmtLog->bind_param("ssii", $previousStatus, $status, $ceapRegFormId, $employeeLogsId);
                $stmtLog->execute();

                echo 'success';
            } else {
                echo 'error';
            }
            $updateCount++;
        }
    }
}
?>
