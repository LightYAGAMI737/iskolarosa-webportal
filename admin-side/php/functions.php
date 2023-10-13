<?php
include 'config_roles.php'; // Include the roles and permissions configuration
function hasPermission($role, $permission) {
    global $roles;

    if (isset($roles[$role]) && in_array($permission, $roles[$role])) {
        return true;
    }

    return false;
}
?>