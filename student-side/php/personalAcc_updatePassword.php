<?php
// Include your database connection code here
include '../../admin-side/php/config_iskolarosa_db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $newPassword = $_POST['newPassword'];
   $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

   // Update the hashed password in the database
   // Replace 'your_query_here' with the actual query to update the password
   $updateQuery = "UPDATE ceap_personal_account SET hashed_password = '$hashedNewPassword' WHERE username = '{$_SESSION['username']}'";

   // Execute the update query
   // Replace 'your_database_connection' with your actual database connection variable
   mysqli_query($conn, $updateQuery);

   echo 'Password updated successfully';
}
?>
