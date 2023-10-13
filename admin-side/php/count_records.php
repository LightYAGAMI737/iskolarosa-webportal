<?php
include 'config_iskolarosa_db.php';

// Execute the SQL query to count records
$sql = "SELECT COUNT(*) AS count FROM temporary_account AS t
        INNER JOIN ceap_reg_form AS p ON t.ceap_reg_form_id = p.ceap_reg_form_id
        WHERE t.status = 'Verified' AND UPPER(p.barangay) = 'APLAYA'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $count = $row["count"];
    echo $count;
} else {
    echo "0";
}

$conn->close();
?>
