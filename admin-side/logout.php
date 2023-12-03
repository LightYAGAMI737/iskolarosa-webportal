<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Connect to the database (replace the placeholders with actual values)
    include './php/config_iskolarosa_db.php';

    // Get the username from the session
    $username = $_SESSION['username'];

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
