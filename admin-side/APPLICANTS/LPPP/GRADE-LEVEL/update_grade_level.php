<?php
include '../../../php/config_iskolarosa_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $ceapRegFormId = $_POST['id'];
    $grade_level = $_POST['grade_level'];

    // Define the update query with placeholders
    $updateQuery = "UPDATE lppp_reg_form SET grade_level = ? WHERE lppp_reg_form_id = ?";
    
    // Prepare and execute the update statement
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $grade_level, $ceapRegFormId);
    $success = mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if ($success) {
        // Redirect back to the original page after updating
         // Close the statement
         mysqli_stmt_close($stmt);
        
         // Close the connection
         $conn->close();
         
        echo '<script>window.history.go(-2);</script>';
        exit();
    } else {
        // Handle the error (you might want to display an error message)
        echo "Update failed: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the connection
$conn->close();
?>
