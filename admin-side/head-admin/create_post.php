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
   
   // Set variables
   $currentPage = "post";
   $currentSubPage = 'create post';
   
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?> </title>
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/create_post.css">
      <link rel="stylesheet" type="text/css" href="../css/popup.css">
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
      <style></style>
   </head>
   <body> <?php
         include 'head_admin_side_bar.php';
         ?>
      <!-- home content-->
      <!-- form for announcement -->
      <div class="header-label post">
         <h1>Create Post</h1>
      </div>
      <div class="create-post-form">
         <div class="content-section">
            <div class="left-section">
               <form action="create_post_insert.php" id="post-form" method="post" enctype="multipart/form-data">
                  <div class="input-row">
                     <label for="title" class="required">Title:</label>
                     <label for="scheduled_at" class="required">Tag</label>
                  </div>
                  <div class="input-row">
                  <input type="text" id="title" name="post_title" minlength="5" maxlength="100" style="margin-right: 10px;" oninput="formatInput(this)" required>
                     <select id="tag" name="tag">
                        <option value="ceap">CEAP</option>
                        <option value="lppp">LPPP</option>
                     </select>
                  </div>
                  <label for="description" class="required">Description:</label>
                  <textarea id="description" name="post_description" rows="4" minlength="10" maxlength="15000" oninput="formatInput(this)" required></textarea>
                  <br>
            </div>
            <div class="right-section">
               <label for="image">Upload Picture (Optional, JPG 5MB)</label>
               <input type="file" id="image" name="post_image" accept=".jpg, .jpeg" onchange="validateFile()">
               <br>
               <br>
               <label for="scheduled_at">Schedule Post (Optional):</label>
                <input type="datetime-local" id="scheduled_at" name="post_schedule_at" onkeydown="preventInput(event)"
                    min="<?php echo date('Y-m-d\TH:i'); ?>"
                    max="<?php echo date('Y-12-31\TH:i'); ?>"
                    onchange="validateSchedule()">
                <p id="scheduleError" style="color: red;"></p>
               <!-- create post button -->
               <div class="button-section ">
                  <button type="button" class="create" onclick="openPopup()" disabled>Create Post</button>
                  <!-- <button type="button" class="btn" onclick="openPopup()">Create Post</button> -->
                  <div class="popup" id="popup">
                     <br>
                     <i class="ri-download-2-fill" style="font-size: 10em; color: #A5040A;"></i>
                     <strong>
                        <h2>Post Now?</h2>
                     </strong>
                     <center>
                        <p>This will publish your post in Announcement. You can make further edits in the “Manage Post” page.</p>
                     </center>
                     <div style="padding: 10px;">
                        <button type="button" class="cancel" onclick="closePopup()" style="margin-right: 15px; background-color: #C0C0C0;">
                           <i class="ri-close-fill"></i>Cancel </button>
                        <button type="submit" onclick="closePopup()">
                           <i class="ri-check-line"></i>Post </button>
                     </div>
                  </div>
                  <!-- discard button -->
                  <div class="button-section ">
                     <button type="button" class="discard" onclick="opendiscard()">Discard</button>
                     <!-- <button type="button" class="btn" onclick="openPopup()">Discard</button> -->
                     <div class="disPopup" id="disPopup">
                        <br>
                        <i class="ri-delete-bin-2-fill" style="font-size: 10em; color: #A5040A;"></i>
                        <strong>
                           <h2>Discard Post?</h2>
                        </strong>
                        <center>
                           <p>Confirming this action will return your post to its previous state. Are you sure you want to discard your post? This action cannot be undo.</p>
                        </center>
                        <div style="padding: 10px;">
                           <button type="button" onclick="closediscard()" style="margin-right: 15px; background-color: #C0C0C0;">
                              <i class="ri-close-fill"></i>Cancel </button>
                           <button type="button" onclick="closediscard(), clearFormFields()">
                              <i class="ri-check-line"></i>Discard </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </form>
      <!-- end announcement form -->
      <!-- <footer class="footer"></footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script>
      <script src="../js/side_bar.js"></script>
      <script>
         function clearFormFields() {
            // Get the form element by its ID
            var form = document.getElementById("post-form");
            // Reset the form to clear all input fields
            form.reset();
         }
      </script>
      <script>
         let popup = document.getElementById("popup");

         function openPopup() {
            popup.classList.add("open-popup")
            // document.getElementById("disabled").disabled=true;
         }

         function closePopup() {
            popup.classList.remove("open-popup")
            // document.getElementById("disabled").disabled=false;
         }
      </script>
      <!-- popup -->
      <!-- popup discard-->
      <script>
         let disPopup = document.getElementById("disPopup");

         function opendiscard() {
            disPopup.classList.add("open-discard")
            // document.getElementById("disabled").disabled=true;
         }

         function closediscard() {
            disPopup.classList.remove("open-discard")
            // document.getElementById("disabled").disabled=false;
         }
      </script>

      <script>
       function validateFile() {
    const fileInput = document.getElementById("image");
    const fileType = fileInput.files[0].type;
    const fileSize = fileInput.files[0].size / 1024 / 1024; // Convert to MB
    const errorMessageElement = document.getElementById("fileErrorMessage");

    // Check file type
    if (fileType !== "image/jpeg" && fileType !== "image/jpg") {
        alert("Only JPEG images (file extension .jpg or .jpeg) are allowed.");
        fileInput.value = ""; // Clear the file input
        errorMessageElement.textContent = "Invalid file type. Only JPEG images (.jpg or .jpeg) are allowed.";
        fileInput.classList.add("invalid");
        return false;
    }

    // Check file size
    if (fileSize > 5) {
        alert("File size exceeds 5MB.");
        fileInput.value = ""; // Clear the file input
        errorMessageElement.textContent = "File size exceeds 5MB.";
        fileInput.classList.add("invalid");
        return false;
    }

    // Reset the input and error message if valid
    fileInput.classList.remove("invalid");
    errorMessageElement.textContent = "";
    return true;
}

      </script>
     
      <script>
         function formatInput(inputElement) {
            // Replace multiple consecutive spaces with a single space
            inputElement.value = inputElement.value.replace(/ +/g, ' ');

            const inputValue = inputElement.value;
            const minLength = inputElement.minLength;
            const maxLength = inputElement.maxLength;

            if (inputValue.length < minLength || inputValue.length > maxLength) {
                inputElement.classList.add("invalid");
            } else {
                inputElement.classList.remove("invalid");
                updateButtonState();
            }
                }
      </script>
      <script>
