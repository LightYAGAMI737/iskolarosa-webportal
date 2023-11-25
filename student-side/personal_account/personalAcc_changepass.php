<?php
    include '../../admin-side/php/config_iskolarosa_db.php';
    session_start();
    // Check if the session is not set (user is not logged in)
    if (!isset($_SESSION['control_number'])) {
        // You can either show a message or redirect to the login page
        echo 'You need to log in to access this page.';
        // OR
         header("Location: index.php"); // Redirect to the login page
        exit();
    }
    include '../php/fetchedApplicantInfo.php';

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>iSKOLAROSA | Profile</title>
      <link rel="icon" href="../../admin-side/system-images/iskolarosa-logo.png" type="image/png">
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
      <link rel="stylesheet" href="../../admin-side/css/remixicon.css">
      <link rel="stylesheet" href="../../admin-side/css/status_popup.css">
      <link rel="stylesheet" href="../css/tempAcc_nav.css">
      <link rel="stylesheet" href="../css/personalAcc_profile.css">
      <style>
  .password-container {
    position: relative;
  }

  .toggle-icon {
    position: absolute;
    top: 73%;
    transform: translateY(-50%);
    right: 15px;
    cursor: pointer;
  }
  .custom-container {
    max-width: 400px; /* Adjust the maximum width as needed */
    margin: auto;
  }
  .updateBTN {
    margin-bottom: 20px;
    background: #A5040A !important;
    color: #fff !important;
    border: none;
    border-radius: 15px !important;
    padding: 8px 20px !important;    
}
  
</style>
    </head>

   <body>
      <?php
         include 'personalAcc_nav.php';
         ?>
        <div class="content-out-profile">
        <div class="content-head">
            <div class="profile-applicantname">
            <span class="profilename-uppercase">
                    <?php
                    $fullName = $last_name . ', ' . $first_name;

                    // Check if middle_name and suffix_name are not 'n/a' before adding them to the full name
                    if (isset($middle_name) && $middle_name !== 'N/A') {
                        $fullName .= ' ' . $middle_name;
                    }

                    if (isset($suffix_name) && $suffix_name !== 'N/A') {
                        $fullName .= ' ' . $suffix_name;
                    }

                    echo $fullName;
                    echo ' (' . $control_number . ')';
                    ?>
                </span>
            </div>
            </div>
            <div class="container mt-4 custom-container">
  <form id="changePasswordForm">
    <div class="mb-3 password-container">
      <label for="oldPassword" class="form-label">Old Password</label>
      <input type="password" class="form-control" id="oldPassword" required>
      <i class="toggle-icon" id="toggleOldPassword"> <i class="ri-eye-off-line"></i> </i>
    </div>

    <div class="mb-3 password-container">
      <label for="newPassword" class="form-label">New Password</label>
      <input type="password" class="form-control" id="newPassword" disabled required>
      <i class="toggle-icon" id="toggleNewPassword"> <i class="ri-eye-off-line"></i> </i>
    </div>
    <span id="newPassword_error"></span>

    <div class="mb-3 password-container">
      <label for="confirmPassword" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="confirmPassword" disabled required>
      <i class="toggle-icon" id="toggleConfirmPassword"> <i class="ri-eye-off-line"></i> </i>
    </div>
    <span id="confirmPassword_error"></span>

    <button type="submit" class="btn d-block mx-auto mb-3 updateBTN" id="updateBtn" disabled>Change Password</button>
  </form>
</div>
      <script src="../js/bootstrap.min.js"></script>

      <script>
