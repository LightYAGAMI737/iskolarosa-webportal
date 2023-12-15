<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming you have a database connection established
// Replace "YOUR_HOST", "YOUR_USERNAME", "YOUR_PASSWORD", and "YOUR_DATABASE" with your actual database credentials.
include '../../admin-side/php/config_iskolarosa_db.php';

// Start the session to access the session variables
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // To prevent SQL injection, use prepared statements
    $sql = "SELECT hashed_password, first_time_login, ceap_password FROM temporary_account WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashed_password, $first_time_login, $ceap_password);

        if (mysqli_stmt_fetch($stmt)) {
            // Check if it's the first-time login
            if ($first_time_login == 1) {
                // Verify the first-time login password based on username
                if ($password === $ceap_password) {
                    mysqli_stmt_close($stmt);

                    // Update the password to the new password
                    // Redirect to update password page
                    $_SESSION['username'] = $username;
                    mysqli_close($conn); // Close the connection before redirecting
                    header("Location: first_time_login.php");
                    exit();
                } else {
                    // Invalid first-time login password, redirect back to the login page with an error message
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    $error = "Invalid username or password.";
                    header('Location: ../index.php?error=' . urlencode($error));
                    exit();
                }
            } else {
                // Verify the password using password_verify() with "hashed_password" column
                if (password_verify($password, $hashed_password)) {
                    mysqli_stmt_close($stmt);

                    // Retrieve control_number from the temporary_account table based on the username
                    $controlNumberQuery = "SELECT p.control_number FROM ceap_reg_form p
                    JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
                    WHERE t.username = ?";
                    $stmtControlNumber = mysqli_prepare($conn, $controlNumberQuery);
                    mysqli_stmt_bind_param($stmtControlNumber, "s", $username);
                    mysqli_stmt_execute($stmtControlNumber);
                    mysqli_stmt_bind_result($stmtControlNumber, $control_number);

                    if (mysqli_stmt_fetch($stmtControlNumber)) {
                        // Store the control_number in the session
                        $_SESSION['username'] = $username;
                        $_SESSION['control_number'] = $control_number;
                        mysqli_stmt_close($stmtControlNumber);

                        mysqli_close($conn);
                        header("Location: ../home-page-loggedin.php");
                        exit();
                    }
                }
            }
        }
        // Close the temporary account statement
    }

    // Check the user's location
    $userIsGrantee = false;

// To prevent SQL injection, use prepared statements
$sql = "SELECT hashed_password, control_number FROM ceap_personal_account WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashed_password, $control_number);

    if (mysqli_stmt_fetch($stmt)) {
        // Verify the password using password_verify()
        if (password_verify($password, $hashed_password)) {
            mysqli_stmt_close($stmt);

            // Store the username and control_number in the session
            $_SESSION['username'] = $username;
            $_SESSION['control_number'] = $control_number;

            mysqli_close($conn);

            // Redirect to the personal account page for authenticated users
            header("Location: ../personal_account/personalAcc_home.php");
            exit();
        }
    }
    mysqli_stmt_close($stmt);
}
    // Invalid credentials, redirect back to the login page with an error message
// Username or password not found in the database
$error = "Invalid username or password.";
header('Location: ../index.php?error=' . urlencode($error));
exit();
}

?>
