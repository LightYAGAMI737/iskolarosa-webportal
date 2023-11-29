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

    // Check if the user is already logged in on another device
    $existingSession = mysqli_query($conn, "SELECT * FROM employee_list WHERE username = '$username' AND logged_in = 1");
    
    if (mysqli_num_rows($existingSession) > 0) {
        // User is already logged in on another device
        $error = "User is already logged in on another device.";
        header('Location: ../admin_index.php?error=' . urlencode($error));
        exit();
    }

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

                // Check if the user is already logged in
                if ($employee['logged_in'] == 1) {
                    // User is already logged in on another device
                    $error = "User is already logged in on another device.";
                    header('Location: ../admin_index.php?error=' . urlencode($error));
                    exit();
                }

                // Update the logged_in flag in the employee_list table
                mysqli_query($conn, "UPDATE employee_list SET logged_in = 1 WHERE username = '$username'");

                $_SESSION["user_id"] = $employee["employee_id_no"];
                $_SESSION["user_department"] = $employee["role_id"];
                $_SESSION['username'] = $employee['username'];
                $_SESSION['role'] = $employee['role_id']; // Set the user's role in the session

                // Log the successful login action
                $employee_username = $employee['username'];
                $action = "Logged in";

                // Insert a new log entry into the employee_logs table
                $sql = "INSERT INTO employee_logs (employee_username, action) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $employee_username, $action);
                $stmt->execute();

                // Check the role_id and redirect accordingly
                if ($employee['role_id'] === 1 || $employee['role_id'] === 2) {
                    header('Location: ../dashboard_charts.php');
                } elseif ($employee['role_id'] === 3) {
                    header('Location: ../dashboard_charts.php');
                } else {
                    // Redirect to the dashboard or desired page after successful login
                    $error = 'Invalid role.';
                    exit();
                }
                exit();
            } else {
                // Invalid password
                $error = 'Incorrect username or password.';
                header('Location: ../admin_index.php?error=' . urlencode($error));
                exit();
            }
        } else {
            // Inactive account
            $error = 'Inactive account. Please contact the Head Administrator.';
            header('Location: ../admin_index.php?error=' . urlencode($error));
            exit();
        }
    } else {
        // Username not found in the database
        $error = "Invalid username or password.";
        header('Location: ../admin_index.php?error=' . urlencode($error));
        exit();
    }
}
?>
