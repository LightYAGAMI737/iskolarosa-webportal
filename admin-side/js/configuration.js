    const toggleButton = document.getElementById('toggleButton');
    const startDateInput = document.getElementById('startDate');
    const startTimeInput = document.getElementById('startTime');
    const endDateInput = document.getElementById('endDate');    
    const endTimeInput = document.getElementById('endTime');
    const qualifications = document.getElementById('qualifications')
    const requirements = document.getElementById('requirements')
    const submitConfigBtnCEAP = document.getElementById('submitConfigBtnCEAP');
    const submitConfigBtnLPPP = document.getElementById('submitConfigBtnLPPP');
    const errorSpan = document.querySelector('.TimeandDateError');
    
    // Function to format the current date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Set the min attribute to the current date
    startDateInput.min = formatDate(new Date());
    // Add an event listener to the startDate input to update the min attribute of endDate
    startDateInput.addEventListener('input', function () {
        const startDateValue = startDateInput.value;
        endDateInput.min = startDateValue;
    });
    

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

toggleButton.addEventListener("input", function () {
    if (toggleButton.checked) {
        if (startDateInput.value.trim() === "") {
            ToggleOnEnableFields(); // Call the function to enable the fields
        }
    }else {
        startDateInput.setAttribute("disabled", "true")
        startTimeInput.setAttribute("disabled", "true")
        endDateInput.setAttribute("disabled", "true")
        endTimeInput.setAttribute("disabled", "true")
        endTimeInput.setAttribute("disabled", "true")
        qualifications.setAttribute("disabled", "true")
        requirements.setAttribute("disabled", "true")

        startDateInput.value = ""
        startTimeInput.value = ""
        endDateInput.value = ""
        endTimeInput.value = ""
        qualifications.value = ""
        requirements.value = ""
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
const today = new Date();
today.setMinutes(today.getMinutes() - today.getTimezoneOffset()); // Adjust for the time zone offset
document.getElementById('startDate').setAttribute('min', today.toISOString().split('T')[0]);
document.getElementById('endDate').setAttribute('min', today.toISOString().split('T')[0]);

// Add event listeners to date and time inputs
document.getElementById('startDate').addEventListener('input', checkTimeValidity);
document.getElementById('startTime').addEventListener('input', checkTimeValidity);
document.getElementById('endDate').addEventListener('input', checkTimeValidity);
document.getElementById('endTime').addEventListener('input', checkTimeValidity);

