<?php
// Include database configuration file
include '../../admin-side/php/config_iskolarosa_db.php';

// Start session
session_start();

// Check if the session is set
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query to check if the applicant is a grantee
    $sql = "SELECT is_grantee FROM temporary_account WHERE username = ? AND is_grantee = 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Check if the applicant is a grantee
    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Applicant is a grantee, perform logout
        // Destroy session
        session_unset();
        session_destroy();
        // Redirect to logout page
        header("Location: logout.php");
        exit();
    } else {
        // Applicant is not a grantee
        echo "Applicant is not a grantee.";
    }
} else {
    // Session not set, redirect to login page
    header("Location: index.php");
    exit();
}
?>
