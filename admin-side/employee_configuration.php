<?php
   // Include configuration and functions
   session_start();
   include './php/config_iskolarosa_db.php';
   include './php/functions.php';
   
   // Description: This script handles permission checks and retrieves applicant information.
   
   // Check if the session is not set (user is not logged in)
   if (!isset($_SESSION['username'])) {
       echo 'You need to log in to access this page.';
       exit();
   }
   
   // Define the required permission
   $requiredPermission = 'delete_applicant';
   
   // Define an array of required permissions for different pages
   $requiredPermissions = [
       'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
       'edit_users' => 'You do not have permission to edit applicant.',
       'delete_applicant' => 'You do not have permission to access this page.',
   ];
   
   // Check if the required permission exists in the array
   if (!isset($requiredPermissions[$requiredPermission])) {
       echo 'Invalid permission specified.';
       exit();
   }
   
   // Call the hasPermission function to check the user's permission
   if (!hasPermission($_SESSION['role'], $requiredPermission)) {
       echo $requiredPermissions[$requiredPermission];
       exit();
   }
 // Check the user's role
 if ($_SESSION['role'] === 3) {
  // Set variables for head admin
  $currentPage = "configuration";
  $currentSubPage = "employee";
} else {
  // Set variables for other roles
  $currentPage = "configuration";
  $currentSubPage = "";
}


   ?>

<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
      <link rel="stylesheet" href="./css/remixicon.css">
      <link rel='stylesheet' href='./css/unpkg-layout.css'>
      <link rel="stylesheet" href="./css/side_bar.css">
      <link rel="stylesheet" href="./css/ceap_configuration.css">
      <link rel="stylesheet" href="./css/status_popup.css">
      <link rel="stylesheet" href="./css/employee_configuration.css">
      <link rel="stylesheet" href="./css/errorpopup.css">
      <style>
        /* Define the invalid class with red border color */
        .invalid {
            border-color: red !important;
        }
        .password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
}
input:focus::placeholder {
    color: transparent;
}
    </style>
   </head>
   <body>
   <?php
        include './php/passwordpopup.php';
        include './php/EmployeeConfigPopup.php';
        include './php/head_admin_side_bar.php';

?>
      <!-- home content-->   
      <div class="header-label">
<h1>Employee Configuration</h1>
</div>
      <nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
<div class="collapse navbar-collapse">
<ul class="navbar-nav">
    <li class="nav-item" style="padding-right: 25px;">
        <strong><a id="ceapLink" class="nav-link status actives" href="employee_configuration.php">ADD EMPLOYEE</a></strong>
    </li>
    <li class="nav-item" style="padding-right: 20px;">
        <strong><a id="lpppLink" class="nav-link status" href="employee_list.php">EMPLOYEE LIST</a></strong>
    </li>
</ul>
</div>
</nav> 
      <form method="POST" action="../php/employeeconfigurationinsert.php"  id="employeeconfigform" enctype="multipart/form-data">
        <div class="text-cont">
            <h1>EMPLOYEE CONFIGURATION</h1>
            <div class="form-row">
            <div class="form-group">
    <label class="required" for="employeeId">Employee ID:</label>
    <input type="text" id="employeeId" name="employee_id_no" minlength="5" maxlength="5" placeholder="Enter employee ID" required>
    <span id="employeeIdErrors" class="error-message"></span>
</div>
      <div class="form-group">
        <label class="required" for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="last_Name" placeholder="Dela Cruz" minlength="2" maxlength="25" required required onkeypress="validateNames()">
        <span id="lastNameError" class="error-message"></span>
      </div>
      <div class="form-group">
        <label class="required" for="firstName">First Name:</label>
        <input type="text" id="firstName" name="first_Name" placeholder="Juan" minlength="2" maxlength="25" required required onkeypress="validateNames()">
        <span id="firstNameError" class="error-message"></span>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="required"  for="contactNumber">Contact Number:</label>
        <input type="text" id="contactNumber" name="contact_Number" placeholder="09-xxxxxxxxx"  minlength="11" maxlength="11" required>
        <span id="contactNumberError" class="error-message"></span>
      </div>
      <div class="form-group">
        <label class="required"  for="email">Email:</label>
        <input type="text" id="email" name="email" minlength="10" maxlength="100" placeholder="juandelacruz@gmail.com" required>
        <span id="emailError" class="error-message"></span>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="required"  for="department">Role:</label>
        <select id="department" name="role_name" required>
      <option value="" hidden>Select role</option>
      <option value="Staff">STAFF</option>
      <option value="Admin">ADMIN</option>
    </select>
    
      </div>
      <div class="form-group">
      <label class="required"  for="picture">Picture: (JPG/JPEG, 2MB)</label>
<input type="file" id="picture" name="picture" accept=".jpg, .jpeg" onchange="validatePicture()" required>
<span id="pictureError" class="error-message"></span>

</div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="required"  for="username">Username:</label>
        <input type="text" id="username"  style="border: none; background: #ececec;"  name="username" readonly required>
      </div>
      <div class="form-group">
    <label class="required" for="password">Password:</label>
    <div class="password-input">
        <input type="password" id="password" name="password" placeholder="Enter password" maxlength="20" required>
        <span class="password-toggle" onclick="togglePassword()">
            <i class="ri-eye-off-fill"></i>
        </span>
    </div>
    <span id="passwordError" class="error-message"></span>
</div>

<div class="form-group">
    <label class="required" for="confirmPassword">Confirm Password:</label>
    <div class="password-input">
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" maxlength="20" required>
        <span class="password-toggle" id="password-toggle-confirmPassword" onclick="toggleConfirmPassword()">
            <i class="ri-eye-off-fill"></i>
        </span>
    </div>
    <span id="confirmPasswordError" class="error-message"></span>
</div>
    </div>
    <div class="button-container">
                <button type="button" id="submitButton" onclick="openemployeeConfigPopup()" disabled>Submit</button>
            </div>
  </div>
</form>
      <!-- end application configuration -->
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="./js/side_bar.js"></script>
      <script  src="./js/employee_configuration.js"></script>
      <script  src="./js/employeeConfigPopup.js"></script>
   </body>
</html>