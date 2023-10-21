<?php
include 'config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];
    $applicantId = $_POST["id"];
    
    // Update the status in the database
    $updateQuery = "UPDATE lppp_temporary_account SET status = ? WHERE lppp_reg_form_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $status, $applicantId);
    
    if ($stmt->execute()) {
        $response = 'success'; // Update was successful
    } else {
        $response = 'error'; // Update failed
    }

    echo $response; // Send the response back to the JavaScript code
}

mysqli_close($conn);
?>
