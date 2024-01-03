<?php
session_start();

date_default_timezone_set('Asia/Manila');
$currentTimeLog = date('Y-m-d H:i:s');

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Connect to the database (replace the placeholders with actual values)
    include './php/config_iskolarosa_db.php';

    $username = $_SESSION['username'];
    $action = "Logged out";

    // Insert a new log entry into the employee_logs table with timestamp
    $sql = "INSERT INTO employee_logs (employee_username, action, timestamp) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $action, $currentTimeLog);
    $stmt->execute();

    // Update the session_id column to null in the employee_list table
    $updateStmt = $conn->prepare("UPDATE employee_list SET session_id = NULL WHERE username = ?");
    $updateStmt->bind_param("s", $username);
    $updateStmt->execute();
    $updateStmt->close();

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after logout
    header("Location: admin_index.php");
    exit();
} else {
    // If the user is not logged in, simply redirect to the login page
    header("Location: admin_index.php");
    exit();
}
?>
