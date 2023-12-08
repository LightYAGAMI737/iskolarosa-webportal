<?php
include 'config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $status = isset($_POST["status"]) ? $_POST["status"] : '';
    $applicantId = isset($_POST["id"]) ? $_POST["id"] : '';
    
    // Validate and sanitize the score
    $applicantScore = isset($_POST["applicantScore"]) ? filter_var($_POST["applicantScore"], FILTER_VALIDATE_INT) : null;

    // Additional validation if needed

    // Update the status and score in the database using prepared statements
    $updateQuery = "UPDATE lppp_temporary_account SET status = ?, applicant_score = ? WHERE lppp_reg_form_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sii", $status, $applicantScore, $applicantId);

    if ($stmt->execute()) {
        $response = 'success'; // Update wplicantScore);
    } else {
        $response = 'error'; // Update failed
    }
    echo $response; // Send the response back to the JavaScript code
}

mysqli_close($conn);
?>
