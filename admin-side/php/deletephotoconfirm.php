<?php
// Include your database connection configuration
include 'config_iskolarosa_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the photo ID to be updated from the AJAX request
    $createPostId = isset($_POST['create_post_id']) ? $_POST['create_post_id'] : null;


    if ($createPostId) {
        // Perform the update in your database (replace 'create_post' with your actual table name)
        $sql = "UPDATE create_post SET post_image_path = '' WHERE create_post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $createPostId);

        if ($stmt->execute()) {
            // Update successful
            http_response_code(200); // OK
        } else {
            // Error occurred while updating
            http_response_code(500); // Internal Server Error
            $error_message = "Error updating photo in create_post table. Error: " . $stmt->error;
            error_log($error_message);

            // Output the error message to the response (you can remove this in production)
            echo $error_message;
        }

        $stmt->close();
    } else {
        // Invalid or missing photo ID
        http_response_code(400); // Bad Request
        error_log("Invalid or missing photo ID in the request.");
    }
} else {
    // Handle non-POST requests if necessary
    http_response_code(405); // Method Not Allowed
    error_log("Non-POST request received for deletephotoconfirm.php.");
}

// Close your database connection
$conn->close();


?>