function validateSchedule() {
    const scheduledAtInput = document.getElementById('scheduled_at');
    const scheduleError = document.getElementById('scheduleError');
    
    const selectedDateTime = new Date(scheduledAtInput.value);
    const currentDateTime = new Date();
    
    if (selectedDateTime < currentDateTime) {
        scheduleError.textContent = 'Scheduled time cannot be earlier than the current time.';
        scheduledAtInput.classList.add("invalid");
        scheduledAtInput.value = ''; // Clear the input value

    } else {
        scheduledAtInput.classList.remove("invalid");
        scheduleError.textContent = '';
         // Clear any previous error message
    }
}
</script>
<!-- Add this script to your HTML file -->
<script>
    // Function to check if any input has the 'invalid' class
    function checkInvalidInputs() {
        const inputs = document.querySelectorAll('input, textarea');
        let hasInvalidInput = false;
        let hasEmptyRequiredField = false;

        inputs.forEach(function(input) {
            if (input.classList.contains('invalid')) {
                hasInvalidInput = true;
            }

            if (input.hasAttribute('required') && input.value.trim() === '') {
                hasEmptyRequiredField = true;
            }
        });

        return hasInvalidInput || hasEmptyRequiredField;
    }

    // Function to update the button's disabled state
    function updateButtonState() {
        const createButton = document.querySelector('.create');

        if (checkInvalidInputs()) {
            createButton.disabled = true; // Disable the button if there are invalid inputs or empty required fields
        } else {
            createButton.disabled = false; // Enable the button if all inputs are valid and required fields are filled
        }
    }
</script>

   </body>
</html>