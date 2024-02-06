<?php
// Include your database connection code here
include '../../admin-side/php/config_iskolarosa_db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $userProvidedPassword = $_POST['oldPassword'];

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT hashed_password FROM temporary_account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDatabase);
    $stmt->fetch();

    // Verify Password
    if (password_verify($userProvidedPassword, $hashedPasswordFromDatabase)) {
        echo 'success'; // Passwords match
    } else {
        // Log error to console
        echo '<script>console.error("Error: Passwords do not match");</script>';
        echo 'error'; // Passwords do not match
    }

    // Close the statement
    $stmt->close();
}
?>
