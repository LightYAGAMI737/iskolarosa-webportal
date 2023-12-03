<?php
session_start();

// Check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database (replace the placeholders with actual values)
    include 'config_iskolarosa_db.php';

    // Get the user_id from the session
    $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE employee_list SET last_activity = CURRENT_TIMESTAMP WHERE employee_id_no = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();
}
?>