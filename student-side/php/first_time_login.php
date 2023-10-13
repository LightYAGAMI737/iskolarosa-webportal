<!DOCTYPE html>
<html>
<head>
    <title>Update Password</title>
    <link rel="stylesheet" href="../css/first_time_login.css">
</head>
<body>
    <div class="container">
        <div class="header" style="background-color: #FEC021;"></div>
        <h2>Update Your Password</h2>
        <h4>(For first time log in)</h4>
        

        <!-- Display error message if there is any -->
        <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error === "PasswordMismatch") {
                echo '<p class="error-message">Passwords do not match.</p>';
            } elseif ($error === "DatabaseError") {
                echo '<p class="error-message">Error updating the password. Please try again later.</p>';
            }
        }
        ?>
   <form action="first_time_login_process.php" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
            <br>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <!-- Add the error message below the confirm password input -->
            <p class="error-message" style="color: red; display: none;">Passwords do not match.</p>
            <br>
            <input type="submit" value="Update Password" id="update_button" disabled>
        </form>
    </div>

    <script src="../js/update_password.js"></script>
</body>
</html>
