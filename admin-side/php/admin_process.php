<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Connect to the database (replace the placeholders with actual values)
    include '../php/config_iskolarosa_db.php';

    // Get the submitted username and password
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    // Prepare the SQL query to retrieve the employee data based on the username and account_status
    $sql = "SELECT * FROM employee_list WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the username exists in the database
    if ($result->num_rows === 1) {
        // Fetch the employee data
        $employee = $result->fetch_assoc();

        // Check account_status
        if ($employee['account_status'] == '1') {
            // Verify the password
            if (password_verify($password, $employee["password"])) {
                // Password is correct, store user data in session
                $_SESSION["user_id"] = $employee["employee_id_no"];
                $_SESSION["user_department"] = $employee["role_id"];
                $_SESSION['username'] = $employee['username'];
                $_SESSION['role'] = $employee['role_id']; // Set the user's role in the session

                // Log the successful login action
                $employee_username = $employee['username']; // Get the admin username from the session
                $action = "Logged in"; // Define the action as a successful login

                // Insert a new log entry into the admin_logs table using prepared statement
                $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $employee_username, $action);
                $stmt->execute();

                // Check the role_id and redirect accordingly
                if ($employee['role_id'] === 1 || $employee['role_id'] === 2) {
                    header('Location: ../dashboard_charts.php');
                } elseif ($employee['role_id'] === 3) {
                    header('Location: ../head-admin/dashboard_charts.php');
                } else {
                    // Redirect to the dashboard or desired page after successful login
                    $error = 'Invalid role.';
                    exit();
                }
                exit(); // Make sure to exit after header redirects
            } else {
                // Invalid password
                $error = 'Incorrect username or password.';
                header('Location: ../admin_index.php?error=' . urlencode($error)); // Redirect to admin_index.php with an error parameter
                exit();
            }
        } else {
            // Inactive account
            $error = 'Inactive account. Please contact the Head Administrator.';
            header('Location: ../admin_index.php?error=' . urlencode($error)); // Redirect to admin_index.php with an error parameter
            exit();
        }
    } else {
        // Username not found in the database
        $error = "Invalid username or password.";
        header('Location: ../admin_index.php?error=' . urlencode($error)); // Redirect to admin_index.php with an error parameter
        exit();
    }
}
?>
