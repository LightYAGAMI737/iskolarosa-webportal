<?php
include 'config_iskolarosa_db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT email FROM employee_list WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not_exists";
    }

    $stmt->close();
}

$conn->close();
?>
