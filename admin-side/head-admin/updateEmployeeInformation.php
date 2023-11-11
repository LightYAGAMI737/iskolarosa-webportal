<?php
// Include your database connection file
include '../php/config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $employeeId = $_POST['employeeId'];
    $username = $_POST['username'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $contactNumber = $_POST['contactNumber'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $accountStatus = $_POST['accountStatus'];

   // Update the employee data in the database
$sql = "UPDATE employee_list SET 
last_name = ?, 
first_name = ?, 
contact_number = ?, 
email = ?, 
role_id = ?, 
account_status = ?
WHERE employee_id_no = ?";

$stmt = $conn->prepare($sql);

// Bind parameters, excluding employee_id_no
$stmt->bind_param(
"sssssss",  // Change the type specifier to match the types of your parameters
$lastName,
$firstName,
$contactNumber,
$email,
$department,
$accountStatus,
$employeeId
);

if ($stmt->execute()) {
// Data updated successfully
echo 'Data updated successfully!';
} else {
// Error occurred while updating data
echo 'Error: ' . $stmt->error;
}

// Close the statement
$stmt->close();
}

// Close the database connection
$conn->close();
?>
