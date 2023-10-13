<?php
   include '../../admin-side/php/config_iskolarosa_db.php';

// Get the user input for guardian's first name and last name
$guardian_firstname = $_POST['guardian_firstname'];
$guardian_lastname = $_POST['guardian_lastname'];

// Query the database to check for duplicates
$query = "SELECT ceap_reg_form_id FROM ceap_reg_form WHERE guardian_firstname = ? AND guardian_lastname = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $guardian_firstname, $guardian_lastname);
$stmt->execute();
$result = $stmt->get_result();

// Check if any rows were returned
if ($result->num_rows > 0) {
    echo 'duplicate';
} else {
    echo 'not_duplicate';
}

// Close the database connection
$stmt->close();
$conn->close();
?>
