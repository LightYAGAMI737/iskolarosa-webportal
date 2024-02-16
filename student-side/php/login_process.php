<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../admin-side/php/config_iskolarosa_db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $checkCeapRegFormQuery = "SELECT ceap_reg_form_id FROM ceap_reg_form WHERE control_number = ?";
    $stmtCheckCeapRegForm = mysqli_prepare($conn, $checkCeapRegFormQuery);

    if ($stmtCheckCeapRegForm) {
        mysqli_stmt_bind_param($stmtCheckCeapRegForm, "s", $username);
        mysqli_stmt_execute($stmtCheckCeapRegForm);
        mysqli_stmt_store_result($stmtCheckCeapRegForm);

        if (mysqli_stmt_num_rows($stmtCheckCeapRegForm) > 0) {
            mysqli_stmt_close($stmtCheckCeapRegForm);

            $sql = "SELECT ceap_password, hashed_password, first_time_login, is_grantee FROM temporary_account WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $ceap_password, $hashed_password, $first_time_login, $is_grantee);

                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);

                    // Verify the password
                    if ($first_time_login == 1 ){
                            if($password == $ceap_password) {
                                $_SESSION['username'] = $username;
                                mysqli_close($conn);
                                header("Location: first_time_login.php");
                                exit();
                            }else {
                                mysqli_close($conn);
                                $error = "Incorrect username or password.";
                                header('Location: ../index.php?error=' . urlencode($error));
                                exit();
                            }
                    } elseif ($first_time_login == 0 && password_verify($password, $hashed_password)) {
                        $_SESSION['username'] = $username;
                        $_SESSION['control_number'] = $username; // Assuming username is the control number
                        mysqli_close($conn);

                        if ($is_grantee == 0) {
                            header("Location: ../tempAcc_status.php");
                        } elseif ($is_grantee == 1) {
                            header("Location: ../personal_account/personalAcc_status.php");
                        } else {
                            // Handle invalid is_grantee value
                        }
                        exit();
                    } else {
                        mysqli_close($conn);
                        $error = "Incorrect username or password.";
                        header('Location: ../index.php?error=' . urlencode($error));
                        exit();
                    }
                } else {
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    $error = "Error fetching data.";
                    header('Location: ../index.php?error=' . urlencode($error));
                    exit();
                }
            } else {
                mysqli_close($conn);
                $error = "Error preparing the statement.";
                header('Location: ../index.php?error=' . urlencode($error));
                exit();
            }
        } else {
            mysqli_stmt_close($stmtCheckCeapRegForm);
            mysqli_close($conn);
            $error = "Incorrect username or password.";
            header('Location: ../index.php?error=' . urlencode($error));
            exit();
        }
    } else {
        mysqli_close($conn);
        $error = "Error preparing the statement.";
        header('Location: ../index.php?error=' . urlencode($error));
        exit();
    }
} else {
    mysqli_close($conn);
    $error = "Database error.";
    header('Location: ../index.php?error=' . urlencode($error));
    exit();
}
?>
