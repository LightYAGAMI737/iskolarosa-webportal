const toggleButton = document.getElementById('toggleButton');
    const startDateInput = document.getElementById('startDate');
    const startTimeInput = document.getElementById('startTime');
    const endDateInput = document.getElementById('endDate');
    const endTimeInput = document.getElementById('endTime');
    const submitConfigBtn = document.getElementById('submitConfigBtn');
    const errorSpan = document.querySelector('.TimeandDateError');

// Function to enable or disable fields based on toggle state
function ToggleOnEnableFields() {

    
// Function to enable or disable the textareas
function toggleTextareas(disable) {
    document.getElementById("qualifications").disabled = disable;
    document.getElementById("requirements").disabled = disable;
}
    // Remove the 'disabled' attribute from the startDateInput
    startDateInput.removeAttribute("disabled");

    startDateInput.addEventListener("change", function () {
        if (startDateInput.value.trim() !== "") {
            startTimeInput.removeAttribute("disabled");
            startTimeInput.focus();
        } else {
            startTimeInput.setAttribute("disabled", "true");
            endDateInput.setAttribute("disabled", "true");
        }
    });

    startTimeInput.addEventListener("input", function () {
        if (startTimeInput.value.trim() !== "") {
            endDateInput.removeAttribute("disabled");
            endDateInput.focus();
        } else {
            endDateInput.setAttribute("disabled", "true");
            endTimeInput.setAttribute("disabled", "true");
        }
    });

    endDateInput.addEventListener("input", function () {
        if (endDateInput.value.trim() !== "") {
            endTimeInput.removeAttribute("disabled");
            endTimeInput.focus();
        } else {
            endTimeInput.setAttribute("disabled", "true");
        }
    });

    endTimeInput.addEventListener("input", function () {
            if (endTimeInput.value.trim() !=="") {
                toggleTextareas(false); // Enable the textareas only if "endTime" hasn't been touched
            }else
            {
                toggleTextareas(true); // Enable the textareas only if "endTime" hasn't been touched
            }
    });
}

// Add an event listener to the toggle button
toggleButton.addEventListener("change", function () {
    if (toggleButton.checked) {
        ToggleOnEnableFields(); // Call the function to enable the fields
    } else {
        // If the toggle button is unchecked, clear only the date and time values
        startDateInput.value = "";
        startTimeInput.value = "";
        endDateInput.value = "";
        endTimeInput.value = "";

        startDateInput.setAttribute("disabled", "true");
        startTimeInput.setAttribute("disabled", "true");
        endDateInput.setAttribute("disabled", "true");
        endTimeInput.setAttribute("disabled", "true");
    }
});


    function checkTimeValidity() {

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

// Function to check if all required fields are filled and text areas have at least 15 characters
function checkRequiredFields() {
    const requiredFields = document.querySelectorAll('[required]');
    const textAreas = document.querySelectorAll('textarea');

    const areAllFieldsFilled = Array.from(requiredFields).every((field) => field.value.trim() !== '');
    const textAreasValid = Array.from(textAreas).every((area) => area.value.trim().length >= 15);

    if (areAllFieldsFilled && textAreasValid) {
        submitConfigBtn.removeAttribute("disabled");
    } else {
        submitConfigBtn.setAttribute("disabled", "true");
    }
}

// Add event listeners to input and textarea fields
const inputFields = document.querySelectorAll('input[type="date"], input[type="time"], textarea');
inputFields.forEach(function (field) {
    field.addEventListener('input', checkRequiredFields);
});


// Function to check the toggle state using XHR
function checkToggleState() {
    const xhr = new XMLHttpRequest();

    xhr.open('GET', './php/checkToggleState.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = xhr.responseText.trim();

                // Log the current state of the submit button
                console.log(`Current state of submit button: ${submitConfigBtn.disabled ? 'disabled' : 'enabled'}`);

                // Check if the toggle_value is 1
                if (response === '1') {
                    submitConfigBtn.disabled = true;
                    // Add an event listener to the toggle button
                    toggleButton.addEventListener('change', function () {
                        if (!toggleButton.checked) {
                            submitConfigBtn.disabled = false;
                        }
                    });
                } else if (response === '0')  {
                    console.error('Error: Toggle value is not 1.');
                    submitConfigBtn.disabled = true;
                }
            } else {
                console.error(`Error: XMLHttpRequest failed with status ${xhr.status}.`); 
            }
        }
    };

    xhr.send();
}

// Call the function to check the toggle state
checkToggleState();
