<?php
session_start(); // Start the session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and use filter_input for input validation
    $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_STRING);
    $post_description = filter_input(INPUT_POST, 'post_description', FILTER_SANITIZE_STRING);
    $tag = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $post_image_path = ""; // Initialize the image path
    $post_schedule_at = isset($_POST['post_schedule_at']) ? $_POST['post_schedule_at'] : null; // Retrieve scheduled date and time if provided

    include '../php/config_iskolarosa_db.php';

    // If an image is uploaded, validate and handle the uploaded image
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploaded-img/'; // Directory where uploaded images will be stored
        $allowedExtensions = ['jpg', 'jpeg']; // Allowed image file extensions
        $maxFileSize = 5 * 1024 * 1024; // Max file size in bytes (5MB)

        // Get the file extension of the uploaded image
        $fileExtension = strtolower(pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION));

        // Check if the file extension is allowed
        if (in_array($fileExtension, $allowedExtensions)) {
            // Check if the file size is within the allowed limit
            if ($_FILES['post_image']['size'] <= $maxFileSize) {
                // Generate a unique file name for the uploaded image
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFilePath = $uploadDir . $newFileName;

                // Move the uploaded image to the designated directory
                if (move_uploaded_file($_FILES['post_image']['tmp_name'], $uploadFilePath)) {
                    $post_image_path = $uploadFilePath; // Update the image path
                } else {
                    echo "Error moving uploaded image.";
                    exit(); // Exit the script
                }
            } else {
                echo "File size exceeds the maximum limit.";
                exit(); // Exit the script
            }
        } else {
            echo "Invalid file type. Only JPG and JPEG images are allowed.";
            exit(); // Exit the script
        }
    }

    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO create_post (post_title, post_description, post_image_path, tag, ";

    if ($post_schedule_at !== null) {
        $query .= "post_schedule_at, ";
    }

    $query .= "post_created_at) VALUES (?, ?, ?, ?, ";

    if ($post_schedule_at !== null) {
        $query .= "?, ";
    }

    $query .= "?)"; // Use placeholders

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Bind parameters to the prepared statement
        if ($post_schedule_at !== null) {
            mysqli_stmt_bind_param($stmt, 'ssssss', $post_title, $post_description, $post_image_path, $tag, $post_schedule_at, $serverCurrentDateTime);
        } else {
            mysqli_stmt_bind_param($stmt, 'sssss', $post_title, $post_description, $post_image_path, $tag, $serverCurrentDateTime);
        }

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Log the post creation action
            $employee_username = $_SESSION['username']; // Get the admin username from the session
            $action = "Created a new post: $post_title"; // Define the action as post creation

            // Insert a new log entry into the employee_logs table
            $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ss', $employee_username, $action);
                mysqli_stmt_execute($stmt);
            }

            header("Location: create_post.php"); // Replace with the actual URL
            exit();
        } else {
            echo "Error creating post: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the prepared statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>