document.addEventListener("DOMContentLoaded", function() {
    // Function to check if old password matches the hashed password in the database
    function checkOldPassword() {
        var oldPassword = document.getElementById('oldPassword').value;

        var xhr = new XMLHttpRequest();

        // Define the request
        xhr.open('POST', '../php/checkOldPassword.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var hashedPasswordFromDatabase = xhr.responseText; // Retrieve hashed password from the response

                        console.log("User Provided Password Hashed (for testing):", hashedPasswordFromDatabase); // Log user provided password hashed

                        if (hashedPasswordFromDatabase === 'success') {
                            console.log("Old Password matched");
                            enablePasswordFields(); // Enable password fields
                        } else {
                            disablePasswordFields(); // Disable password fields
                        }
                    } else {
                        // Handle the case when the request fails
                        console.log("Error in the request. Status: " + xhr.status);
                    }
                }
            };

        // Send the request with the old password data
        xhr.send('oldPassword=' + encodeURIComponent(oldPassword));
    }

    // Attach the checkOldPassword function to the input change event
    document.getElementById('oldPassword').addEventListener('input', checkOldPassword);

    // Function to handle form submission
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Add your code to update the password in the database here
        var newPassword = document.getElementById('newPassword').value;

        var xhr = new XMLHttpRequest();

        // Define the request
        xhr.open('POST', '../php/personalAcc_updatepassword.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // Define the callback function to handle the response
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;

                console.log(response);
                window.location.href ="personalAcc_status.php";
            }
        };

        // Send the request with the new password data
        xhr.send('newPassword=' + encodeURIComponent(newPassword));
    });
});
// Function to enable password fields
function enablePasswordFields() {
    document.getElementById('newPassword').disabled = false;
    document.getElementById('confirmPassword').disabled = true;
}

// Function to disable password fields
function disablePasswordFields() {
    document.getElementById('newPassword').disabled = true;
    document.getElementById('confirmPassword').disabled = true;
}
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Function to check if the password meets the criteria
    function isPasswordValid(password) {
      // Password should be alphanumeric and contain at least one uppercase, one lowercase, one number, and one special character
      var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])[0-9a-zA-Z\S]{8,}$/;
      return regex.test(password);
    }

    // Function to handle the input event on the new password field
    function handleNewPasswordInput() {
      var newPassword = document.getElementById('newPassword').value;
      var confirmPassword = document.getElementById('confirmPassword').value;

      // Check if the password meets the criteria
      if (isPasswordValid(newPassword)) {
        // Remove any existing error message
        document.getElementById('newPassword_error').textContent = '';

        // Enable the confirm password field
        document.getElementById('confirmPassword').disabled = false;

        // Check if the passwords match
        if (newPassword === confirmPassword) {
          // Remove any existing error message
          document.getElementById('confirmPassword_error').textContent = '';

          // Enable the update button
          document.getElementById('updateBtn').disabled = false;
        } else {
          // Disable the update button
          document.getElementById('updateBtn').disabled = true;
        }
      } else {
        // Display error message for new password
        document.getElementById('newPassword_error').textContent = 'Password should contain: 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special character.';

        // Disable the confirm password field and update button
        document.getElementById('confirmPassword').disabled = true;
        document.getElementById('updateBtn').disabled = true;
      }
    }

    // Attach the handleNewPasswordInput function to the input event on the new password field
    document.getElementById('newPassword').addEventListener('input', handleNewPasswordInput);

    // Function to handle the change event on the confirm password field
    function handleConfirmPasswordChange() {
      var newPassword = document.getElementById('newPassword').value;
      var confirmPassword = document.getElementById('confirmPassword').value;

      // Check if the passwords match
      if (newPassword === confirmPassword) {
        // Remove any existing error message
        document.getElementById('confirmPassword_error').textContent = '';

        // Enable the update button
        document.getElementById('updateBtn').disabled = false;
      } else {
        // Display error message for confirm password
        document.getElementById('confirmPassword_error').textContent = 'Passwords do not match';

        // Disable the update button
        document.getElementById('updateBtn').disabled = true;
      }
    }

    // Attach the handleConfirmPasswordChange function to the change event on the confirm password field
    document.getElementById('confirmPassword').addEventListener('change', handleConfirmPasswordChange);
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    function togglePasswordVisibility(inputId, toggleIconId) {
      var passwordInput = document.getElementById(inputId);
      var toggleIcon = document.getElementById(toggleIconId);

      toggleIcon.addEventListener("click", function () {
        var type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;

        // Toggle eye and eye-slash icons
        toggleIcon.innerHTML =
          type === "password" ? '<i class="ri-eye-off-line"></i>' : '<i class="ri-eye-line"></i>';
      });
    }

    // Toggle visibility for New Password
    togglePasswordVisibility("oldPassword", "toggleOldPassword");

    togglePasswordVisibility("newPassword", "toggleNewPassword");

    // Toggle visibility for Confirm Password
    togglePasswordVisibility("confirmPassword", "toggleConfirmPassword");
  });
</script>


   </body>
</html>