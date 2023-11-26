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
$requiredPermission = 'view_ceap_applicants';

// Define an array of required permissions for different pages
$requiredPermissions = [
    'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
    'edit_users' => 'You do not have permission to edit applicant.',
    'delete_applicant' => 'You do not have permission to delete applicant.',
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
<html>

<head>

    <head>
        <meta charset="UTF-8">
        <title>iSKOLAROSA |
            <?php echo strtoupper($currentSubPage); ?>
        </title>
        <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
        <link rel="stylesheet" href="./css/remixicon.css">
        <link rel='stylesheet' href='./css/unpkg-layout.css'>
        <link rel="stylesheet" href="./css/status_popup.css">
        <link rel="stylesheet" href="./css/side_bar.css">
        <link rel="stylesheet" href="./css/ceap_configuration.css">
        <link rel="stylesheet" href="./css/employee_configuration.css">
        <link rel="stylesheet" href="./css/ceap_information.css">
    </head>

<body>

    <?php
    include './php/EmployeeConfigPopup.php';
    include './php/head_admin_side_bar.php';

    // Get the employee ID from the URL parameter
    $employee_id = $_GET['employee_id'];

    // Fetch data for a specific employee ID from the database
    $sql = "SELECT * FROM employee_list WHERE employee_id = $employee_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display the employee information in a table
    
        echo '<div class="text-cont">';
        ?> <!-- Back button -->
        <div class="back-button-container">
            <a href="#" class="back-button" onclick="goBacks()">
                <i><i class="ri-close-circle-line"></i></i>
            </a>
        </div>
        <?php
        echo '<h1>Employee Information</h1>';
        echo '<form id="editForm">';
        echo '<table class="employee-table">';
        echo '<thead>';
        echo '<tr></tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<td>Account Status:</td>';
            echo '<td>';
            echo '<select name="accountStatus" id="accountStatus" disabled>';
            echo '<option value="1" ' . ($row['account_status'] == 1 ? 'selected' : '') . '>Active</option>';
            echo '<option value="0" ' . ($row['account_status'] == 0 ? 'selected' : '') . '>Inactive</option>';
            echo '</select>';
            echo '</td>';

            echo '<tr>';
            echo '<td>Employee ID</td>';
            echo '<td><input type="text" id ="employeeId" name="employeeId" class="readonly" value="' . strtoupper($row['employee_id_no']) . '" readonly></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Username</td>';
            echo '<td><input type="text" id ="username" name="username" class="readonly" value="' . strtoupper($row['username']) . '" readonly></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Last Name</td>';
            echo '<td><input type="text" id ="lastName" name="lastName" value="' . strtoupper($row['last_name']) . '" disabled>';
            echo '<span id="lastNameError" class="error-message"></span></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>First Name</td>';
            echo '<td><input type="text" id ="firstName" name="firstName" value="' . strtoupper($row['first_name']) . '" disabled>';
            echo '<span id="firstNameError" class="error-message"></span></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Contact Number</td>';
            echo '<td><input type="text" id ="contactNumber" name="contactNumber" value="' . strtoupper($row['contact_number']) . '" disabled>';
            echo '<span id="contactNumberError" class="error-message"></span></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Email</td>';
            echo '<td><input type="text" id ="email" name="email" value="' . strtoupper($row['email']) . '" disabled>';
            echo '<span id="emailError" class="error-message"></span></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Department</td>';
            echo '<td><input type="text" id ="department" name="department" value="' . strtoupper($row['role_id']) . '" disabled>';
            echo '<span id="departmentError" class="error-message"></span></td>';
            echo '</tr>';


            echo '<tr>';
            echo '<td>Picture</td>';
            echo '<td><img src="' . $row['picture'] . '" alt="Employee Picture" style="width: 200px; height: 200px;"></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<br>';
        echo '<button type="button" class="editBtn" onclick="toggleEditing()">Edit</button>';
        echo '<button type="button" class="updateBtn" id="updateButton" onclick="openEditEmployeeConfigPopup()">Update</button>';
        echo '<button type="button" class="deleteBtn" onclick="openDeleteEmployee()">Delete</button>';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<div class="text-cont">';
        echo '<h1>Employee Configuration Information</h1>';
        echo '<p>No records found.</p>';
        echo '</div>';
    }

    $conn->close();
    ?>
    <script src='https://unpkg.com/@popperjs/core@2'></script>
    <script src="./js/side_bar.js"></script>
    <script src="./js/employeeInformationPopup.js"></script>
    <script src="./js/employeeInfoValidate.js"></script>

    </div>
    </div>
    </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>

</html>