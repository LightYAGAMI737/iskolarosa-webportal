<?php
session_start();

// Check if the user is logged in and the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Include necessary files
      include 'admin_process.php';
      
    // Connect to the database (replace the placeholders with actual values)
    include 'config_iskolarosa_db.php';
  
    // Fetch last_activity from the database
    $user_id = $_SESSION['user_id'];
    $result = mysqli_query($conn, "SELECT last_activity FROM employee_list WHERE employee_id_no = '$user_id'");

    if ($row = mysqli_fetch_assoc($result)) {
        $last_activity = strtotime($row['last_activity']);
        $current_time = time();
        
        if ($current_time - $last_activity > 300) { // 5 min seconds in seconds
            // Update the session_id to NULL in the employee_list table
            $updateStmt = $conn->prepare("UPDATE employee_list SET session_id = NULL WHERE employee_id_no = ?");
            $updateStmt->bind_param("s", $user_id);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }
}
?>
