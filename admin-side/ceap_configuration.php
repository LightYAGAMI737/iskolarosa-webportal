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
   
   // Set variables
   $currentPage = "configuration";
   $currentSubPage = "";
   
   
   // Retrieve the toggle value from the database
   $sql = "SELECT * FROM ceap_configuration ORDER BY id DESC LIMIT 1";
   $result = mysqli_query($conn, $sql);
   // Your database retrieval code
   
   if ($result) {
   $row = mysqli_fetch_assoc($result);
   
   if ($row !== null) {
       $toggleValue = $row['toggle_value'];
       $qualifications = $row['qualifications'];
       $requirements = $row['requirements'];
       $startTime = $row['start_time'];
       $startDate = $row['start_date'];
       $endTime = $row['end_time'];
       $endDate = $row['end_date'];
   } else {
       // No data available, set toggle_value to zero and other values to default
       $toggleValue = 0;
       $qualifications = '';
       $requirements = '';
       $startTime = '';
       $startDate = '';
       $endTime = '';
       $endDate = '';
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
      <link rel="icon" href="system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='css/remixicon.css'>
      <link rel='stylesheet' href='css/unpkg-layout.css'>
      <link rel="stylesheet" href="css/side_bar.css">
      <link rel="stylesheet" href="css/ceap_configuration.css">
      <link rel="stylesheet" href="css/status_popup.css">
      <link rel="stylesheet" href="css/errorpopup.css">
      <script>
    // Prevent manual input in date fields
    function preventInput(event) {
        event.preventDefault();
    }
    window.onload = function () {
    const currentDate = new Date();
    
    // Calculate the date 6 months from the current date
    const sixMonthsLater = new Date(currentDate);
    sixMonthsLater.setMonth(currentDate.getMonth() + 6);

    const formattedMaxDate = sixMonthsLater.toISOString().split('T')[0];

    document.getElementById('startDate').setAttribute('max', formattedMaxDate);
    document.getElementById('endDate').setAttribute('max', formattedMaxDate);
};

</script>

   </head>
   <body>
      <?php
         include './php/configurationPopup.php';
         
         if ($_SESSION['role'] == '3') {
            include './php/head_admin_side_bar.php';
        } else {
            include './php/side_bar_main.php';
        }
         ?>
      <!-- home content-->   
      <div class="header-label">
         <h1>Application Configuration</h1>
      </div>
      <nav class="navbar navbar-expand-xl custom-nav" style="font-size: 15px; padding: 10px;">
         <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
               <li class="nav-item" style="padding-right: 25px;">
                  <strong><a class="nav-link status actives" href="ceap_configuration.php">CEAP</a></strong>
               </li>
               <li class="nav-item" style="padding-right: 20px;">
                  <strong><a class="nav-link status" href="lppp_configuration.php">LPPP</a></strong>
               </li>
            </ul>
         </div>
      </nav>
      <form method="POST" id="configForm">
         <div class="text-cont">
            <h1>CEAP CONFIGURATION</h1>
            <center><label for="current_time">Current Date and Time:</label>
               <span id="currentDateTime"></span>
            </center>
            <script>
      function updateCurrentDateTime() {
    const currentDateTimeElement = document.getElementById('currentDateTime');
    const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric' };
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based
    const year = currentDate.getFullYear();

    const currentDateTime = currentDate.toLocaleString([], options);
    const formattedDate = `${day}/${month}/${year}`;

    currentDateTimeElement.textContent = `${formattedDate} ${currentDateTime}`;
}

updateCurrentDateTime();
setInterval(updateCurrentDateTime, 1000); // Update every 1 second

            </script>
            <div class="toggle-applicant">
               <label for="toggleButton" class="toggle-label">
                  <span class="slider-text">Application Period:</span>
                  <div class="slider-toggle">
                     <input type="checkbox" id="toggleButton" name="toggleButton" <?php echo ($toggleValue == 1) ? 'checked' : ''; ?>>
                     <div class="slider"></div>
                  </div>
               </label>
            </div>
            <?php
               if ($startTime === "00:00:00") {
                   $startTime = '';
               }
               
               if ($endTime === "00:00:00") {
                   $endTime = '';
               }
               ?>
            <p class="application-dates">
               <label for="startDate">Application starts at:</label>
               <input type="date" id="startDate" name="startDate" disabled required value="<?php echo ($startDate); ?>" onkeydown="preventInput(event)"  onchange="checkTimeValidity()" <?php if (!empty($startDate)) echo 'disabled'; ?>>
               <input type="time" id="startTime" name="startTime" disabled  required value="<?php echo ($startTime); ?>" onkeydown="preventInput(event)"  onchange="checkTimeValidity()">
               <label for="endDate">and ends at:</label>
               <input type="date" id="endDate" name="endDate" disabled required value="<?php echo ($endDate); ?>"  onkeydown="preventInput(event)" onchange="checkTimeValidity()">
               <input type="time" id="endTime" name="endTime"disabled required value="<?php echo ($endTime); ?>"  onkeydown="preventInput(event)" onchange="checkTimeValidity()">
            </p>
            <span class="TimeandDateError"></span>
            <?php
               $sql = "SELECT toggle_value FROM ceap_configuration ORDER BY id DESC LIMIT 1";
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
                     <textarea id="qualifications" name="qualifications" disabled placeholder="Enter text here" rows="4" maxlength="1000" oninput="formatInput(this)"   required><?php echo ($qualifications) ?></textarea>
                  </div>
               </div>
               <div class="column">
                  <div class="text-input">
                     <label for="requirements">Requirements:</label>
                     <textarea id="requirements" name="requirements" disabled placeholder="Enter text here" rows="4" maxlength="1000"  oninput="formatInput(this)"   required><?php echo ($requirements) ?></textarea>
                  </div>
               </div>
            </div>
            <div class="button-container"> 
                <button type="button" id="submitConfigBtnCEAP" class="btn" onclick="openCEAPConfigurationPopup()">Submit</button>
                   <?php 
                    if ($_SESSION['role'] == "3") {
                        echo '<button type="button" id="editConfigBtnCEAP" class="btn editConfig" onclick="EditConfiguration()">Edit</button>';
                        echo '<button type="button" id="cancelConfigBtnCEAP" class="btn editConfig" onclick="cancelConfiguration()">Cancel</button>';
                    }
                    ?>
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
            
      <script src='js/unpkg-layout.js'></script>
      <script  src="./js/side_bar.js"></script>
      <script  src="./js/configuration.js"></script>

      <script>
const editConfigBTN = document.getElementById('editConfigBtnCEAP');
         // Function to check if all required fields are filled and text areas have at least 15 characters
function checkRequiredFields() {
    const requiredFields = document.querySelectorAll('[required]');
    const textAreas = document.querySelectorAll('textarea');

    const areAllFieldsFilled = Array.from(requiredFields).every((field) => field.value.trim() !== '');
    const textAreasValid = Array.from(textAreas).every((area) => area.value.trim().length >= 15);

    if (areAllFieldsFilled && textAreasValid) {
        submitConfigBtnCEAP.removeAttribute("disabled");
    } else {
        submitConfigBtnCEAP.setAttribute("disabled", "true");
    }
}

// Function to check the toggle state using XHR
function checkToggleStateCEAP() {
    const xhr = new XMLHttpRequest();

    xhr.open('GET', './php/checkToggleStateCEAP.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = xhr.responseText.trim();

                // Log the current state of the submit button
                console.log(`Current state of submit button: ${submitConfigBtnCEAP.disabled ? 'enabled' : 'disabled'}`);

                // Check if the toggle_value is 1
                if (response === '1') {
                    submitConfigBtnCEAP.disabled = true;
                    // Add an event listener to the toggle button
                    toggleButton.addEventListener('change', function () {
                        if (!toggleButton.checked) {
                            submitConfigBtnCEAP.disabled = false;
                        }else{
                            submitConfigBtnCEAP.disabled = true;
                             startDateInput.setAttribute("disabled", "true");
                        }
                    });
                } else if (response === '0')  {
                    console.error('Error: Toggle value is 0');
                    submitConfigBtnCEAP.disabled = true;
                }else if (response === '2') {
                    console.error('Error: Toggle value is 2');
                    submitConfigBtnCEAP.disabled = true;
                    toggleButton.setAttribute("disabled", "true");
                    submitConfigBtnCEAP.style.display = "none";
                    editConfigBTN.style.display = "block";

                }
            } else {
                console.error(`Error: XMLHttpRequest failed with status ${xhr.status}.`); 
            }
        }
    };

    xhr.send();
}
// Call the function to check the toggle state
checkToggleStateCEAP();

      </script>
