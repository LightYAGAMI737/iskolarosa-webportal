<?php
session_start();

date_default_timezone_set('Asia/Manila');

// Check if the user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database (replace the placeholders with actual values)
    include 'config_iskolarosa_db.php';

    // Get the username from the session
    $username = $_SESSION['username'];
        $stmt = $conn->prepare("UPDATE employee_list SET last_activity = CURRENT_TIMESTAMP WHERE employee_id_no = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
        
          // Returning a success message (not necessary if you don't need to return anything)
    echo json_encode(['success' => true]);
}
?>