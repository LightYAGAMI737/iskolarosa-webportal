<?php
include 'config_iskolarosa_db.php';

// Replace with the user's ID

$sql = "SELECT * FROM status WHERE temporary_account_id = ?";
$result = $conn->query($sql);

$statusUpdates = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusUpdates[] = $row;
    }
}

$conn->close();
?>
