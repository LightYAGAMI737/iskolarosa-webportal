<?php

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Connect to the database (replace the placeholders with actual values)
    include 'config_iskolarosa_db.php';
    // Update the last_activity timestamp in the employee_list table
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE employee_list SET last_activity = CURRENT_TIMESTAMP WHERE employee_id_no = '$user_id'");
}

include 'config_roles.php'; // Include the roles and permissions configuration

function hasPermission($role, $permission) {
    global $roles;

    if (isset($roles[$role]) && in_array($permission, $roles[$role])) {
        return true;
    }

    return false;
}

?>