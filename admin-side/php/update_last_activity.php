<?php
session_start();

date_default_timezone_set('Asia/Manila');

// Get the current time in 'H:i:s' format
$current_time = date("H:i:s");

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
        echo "SQL Error: " . $stmt->error;
    }
    
    $stmt->close();

 // Echo the current time instead of a success message
 echo $current_time;
 echo "Employee ID: " . $_SESSION['username'];
}

?>