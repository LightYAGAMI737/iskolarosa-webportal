<?php
include 'config_iskolarosa_db.php';

// Query to fetch the toggle_value from the ceap_configuration table
$sql = 'SELECT toggle_value FROM lppp_configuration ORDER BY id DESC LIMIT 1' ;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $toggleValue = $row['toggle_value'];

    echo $toggleValue;
} else {
    echo '0'; // Default value if no result found
}

$conn->close();
?>