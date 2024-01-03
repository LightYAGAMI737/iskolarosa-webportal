<?php
// Start the session
session_start();

date_default_timezone_set('Asia/Manila');

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
                            $current_time = date("H:i:s");

                            // Check if there's an active session based on the user's session data
                            if (
                                isset($employee['session_id']) && // have isset session_id
                                $employee['session_id'] !== session_id() && // different browser
                                strtotime($employee['last_activity']) > strtotime($current_time) - 300
                            ) { // last activity is less than 5 min
                                // User is already logged in on another device
                                $error = "User is already logged in on another device.";
                                header('Location: ../admin_index.php?error=' . urlencode($error));
                                exit();
                            } elseif (
                                isset($employee['session_id']) && // have isset session_id
                                $employee['session_id'] !== session_id() && // different browser
                                strtotime($employee['last_activity']) < strtotime($current_time) - 300
                            ) { // last activity is more than 5 min
                                // Update the session_id and last_activity in the employee_list table
                                $session_id = session_id();
                                $stmt = $conn->prepare("UPDATE employee_list SET session_id = ?, last_activity = ? WHERE username = ?");
                                $stmt->bind_param("sss", $session_id, $current_time, $username);
                                $stmt->execute();
                                $stmt->close();
                            }

                            // Update the session_id and last_activity in the employee_list table
                            $session_id = session_id();
                            $stmt = $conn->prepare("UPDATE employee_list SET session_id = ?, last_activity = ? WHERE username = ?");
                            $stmt->bind_param("sss", $session_id, $current_time, $username);
                            $stmt->execute();
                            $stmt->close();
                        
                $_SESSION["user_id"] = $employee["employee_id_no"];
                $_SESSION["user_department"] = $employee["role_id"];
                $_SESSION['username'] = $employee['username'];
                $_SESSION['role'] = $employee['role_id']; // Set the user's role in the session

                date_default_timezone_set('Asia/Manila');
                $currentTimeLog = date('Y-m-d H:i:s'); // Assuming you want to include both date and time

                // Log the successful login action
                $employee_username = $employee['username'];
                $action = "Logged in";

                // Insert a new log entry into the employee_logs table
                $sql = "INSERT INTO employee_logs (employee_username, action, timestamp) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $employee_username, $action, $currentTimeLog);
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