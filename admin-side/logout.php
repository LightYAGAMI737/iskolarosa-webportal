<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Connect to the database (replace the placeholders with actual values)
    include './php/config_iskolarosa_db.php';

    // Get the admin username from the session
    $employee_username = $_SESSION['username'];

    // Define the action as a logout
    $action = "Logged out";

    // Insert a new log entry into the admin_logs table
    $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $employee_username, $action);
    $stmt->execute();

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