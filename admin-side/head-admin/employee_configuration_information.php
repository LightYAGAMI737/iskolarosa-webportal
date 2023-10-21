<?php
   // Include configuration and functions
   session_start();
   include '../php/config_iskolarosa_db.php';
   include '../php/functions.php';
   
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
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/ceap_configuration.css">
      <link rel="stylesheet" href="../css/employee_configuration.css">
      <link rel="stylesheet" href="../css/ceap_information.css">
   </head>

<body>
  
        <?php
// Include the database connection file
include 'head_admin_side_bar.php';

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
        <a href="#" class="back-button" onclick="goBack()">
            <i><i class="ri-close-circle-line"></i></i>
        </a>
      </div>
    <?php
      echo '<h1>Employee Information</h1>';
    echo '<table class="employee-table">';
    echo '<thead>';
    echo '<tr>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through the results and display the data in the table
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>Employee ID</td>';
        echo '<td>' . strtoupper($row['employee_id_no']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Last Name</td>';
        echo '<td>' . strtoupper($row['last_name']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>First Name</td>';
        echo '<td>' . strtoupper($row['first_name']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Contact Number</td>';
        echo '<td>' . strtoupper($row['contact_number']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Email</td>';
        echo '<td>' . strtoupper($row['email']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Department</td>';
        echo '<td>' . strtoupper($row['role_id']) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Username</td>';
        echo '<td>' . strtoupper($row['username']) . '</td>';
        echo '</tr>';

        // Assuming you have stored the image path in the database
        echo '<tr>';
        echo '<td>Picture</td>';
        echo '<td><img src="' . $row['picture'] . '" alt="Employee Picture" style="width: 200px; height: 200px;"></td>';
        echo '</tr>';

        // Add the remaining form fields as needed...
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    // Display a message if there are no records in the database
    echo '<div class="text-cont">';
    echo '<h1>Employee Configuration Information</h1>';
    echo '<p>No records found.</p>';
    echo '</div>';
}

// Close the database connection
$conn->close();
?>
<script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>

<script>
function goBack() {
  window.history.back();
}

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar-wrapper");
  var tableContainer = document.querySelector(".table-container");

  if (sidebar.classList.contains("open")) {
    // Sidebar is currently open
    sidebar.classList.remove("open");
    tableContainer.style.width = "100%"; // Reset the width to 100%
  } else {
    // Sidebar is currently closed
    sidebar.classList.add("open");
    var sidebarWidth = sidebar.offsetWidth;
    var availableWidth = window.innerWidth - sidebarWidth;
    tableContainer.style.width = availableWidth + "px";
  }
}

function expandImage(img) {
  img.parentNode.querySelector(".expanded-image").style.display = "block";
}

function collapseImage(expandedImg) {
  expandedImg.style.display = "none";
}

function togglePasswordVisibility(password) {
    var passwordField = document.getElementById('passwordField');
    var showHideButton = document.getElementById('showHideButton');

    if (passwordField.innerHTML === '********') {
        // Show the password
        passwordField.innerHTML = password;
        showHideButton.textContent = 'Hide';
    } else {
        // Hide the password
        passwordField.innerHTML = '********';
        showHideButton.textContent = 'Show';
    }
}
</script>

</div>
</div>
</div>
</div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>
</html>
