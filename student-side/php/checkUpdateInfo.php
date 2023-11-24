<?php
// Start the session
session_start();

include '../../admin-side/php/config_iskolarosa_db.php';

$control_number = $_SESSION['control_number'];

// Fetch toggle value from ceap_configuration table
$sqlToggle = 'SELECT toggle_value FROM ceap_configuration ORDER BY id DESC LIMIT 1';
$resultToggle = $conn->query($sqlToggle);

// Fetch updated_info from ceap_personal_account table
$sqlInfo = 'SELECT updated_info FROM ceap_personal_account WHERE control_number = ?';
$stmtInfo = mysqli_prepare($conn, $sqlInfo);
mysqli_stmt_bind_param($stmtInfo, 's', $control_number);
mysqli_stmt_execute($stmtInfo);
$resultInfo = mysqli_stmt_get_result($stmtInfo);

// Store results in an associative array
$response = array();

if ($resultToggle->num_rows > 0) {
    $rowToggle = $resultToggle->fetch_assoc();
    $response['toggleValue'] = $rowToggle['toggle_value'];
} else {
    $response['toggleValue'] = '0'; // Default value if no result found
}

if ($resultInfo->num_rows > 0) {
    $rowInfo = $resultInfo->fetch_assoc();
    $response['updatedInfo'] = $rowInfo['updated_info'];
} else {
    $response['updatedInfo'] = '0'; // Default value if no result found
}

// Close the database connection
$conn->close();

// Send the JSON-encoded response
echo json_encode($response);
?>
