<?php
session_start();
include 'config_iskolarosa_db.php';

$response = array(); // Initialize an empty response array

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_posts'])) {
    $selectedPosts = $_POST['selected_posts'];

    // Get the titles of the selected posts for the log entry
    $postTitles = array();
    $queryTitles = "SELECT post_title FROM create_post WHERE create_post_id IN (" . implode(',', array_map('intval', $selectedPosts)) . ")";
    $resultTitles = mysqli_query($conn, $queryTitles);

    while ($row = mysqli_fetch_assoc($resultTitles)) {
        $postTitles[] = $row['post_title'];
    }

    // Log the post deletion action using post titles
    $employee_username = $_SESSION['username']; // Get the employee username from the session
    $action = "Deleted Post(s) with Titles: " . implode(", ", $postTitles); // Define the action as post deletion

    // Insert a new log entry into the employee_logs table
$logQuery = "INSERT INTO employee_logs (employee_username, action, timestamp) VALUES (?, ?, ?)";
$logStmt = mysqli_prepare($conn, $logQuery);

if ($logStmt) {
    // Set the default time zone to 'Asia/Manila'
    date_default_timezone_set('Asia/Manila');

    // Get the current date and time
    $currentDateTime = date('Y-m-d H:i:s');

    mysqli_stmt_bind_param($logStmt, "sss", $employee_username, $action, $currentDateTime);
    mysqli_stmt_execute($logStmt);
    mysqli_stmt_close($logStmt);
}


    // Prepare the query to delete selected posts
    $placeholders = implode(',', array_fill(0, count($selectedPosts), '?'));
    $deleteQuery = "DELETE FROM create_post WHERE create_post_id IN ($placeholders)";

    $stmt = mysqli_prepare($conn, $deleteQuery);
    if ($stmt) {
        // Bind parameters
        $paramType = str_repeat('i', count($selectedPosts));
        mysqli_stmt_bind_param($stmt, $paramType, ...$selectedPosts);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Close the statement
        mysqli_stmt_close($stmt);

        $response['success'] = true; // Set a success flag in the response
        $response['message'] = 'Post(s) deleted successfully'; // Provide a success message
    } else {
        $response['success'] = false; // Set a failure flag in the response
        $response['message'] = 'Error deleting post(s)'; // Provide an error message
    }
}

// Send the JSON response back to the client-side JavaScript
header('Content-Type: application/json');
echo json_encode($response);

// Redirect back to the manage post page (if needed)
// header('Location: ../manage_post.php');
// exit();
?>
    