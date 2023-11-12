<?php
@include 'config_iskolarosa_db.php';

// Check if the reschedule form is submitted
if (isset($_POST['rescheduleBtn'])) {
    $limit = $_POST['limit']; // Number of applicants to reschedule
    $rescheduleDate = $_POST['interview_date']; // Reschedule date

    // Get the current date
    $currentDate = date('Y-m-d');

    // Query to select the applicants to be rescheduled
    $rescheduleQuery = "SELECT *
                        FROM ceap_personal_account
                        WHERE barangay = 'APLAYA' AND status = 'interview' AND interview_date = '$currentDate'
                        LIMIT $limit";

    $rescheduleResult = mysqli_query($conn, $rescheduleQuery);

    // Update the interview date for each applicant
    while ($row = mysqli_fetch_assoc($rescheduleResult)) {
        $tempAccountId = $row['ceap_personal_account_id'];

        // Update the interview date in the temporary_account table
        $updateQuery = "UPDATE ceap_personal_account SET interview_date = '$rescheduleDate' WHERE ceap_personal_account_id = $tempAccountId";
        mysqli_query($conn, $updateQuery);
    }

    // Display success message or perform any additional actions
    echo "Applicants rescheduled successfully.";

    // Redirect to another page
    header("Location: ../APPLICANTS/BARANGAY/APLAYA/old_ceap_list_interview.php");
    exit();
}
?>