<!-- Include this script in your HTML file -->
<script>
// Function to periodically update the database state
function updateDatabaseState() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './php/updateConfigStart.php', true);

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful, you can log or handle the response
            console.log(xhr.responseText);
        } else {
            // Request failed, handle the error
            console.error('Error updating database state. Status:', xhr.status, 'Response:', xhr.responseText);
        }
    };

    xhr.onerror = function () {
        // Handle network errors
        //console.error('Network error while updating database state.');
    };

    xhr.send();
}

// Set up a setInterval to periodically call the update function (every 5 minutes in this example)
setInterval(updateDatabaseState, 30000); // Adjust the interval as needed

</script>
<script>
function EditConfiguration() {
    var form = document.getElementById('configForm');
    var formElements = form.elements;
    var submitConfigBtnCEAP = document.getElementById('submitConfigBtnCEAP');
    var editConfigBtn = document.getElementById('editConfigBtnCEAP');
    var toggleButtonEdit = document.getElementById('toggleButton');
    var startDateInput = document.getElementById('startDateInput'); // Adjust the ID based on your actual input
    var cancelConfigBtnCEAP = document.getElementById('cancelConfigBtnCEAP'); // Adjust the ID based on your actual input

    // Store the initial form state
    var initialFormState = getFormState();

    for (var i = 0; i < formElements.length; i++) {
        formElements[i].disabled = false;
    }

    // Add event listener to form elements for the "input" event
    form.addEventListener('input', function () {
        var currentFormState = getFormState();
 //   submitConfigBtnCEAP.disabled = JSON.stringify(currentFormState) === JSON.stringify(initialFormState);
    });

// Add event listeners to input and textarea fields
const inputFields = document.querySelectorAll('input[type="date"], input[type="time"], textarea');
inputFields.forEach(function (field) {
    field.addEventListener('input', checkRequiredFields);
});

    // Add event listener to the toggle button for the "input" event
    toggleButtonEdit.addEventListener('change', function () {
        if (toggleButtonEdit.checked) {
                EditConfiguration(); // Call the function to enable the fields
        } else {
            // Enable the submit button when the toggle button is unchecked
            submitConfigBtnCEAP.disabled = false;
        }
    });
    toggleButton.checked = true; // Make the checkbox checked
    submitConfigBtnCEAP.style.display = "block";
    submitConfigBtnCEAP.disabled = true;
    editConfigBtn.style.display = "none";
    cancelConfigBtnCEAP.style.display = "block";
}

// Function to get the current state of the form
function getFormState() {
    var form = document.getElementById('configForm');
    var formElements = form.elements;

    var formState = {};
    for (var i = 0; i < formElements.length; i++) {
        formState[formElements[i].name] = formElements[i].value;
    }

    return formState;
}

checkRequiredFields();

function cancelConfiguration() {
    var form = document.getElementById('configForm');
    var formElements = form.elements;

    for (var i = 0; i < formElements.length; i++) {
        formElements[i].disabled = true;
    }
    var cancelConfigBtnCEAP = document.getElementById('cancelConfigBtnCEAP'); // Adjust the ID based on your actual input
    cancelConfigBtnCEAP.style.display = "none";
    submitConfigBtnCEAP.style.display = "none";
    editConfigBTN.style.display = "block";
    editConfigBTN.removeAttribute("disabled", "true");
    toggleButton.checked = false; // Make the checkbox checked
}

</script>

      <script  src="./js/status_popup.js"></script>
      <script  src="./js/configurationPopup.js"></script>
   </body>
</html>