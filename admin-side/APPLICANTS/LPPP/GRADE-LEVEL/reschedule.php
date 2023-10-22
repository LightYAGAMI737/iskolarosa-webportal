<?php
include '../../php/config_iskolarosa_db.php';

// Check if the reschedule form is submitted
if (isset($_POST['rescheduleBtn'])) {
    $limit = $_POST['limit']; // Number of applicants to reschedule
    $rescheduleDate = $_POST['interview_date']; // Reschedule date

    // Get the current date
    $currentDate = date('Y-m-d');

    // Query to select the applicants to be rescheduled
    $rescheduleQuery = "SELECT t.*
                        FROM lppp_temporary_account t
                        INNER JOIN lppp_reg_form p
                        ON p.lppp_reg_form_id = t.lppp_reg_form_id
                        WHERE p.barangay = 'APLAYA' AND t.status = 'Interview' AND t.interview_date = '$currentDate'
                        LIMIT $limit";

    $rescheduleResult = mysqli_query($conn, $rescheduleQuery);

    // Update the interview date for each applicant
    while ($row = mysqli_fetch_assoc($rescheduleResult)) {
        $tempAccountId = $row['lppp_temporary_account_id'];

        // Update the interview date in the lppp_temporary_account table
        $updateQuery = "UPDATE lppp_temporary_account SET interview_date = '$rescheduleDate' WHERE lppp_temporary_account_id = $tempAccountId";
        mysqli_query($conn, $updateQuery);
    }

    // Display success message or perform any additional actions
    echo "Applicants rescheduled successfully.";

    // Redirect to another page
    header("Location: ../LPPP/lppp_list_interview.php");
    exit();
}
?>
