<?php
session_start();

date_default_timezone_set('Asia/Manila');

// Get the current time in 'Y-m-d H:i:s' format
$current_time = date("Y-m-d H:i:s");

// Check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database (replace the placeholders with actual values)
    include 'config_iskolarosa_db.php';

    // Get the username from the session
    $username = $_SESSION['username'];

    // Update the last_activity column with the current time
    $stmt = $conn->prepare("UPDATE employee_list SET last_activity = ? WHERE username = ?");
    $stmt->bind_param("ss", $current_time, $username);
    $stmt->execute();
    if ($stmt->errno) {
        echo json_encode(["error" => "SQL Error: " . $stmt->error]);
    } else {
        // Echo a JSON response with the current time and username
        echo json_encode(["current_time" => $current_time, "username" => $_SESSION['username']]);
    }
    
    $stmt->close();
}
?>
