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
    $currentSubPage = "application";
} else {
    // Set variables for other roles
    $currentPage = "configuration";
    $currentSubPage = "application";
}

// Retrieve the toggle value from the database
$sql = "SELECT toggle_value, qualifications, requirements FROM lppp_configuration ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row !== null) {
        // Retrieve the toggle_value, qualifications, and requirements
        $toggleValue = $row['toggle_value'];
        $qualifications = $row['qualifications'];
        $requirements = $row['requirements'];
    }else {
    // No data available, set toggle_value to zero and other values to default
    $toggleValue = 0;
    $qualifications = '';
    $requirements = '';
}

    // Close the result set
    mysqli_free_result($result);
} else {
    // Handle the case when the query fails
    echo "Error retrieving data from the database: " . mysqli_error($conn);
    exit; // Stop execution if the query fails
}

   ?>

<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentPage); ?></title>
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" type="text/css" href="../css/popup.css">
      <link rel="stylesheet" href="../css/ceap_configuration.css">
       
      <script>
        // Prevent manual input in date fields
        function preventInput(event) {
            event.preventDefault();
        }
        window.onload = function() {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const lastDayOfYear = new Date(currentYear, 11, 31); // Month is 0-based
            const formattedLastDay = lastDayOfYear.toISOString().split('T')[0];

            document.getElementById('startDate').setAttribute('max', formattedLastDay);
            document.getElementById('endDate').setAttribute('max', formattedLastDay);
        };
    </script>
   </head>
   <body>
   <?php
    include 'head_admin_side_bar.php';
?>
      <!-- home content-->   
      <div class="header-label">
<h1>Application Configuration</h1>
</div>
      <nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
<div class="collapse navbar-collapse">
<ul class="navbar-nav">
        <li class="nav-item" style="padding-right: 25px;">
            <strong><a class="nav-link status" href="ceap_configuration.php">CEAP</a></strong>
        </li>
        <li class="nav-item" style="padding-right: 20px;">
            <strong><a class="nav-link status actives" href="lppp_configuration.php">LPPP</a></strong>
        </li>
    </ul>
</div>
</nav> 
      <form method="POST" action="lpppconfigurationinsert.php">
        <div class="text-cont">
            <h1>LPPP CONFIGURATION</h1>
            <div class="toggle-applicant">
                <label for="toggleButton" class="toggle-label">
                    <span class="slider-text">Application Period:</span>
                    <div class="slider-toggle">
                        <input type="checkbox" id="toggleButton" name="toggleButton" <?php echo ($toggleValue == 1) ? 'checked' : ''; ?>>
                        <div class="slider"></div>
                    </div>
                </label>
            </div>
            <p class="application-dates">
        <label for="startDate">Application starts at:</label>
        <input type="date" id="startDate" name="startDate" required onkeydown="preventInput(event)" onchange="checkTimeValidity()">
        <input type="time" id="startTime" name="startTime" required onkeydown="preventInput(event)" onchange="checkTimeValidity()">

        <label for="endDate">and ends at:</label>
        <input type="date" id="endDate" name="endDate" required onkeydown="preventInput(event)">
        <input type="time" id="endTime" name="endTime" required onkeydown="preventInput(event)">
    </p>
            <?php
                            $sql = "SELECT toggle_value FROM lppp_configuration ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row !== null) {
        // Retrieve the toggle_value
        $toggleValue = $row['toggle_value'];

        // Determine the state of the button based on the toggle value
        $isDisabled = ($toggleValue == 0) ? true : false;
    } else {
        // No data available
        $isDisabled = true;
    }

    // Close the database connection
    mysqli_free_result($result);
    $conn->close();
} else {
    // Handle the case when the query fails
    echo "Error retrieving data from the database: " . mysqli_error($conn);
}
?>

