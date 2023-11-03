function checkTimeValidity() {
    const startDateInput = document.getElementById('startDate');
    const startTimeInput = document.getElementById('startTime');
    const endDateInput = document.getElementById('endDate');
    const endTimeInput = document.getElementById('endTime');
    const errorSpan = document.querySelector('.TimeandDateError');

    // Create a Date object for the current time in Asia/Manila timezone
    const asiaManilaTimeZone = 'Asia/Manila';
    const currentDateTime = new Date(new Date().toLocaleString('en-US', { timeZone: asiaManilaTimeZone }));

    // Calculate the selected date and time in the Asia/Manila timezone
    const startDateTime = new Date(startDateInput.value + 'T' + startTimeInput.value);

    // Calculate one hour from the current time
    const oneHourFromNow = new Date(currentDateTime.getTime() + 3600000); // 3600000 milliseconds = 1 hour

    // Check if the start time is at least one hour after the current time
    if (startDateTime <= oneHourFromNow) {
        openstarterrormsgconfig();
        // Use setTimeout to close the error message after 5 seconds
        setTimeout(function () {
            closeerrormsgconfig();
        }, 10000); // 10000 milliseconds = 10 seconds

        startTimeInput.value = ''; // Clear the input field
        console.log('Start Date and Time Input is INVALID'); // Log that the input is invalid
        return;
    } else {
        errorSpan.textContent = ''; // Clear the error message if valid
        console.log('Start Date and Time Input is VALID'); // Log that the input is valid
    }

    // Check if the end time is at least one hour after the start time
    const endDateTime = new Date(endDateInput.value + 'T' + endTimeInput.value);

    if (endDateTime <= startDateTime.getTime() + 3600000) {
        openenderrormsgconfig();
        // Use setTimeout to close the error message after 5 seconds
        setTimeout(function () {
            closeerrormsgconfig();
        }, 10000); // 10000 milliseconds = 10 seconds

        endTimeInput.value = '';
        console.log('End Date and Time Input is INVALID'); // Log that the input is invalid
    } else {
        errorSpan.textContent = ''; // Clear the error message if valid
        console.log('End Date and Time Input is VALID'); // Log that the input is valid
        closeerrormsgconfig();
    }
}


// Set the minimum date for the start date input to today's date in the "Asia/Manila" timezone
const today = new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' }).split('T')[0];
document.getElementById('startDate').setAttribute('min', today);
document.getElementById('endDate').setAttribute('min', today);

// Add event listeners to date and time inputs
document.getElementById('startDate').addEventListener('input', checkTimeValidity);
document.getElementById('startTime').addEventListener('input', checkTimeValidity);
document.getElementById('endDate').addEventListener('input', checkTimeValidity);
document.getElementById('endTime').addEventListener('input', checkTimeValidity);

// Disable the submit button by default
const submitButton = document.querySelector('#submitConfigBtn');
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
toggleButton.addEventListener('input', toggleButtonStateChanged);

// Function to handle toggle button state input
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

document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.getElementById("toggleButton");
    const startDateInput = document.getElementById("startDate");
    const startTimeInput = document.getElementById("startTime");
    const endDateInput = document.getElementById("endDate");
    const endTimeInput = document.getElementById("endTime");

    toggleButton.addEventListener("change", function() {
        if (!toggleButton.checked) {
            // If the toggle button is unchecked, clear the values
            startDateInput.value = "";
            startTimeInput.value = "";
            endDateInput.value = "";
            endTimeInput.value = "";

        }
    });
});



function formatInput(inputElement) {
    // Replace multiple consecutive spaces with a single space
    inputElement.value = inputElement.value.replace(/ +/g, ' ');
}

//error message
const errorstartmsgconfiguration = document.getElementById("errorstartpopupinside");
const errorendmsgconfiguration = document.getElementById("errorendpopupinside");

function openenderrormsgconfig() {
    errorendmsgconfiguration.style.display="block";
}
function openstarterrormsgconfig() {
    errorstartmsgconfiguration.style.display="block";
}

function closeerrormsgconfig(){
    errorendmsgconfiguration.style.display="none";
    errorstartmsgconfiguration.style.display="none";
    
}
const CEAPconfigurationPopup = document.getElementById("CEAPconfigurationPopUp");
const LPPPconfigurationPopup = document.getElementById("LPPPconfigurationPopUp");

function openCEAPConfigurationPopup() {
    CEAPconfigurationPopup.style.display = "block";
}
function openLPPPConfigurationPopup() {
    LPPPconfigurationPopup.style.display = "block";
}
function closeLPPPConfigurationPopup() {
    LPPPconfigurationPopup.style.display = "none";
}
function closeCEAPConfigurationPopup() {
    CEAPconfigurationPopup.style.display = "none";
}


function openConfigConfirmationPopup() {
    closeCEAPConfigurationPopup();
    closeLPPPConfigurationPopup();

    const configConfrimPopup = document.getElementById('ConfigConfrimMsgPopUp');
    configConfrimPopup.style.display = "block";
    const okConfigButton = document.getElementById("okConfigConfirm");
    okConfigButton.addEventListener("click", function () {
        configConfrimPopup.style.display = "none";
        location.reload();
    });
    // Call the location.reload function after a 5-second delay
    setTimeout(function () {
        location.reload();
    }, 5000);
}

function CEAPSubmitConfig() {
    const xhr = new XMLHttpRequest();
    const url = './php/ceapconfigurationinsert.php';
    const method = 'POST';
    const formData = new FormData(document.querySelector('form'));
    xhr.open(method, url, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Ajax request successful');
            setTimeout(openConfigConfirmationPopup,1500);
        } else {
            alert('Error updating configuration');
            console.error('Ajax request failed');
        }
    };

    xhr.onerror = function () {
        console.error('Network error occurred during the Ajax request');
    };

    xhr.send(formData);
}


function LPPPSubmitConfig() {
    const xhr = new XMLHttpRequest();
    const url = './php/lpppconfigurationinsert.php';
    const method = 'POST';
    const formData = new FormData(document.querySelector('form'));
    xhr.open(method, url, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Ajax request successful');
            setTimeout(openConfigConfirmationPopup,1500);
        } else {
            alert('Error updating configuration');
            console.error('Ajax request failed');
        }
    };

    xhr.onerror = function () {
        console.error('Network error occurred during the Ajax request');
    };

    xhr.send(formData);
}


