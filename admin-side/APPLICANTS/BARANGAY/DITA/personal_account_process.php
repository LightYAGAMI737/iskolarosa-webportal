
<?php
// Your database connection details
include '../../../php/config_iskolarosa_db.php';

// Start a transaction to group the operations
$conn->begin_transaction();

try {
    // Retrieve data from ceap_reg_form and temporary_account tables
    $sql = "SELECT * FROM ceap_reg_form AS p
            INNER JOIN temporary_account AS t ON p.ceap_reg_form_id = t.ceap_reg_form_id
            WHERE t.status = 'Grantee'"; // Modify the condition as per your requirements

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // After successful processing, update the status in the temporary_account table
        $update_sql = "UPDATE temporary_account SET is_grantee = 1 WHERE status = 'Grantee'";
        
        if ($conn->query($update_sql) === TRUE) {
            // Commit the transaction if everything is successful
            $conn->commit();
        } else {
            throw new Exception("Error updating data in temporary_account table: " . $conn->error);
        }
    } 
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}
?>
