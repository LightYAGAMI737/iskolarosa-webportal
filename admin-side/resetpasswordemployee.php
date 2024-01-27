<?php
include './php/config_iskolarosa_db.php';

function updatePassword($conn, $username, $newPassword) {
    // Generate a hashed password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user's hashed password in the employee_list table
    $updatePasswordSql = "UPDATE employee_list SET password = ?, first_time_login = 0 WHERE username = ?";
    $stmt = mysqli_prepare($conn, $updatePasswordSql);
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username);
    mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if (mysqli_affected_rows($conn) > 0) {
        // Password updated successfully, redirect the user to the login page with a success message
        mysqli_stmt_close($stmt);
        session_start();
        $_SESSION['password_updated'] = true;
        header("Location: admin_index.php");
        exit();
    } else {
        // Database update error, handle the error accordingly
        mysqli_stmt_close($stmt);
        header("Location: resetpassword.php?error=DatabaseError");
        exit();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the username and new password from the form
    $username = $_POST['username'];
    $newPassword = $_POST['confirm_password'];

    // Call the updatePassword function
    updatePassword($conn, $username, $newPassword);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iSKOLAROSA | Reset Password</title>
    <link rel="icon" href="system-images/iskolarosa-logo.png" type="image/png">
	<!-- Bootstrap 5 CDN Link -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Custom CSS Link -->
	<link rel="stylesheet" href="./css/forgotpassword.css">
    <style>
    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-label {
        margin-left: 10px; /* Adjust the margin as needed to control the spacing */
    }
    .form-check-input:checked {
    background-color: #FEC021;
    border-color: #FEC021;
    }
   
    #tooltipsID {
        position: absolute;
    background-color: #333;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
    z-index: 1000;
    width: 300px;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    bottom: 57%;
    left: 50%;
    transform: translateX(-50%);
    text-align: left;

}

.tooltips.active {
    visibility: visible;
    opacity: 1;
}
</style>
</head>
<body> 
<section class="wrapper">
    <div class="container">
        <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
            <div class="logo">
                <img decoding="async" src="./system-images/iskolarosa-logo.png" class="img-fluid" alt="logo">
            </div>
            <form class="rounded bg-white shadow p-5" action="resetpasswordemployee.php" method="post">
                <input type="hidden" name="username" value="<?php echo $_GET['username']; ?>">
                <h3 class="text-dark fw-bolder fs-4 mb-2">Reset Password</h3>
                <div id="tooltipsID">
                <div class="tooltips"></div>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" style="margin-bottom: 10px;" id="new_password" name="new_password" placeholder="New Password">
                    <label for="new_password">New Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" style="margin-bottom: 10px;" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
                    <label for="confirm_password">Confirm New Password</label>
                </div>

                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="showPassword">
                    <label class="form-check-label" for="showPassword">Show Password</label>
                </div>
              
                <button type="submit" class="btn btn-primary submit_btn my-4" id="submitForgotPassword" disabled>Submit</button>
                <button type="button" class="btn btn-secondary submit_btn my-4 ms-3" id="cancelForgotPassword">Cancel</button>
            </form>
        </div>
    </div>
</section>
	<script src="./js/resetpassword.js"></script>
    <script src="./js/validatepassword.js"></script>
</body>
</html>

