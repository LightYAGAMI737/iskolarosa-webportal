<?php
include '../../admin-side/php/config_iskolarosa_db.php';
session_start();

if (!isset($_SESSION['control_number'])) {
    // You need to log in to access this page.
    echo 'You need to log in to access this page.';
    // OR
    header("Location: index.php"); // Redirect to the login page
    exit();
} else {
    // Set the ceap_personal_account_id session variable
    $control_number = $_SESSION['control_number'];
    
    // Query the database to get the corresponding ceap_personal_account_id
    $selectPersonalAccountIdSql = "SELECT ceap_personal_account_id FROM ceap_personal_account WHERE control_number = ?";
    $stmtPersonalAccountId = mysqli_prepare($conn, $selectPersonalAccountIdSql);
    mysqli_stmt_bind_param($stmtPersonalAccountId, 's', $control_number);
    mysqli_stmt_execute($stmtPersonalAccountId);
    mysqli_stmt_bind_result($stmtPersonalAccountId, $ceap_personal_account_id);
    
    if (mysqli_stmt_fetch($stmtPersonalAccountId)) {
        $_SESSION['ceap_personal_account_id'] = $ceap_personal_account_id;
    } else {
        // Handle the case where ceap_personal_account_id is not found in the database
        // You may want to redirect or display an error message
        echo 'error';
        echo 'Error: ceap_personal_account_id not found.';
        exit();
    }
    
    mysqli_stmt_close($stmtPersonalAccountId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control_number = $_SESSION['control_number'];

    // Define an array of editable fields
    $editableFields = array(
        'civil_status',
        'religion',
        'contact_number',
        'active_email_address',
        'house_number',
        'barangay',
        'guardian_firstname',
        'guardian_last',
        'guardian_occupation',
        'guardian_relationship',
        'guardian_monthly_income',
        'guardian_annual_income',
        'course_enrolled',
        'no_of_units',
        'year_level',
        'current_semester',
        'graduating',
        'school_name',
        'school_type',
        'expected_year_of_graduation',
        'school_address',
        'student_id_no'
    );

    // Define an array to store the fields to update and their new values
    $updates = array();

    // Define an array to store the old and new values for auditing
    $auditValues = array();

    // Iterate through the posted data and store fields and new values in the $updates array
    foreach ($_POST as $field => $value) {
        // Check if the field is in the list of editable fields
        if (in_array($field, $editableFields) && !in_array($field, ['update_all_info', 'ceap_reg_form_id'])) {
            // Before updating, retrieve the old value for auditing
            $selectOldValueSql = "SELECT $field FROM ceap_personal_account WHERE control_number = ?";
            $stmtOldValue = mysqli_prepare($conn, $selectOldValueSql);
            mysqli_stmt_bind_param($stmtOldValue, 's', $control_number);
            mysqli_stmt_execute($stmtOldValue);
            mysqli_stmt_bind_result($stmtOldValue, $oldValue);
            mysqli_stmt_fetch($stmtOldValue);
            mysqli_stmt_close($stmtOldValue);

            // Only update if the value has changed
            if ($oldValue !== $value) {
                $updates[$field] = $value;
                
                // Store old and new values for auditing
                $auditValues[] = [
                    'field' => $field,
                    'oldValue' => $oldValue,
                    'newValue' => $value,
                ];
            }
        }
    }

    // Check if any fields have changed before updating
    if (!empty($updates)) {
        // Prepare and execute the SQL query to update the ceap_personal_account table
        $updateSql = "UPDATE ceap_personal_account SET ";
        foreach ($updates as $field => $value) {
            $updateSql .= "$field = ?, ";
        }
        // Include ceap_personal_account_id in the update query
        $updateSql .= "ceap_personal_account_id = ceap_personal_account_id, "; // This keeps the value unchanged
        // Remove the trailing comma and space
        $updateSql = rtrim($updateSql, ', ');
        $updateSql .= " WHERE control_number = ?";
        
        $stmt = mysqli_prepare($conn, $updateSql);

        // Bind the parameters dynamically based on the number of fields to update
        $bindParams = str_repeat('s', count($updates)) . 's'; // 'ssss...' based on the number of fields
        $bindValues = array_values($updates);
        $bindValues[] = $control_number; // Append the control_number to the end
        
        mysqli_stmt_bind_param($stmt, $bindParams, ...$bindValues);
        
        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
              // Update successful.
    // Update the status to "In Progress"
    $updateStatusSql = "UPDATE ceap_personal_account SET status = 'In Progress', updated_info = '1' WHERE control_number = ?";
    $stmtStatus = mysqli_prepare($conn, $updateStatusSql);
    mysqli_stmt_bind_param($stmtStatus, 's', $control_number);
    
    if (mysqli_stmt_execute($stmtStatus)) {
        echo 'Status updated to "In Progress".';
    } else {
        echo 'Status update failed: ' . mysqli_error($conn);
    }
    mysqli_stmt_close($stmtStatus);

            // Log old and new values to the audit table for fields that have changed
            foreach ($auditValues as $audit) {
                $field = $audit['field'];
                $oldValue = $audit['oldValue'];
                $newValue = $audit['newValue'];
            
                // Get the correct value of ceap_personal_account_id from the session
                $ceap_personal_account_id = $_SESSION['ceap_personal_account_id'];
            
                // Get the current date and time
                $currentDate = date('Y-m-d H:i:s');
            
                // Insert the old and new values into the audit table, including the UpdatedOn field
                $insertAuditSql = "INSERT INTO ceap_applicantupdates (ceap_personal_account_id, FieldToUpdate, OldValue, NewValue, UpdatedOn) VALUES (?, ?, ?, ?, ?)";
                $stmtAudit = mysqli_prepare($conn, $insertAuditSql);
                mysqli_stmt_bind_param($stmtAudit, 'issss', $ceap_personal_account_id, $field, $oldValue, $newValue, $currentDate);
                mysqli_stmt_execute($stmtAudit);
                mysqli_stmt_close($stmtAudit);
            }
        } else {
            echo 'error' . mysqli_error($conn);
        }
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // No fields have changed, display a message or take appropriate action
        echo 'No changes to update.';
    }
}
?>
