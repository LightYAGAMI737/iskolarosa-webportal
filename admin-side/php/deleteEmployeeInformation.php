<?php
// Include your database connection file
include '../php/config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $employeeId = $_POST['employeeId'];

    // Delete the employee data from the database
    $sql = "DELETE FROM employee_list WHERE employee_id_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $employeeId);

    if ($stmt->execute()) {
        // Data deleted successfully
        echo 'Data deleted successfully!';
    } else {
        // Error occurred while deleting data
        echo 'Error: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
