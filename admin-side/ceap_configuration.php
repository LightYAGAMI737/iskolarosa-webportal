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
   $sql = "SELECT toggle_value, qualifications, requirements FROM ceap_configuration ORDER BY id DESC LIMIT 1";
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
         include './php/configurationPopup.php';
         include './php/side_bar_main.php';
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
            <div class="toggle-applicant">
               <label for="toggleButton" class="toggle-label">
                  <span class="slider-text">Application Period:</span>
                  <div class="slider-toggle">
                     <input type="checkbox" id="toggleButton" required name="toggleButton" <?php echo ($toggleValue == 1) ? 'checked' : ''; ?>>
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
                     <textarea id="qualifications" name="qualifications" placeholder="Enter text here" rows="4" maxlength="1000" oninput="formatInput(this)"   required><?php echo ($qualifications) ?></textarea>
                  </div>
               </div>
               <div class="column">
                  <div class="text-input">
                     <label for="requirements">Requirements:</label>
                     <textarea id="requirements" name="requirements" placeholder="Enter text here" rows="4" maxlength="1000"  oninput="formatInput(this)"   required><?php echo ($requirements) ?></textarea>
                  </div>
               </div>
            </div>
            <div class="button-container"> 
               <button type="button" id="submitConfigBtn" class="btn" onclick="openCEAPConfigurationPopup()">Submit</button>
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
      <script  src="./js/status_popup.js"></script>
      <script  src="./js/configurationPopup.js"></script>
 
         
      </script>
   </body>
</html>