<div class="two-column-layout">
                <div class="column">
                    <div class="text-input">
                        <label for="qualifications">Qualifications:</label>
                        <textarea id="qualifications" name="qualifications" placeholder="Enter text here" rows="4" maxlength="1000"   oninput="formatInput(this)"  required><?php echo ($qualifications) ?></textarea>
                    </div>
                </div>
                <div class="column">
                    <div class="text-input">
                        <label for="requirements">Requirements:</label>
                        <textarea id="requirements" name="requirements" placeholder="Enter text here" maxlength="1000"  oninput="formatInput(this)"  rows="4" required><?php echo ($requirements) ?></textarea>
                    </div>
                </div>
            </div>
        <!-- start popup -->
        <div class="button-container"> 
                <!-- <div class="container-button">  --> 
                    <button type="button" id="disabled" class="btn" onclick="openPopup()">Submit</button>
                        <div class="popup" id="popup"><br>
                        <i class="ri-error-warning-line"style="font-size: 10em; color: #A5040A;"></i>
            <strong><h2>Submit Now?</h2></strong>
            <center><p>Confirming this action will save your changes to the application period. Are you sure you want to proceed?</p></center>
            <div style="padding: 10px;">
            <button type="button" onclick="closePopup()" style="margin-right: 15px; background-color: #C0C0C0;"><i class="ri-close-fill"></i>Cancel</button>
            <button type="submit" onclick="closePopup()"><i class="ri-check-line"></i>Confirm</button>
            </div>
        </div> 
        <!-- ending popup -->
        </div>
    </form>
      <!-- end application configuration -->
      <!-- <footer class="footer">
      </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>
     
      
       <!-- popup -->
       <script>
    let popup = document.getElementById("popup");

    function openPopup(){
        popup.classList.add("open-popup")
        document.getElementById("disabled").disabled=true;
    }
    function closePopup(){
        popup.classList.remove("open-popup")
        document.getElementById("disabled").disabled=false;
    }
</script>
    <!-- popup -->
     <script>
   function checkTimeValidity() {
    const startDateInput = document.getElementById('startDate');
    const startTimeInput = document.getElementById('startTime');
    const endDateInput = document.getElementById('endDate');
    const endTimeInput = document.getElementById('endTime');

    // Get the current date and time
    const currentDate = new Date();
    currentDate.setSeconds(0); // Set seconds to 0 to make it more precise

    // Calculate the selected date and time
    const startDateTime = new Date(startDateInput.value + 'T' + startTimeInput.value);
    const endDateTime = new Date(endDateInput.value + 'T' + endTimeInput.value);

    // Check if the start time is earlier than the current time
    if (startDateTime <= currentDate) {
        alert('Start time cannot be earlier the current time.');
        startTimeInput.value = ''; // Clear the input field
        return;
    }

    // Check if the end time is not before the start time
    if (endDateTime <= startDateTime) {
        alert('End time must be after the start time.');
        endTimeInput.value = ''; // Clear the input field
    }
}

// Set the minimum date for the start date input to today's date
const today = new Date().toISOString().split('T')[0];
document.getElementById('startDate').setAttribute('min', today);
document.getElementById('endDate').setAttribute('min', today);

// Add event listeners to date and time inputs
document.getElementById('startDate').addEventListener('change', checkTimeValidity);
document.getElementById('startTime').addEventListener('change', checkTimeValidity);
document.getElementById('endDate').addEventListener('change', checkTimeValidity);
document.getElementById('endTime').addEventListener('change', checkTimeValidity);

// Disable the submit button by default
const submitButton = document.querySelector('button[type="button"]');
submitButton.disabled = true;

// Function to check if all required fields are filled
function checkRequiredFields() {
    const requiredFields = document.querySelectorAll('[required]');
    let allFilled = true;
    requiredFields.forEach(function(field) {
        if (field.value.trim() === '') {
            allFilled = false;
        }
    });
    submitButton.disabled = !allFilled;
}

// Add event listeners to required fields
const requiredFields = document.querySelectorAll('[required]');
requiredFields.forEach(function(field) {
    field.addEventListener('input', checkRequiredFields);
});


// Add event listener to the toggle button
toggleButton.addEventListener('change', toggleButtonStateChanged);

// Function to handle toggle button state change
function toggleButtonStateChanged() {
    // Get the state of the toggle button
    const isChecked = toggleButton.checked;

    // Enable or disable the submit button based on the toggle button state
    submitButton.disabled = isChecked;

    // Enable or disable the required attribute of the fields based on the toggle button state
    requiredFields.forEach(function(field) {
        field.required = isChecked;
    });
}

// Call the toggleButtonStateChanged function initially to set the initial state of the submit button and required fields
toggleButtonStateChanged();


    </script>
<script>
function formatInput(inputElement) {
    // Replace multiple consecutive spaces with a single space
    inputElement.value = inputElement.value.replace(/ +/g, ' ');
}

    </script>
   </body>
</html>