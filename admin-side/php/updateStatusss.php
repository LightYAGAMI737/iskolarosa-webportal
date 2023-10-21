<?php
// Start the session
session_start();
@include 'config_iskolarosa_db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the applicant's ID and new status from the form submission
    $applicantId = $_POST['id'];
    $status = $_POST['status'];

    // Get the logged-in admin's username from the session
    $adminUsername = $_SESSION['username'];

    // Determine the table (personal_account or temporary_account) based on applicant ID
    $tableToUpdate = (strpos($applicantId, 'P') === 0) ? "personal_account" : "temporary_account";

    // Prepare and execute the update query for the respective table
    $sqlUpdate = "UPDATE $tableToUpdate SET status = ?, updated_by = ? WHERE " . ($tableToUpdate === "personal_account" ? "personal_account_id" : "ceap_reg_form_id") . " = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sss", $status, $adminUsername, $applicantId);

    if ($stmtUpdate->execute()) {
        // Update successful, insert the log information into the log_updates table
        $previousStatus = ""; // Store the previous status of the applicant
        $newStatus = $status; // The new status is the one updated

        // Retrieve the previous status of the applicant
        $sqlPreviousStatus = "SELECT status FROM $tableToUpdate WHERE " . ($tableToUpdate === "personal_account" ? "personal_account_id" : "ceap_reg_form_id") . " = ?";
        $stmtPreviousStatus = $conn->prepare($sqlPreviousStatus);
        $stmtPreviousStatus->bind_param("s", $applicantId);
        $stmtPreviousStatus->execute();
        $result = $stmtPreviousStatus->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $previousStatus = $row['status'];
        }

        // // Insert the update information into the log_updates table
        // $sqlInsertLog = "INSERT INTO log_updates (admin_username, applicant_id, previous_status, new_status) VALUES (?, ?, ?, ?)";
        // $stmtInsertLog = $conn->prepare($sqlInsertLog);
        // $stmtInsertLog->bind_param("ssss", $adminUsername, $applicantId, $previousStatus, $newStatus);
        // $stmtInsertLog->execute();

        // echo 'success';
    } else {
        // Update failed
        echo 'error';
    }
}

// Close the database connection
mysqli_close($conn);
?>
