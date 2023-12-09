<?php
include 'config_iskolarosa_db.php';

// Query to fetch the toggle_value from the ceap_configuration table
$sql = 'SELECT toggle_value, start_date FROM ceap_configuration ORDER BY id DESC LIMIT 1';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $toggleValue = $row['toggle_value'];
    $startDate = $row['start_date'];

        if($toggleValue == 1){
            echo '1';
        } elseif ($toggleValue == 0 && $startDate != "0000-00-00") {
            echo '2'; // Default value if no result found
        } else {
            echo '0';
}
}
$conn->close();
?>
