<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Connect to the database (replace the placeholders with actual values)
    include '../php/config_iskolarosa_db.php';

    // Get the username from the session
    $username = $_SESSION['username'];

    // Update the logged_in flag to 0 (not logged in) in the employee_list table
    $updateStmt = $conn->prepare("UPDATE employee_list SET logged_in = 0 WHERE username = ?");
    $updateStmt->bind_param("s", $username);
    $updateStmt->execute();
    $updateStmt->close();

    // Define the action as a logout
    $action = "Logged out";

    // Insert a new log entry into the employee_logs table
    $logStmt = $conn->prepare("INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)");
    $logStmt->bind_param("ss", $username, $action);
    $logStmt->execute();
    $logStmt->close();

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after logout
    header("Location: ../admin_index.php");
    exit();
} else {
    // If the user is not logged in, simply redirect to the login page
    header("Location: ../admin_index.php");
    exit();
}
?>
