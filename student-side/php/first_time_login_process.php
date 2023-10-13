<?php
session_start();

// Assuming you have a database connection established
// Replace "YOUR_HOST", "YOUR_USERNAME", "YOUR_PASSWORD", and "YOUR_DATABASE" with your actual database credentials.
include 'config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Hash the new password before storing it in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE temporary_account SET hashed_password = ?, first_time_login = 0 WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $username);

        if (mysqli_stmt_execute($stmt)) {
            // Password updated successfully, redirect to the main page
            header("Location: ../index.php");
            exit();
        } else {
            // Something went wrong with the database update, handle the error accordingly
            header("Location: ceap_update_password.php?error=DatabaseError");
            exit();
        }
    } else {
        // Passwords do not match, redirect back to the update password page
        header("Location: ceap_update_password.php?error=PasswordMismatch");
        exit();
    }
}
?>
