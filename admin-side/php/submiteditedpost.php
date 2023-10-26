<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config_iskolarosa_db.php';

    $createPostId = $_POST['create_post_id'];
    $postTitle = $_POST['post_title'];
    $tag = $_POST['tag'];
    $postDescription = $_POST['post_description'];
    $scheduledAt = $_POST['post_schedule_at'];

    // Handle file upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
        $targetDirectory = '../../uploaded-img/';
        $targetFile = $targetDirectory . basename($_FILES['post_image']['name']);

        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $targetFile)) {
            // File upload successful, update the database with the new file path
            $sql = "UPDATE create_post SET post_title=?, tag=?, post_description=?, post_image_path=?, post_schedule_at=? WHERE create_post_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $postTitle, $tag, $postDescription, $targetFile, $scheduledAt, $createPostId);
        } else {
            // File upload failed
            http_response_code(500);
            echo "Error uploading the file.";
        }
    } else {
        // No file was uploaded, update the database without changing the file path
        $sql = "UPDATE create_post SET post_title=?, tag=?, post_description=?, post_schedule_at=? WHERE create_post_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $postTitle, $tag, $postDescription, $scheduledAt, $createPostId);
    }

    if ($stmt->execute()) {
        // Update successful
        http_response_code(200);
        echo 'Post updated successfully.';
    } else {
        // Error occurred while updating
        http_response_code(500);
        $error_message = "Error updating post. Error: " . $stmt->error;
        error_log($error_message);
        echo $error_message;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
