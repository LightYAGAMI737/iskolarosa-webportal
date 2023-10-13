// Individual input validations
const guardianFirstNameInput = document.getElementById("guardian_firstname");
const guardianLastNameInput = document.getElementById("guardian_lastname");
const guardianOccupationInput = document.getElementById("guardian_occupation");
const guardianRelationshipInput = document.getElementById("guardian_relationship");
const monthlyIncomeInput = document.getElementById('guardian_monthly_income');
const annualIncomeInput = document.getElementById('guardian_annual_income');

// Function to validate input with minimum length and display error message
function validateInputWithMinLength(inputElement, minLength, errorSpanId) {
    const inputValue = inputElement.value;
    const sanitizedValue = inputValue.trim(); // Remove leading and trailing spaces

    if (sanitizedValue.length < minLength) {
        inputElement.classList.add("invalid");
        const lengtherrorSpan = document.getElementById(errorSpanId);
        lengtherrorSpan.textContent = `At least ${minLength} characters.`;
        lengtherrorSpan.style.fontSize = "13px"; 

    } else {
        inputElement.classList.remove("invalid");
        const lengtherrorSpan = document.getElementById(errorSpanId);
        lengtherrorSpan.textContent = ''; // Clear the error message
    }
}

guardianFirstNameInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianFirstNameInput, 2, "guardian_firstname_error");
});

guardianLastNameInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianLastNameInput, 2, "guardian_lastname_error");
});

guardianRelationshipInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianRelationshipInput, 5, "guardian_relationship_error");
});

guardianOccupationInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianOccupationInput, 5, "guardian_occupation_error");
});
    

const errorSpan = document.getElementById("guardian_annual_income_error");

monthlyIncomeInput.addEventListener("input", function () {
    const inputValue = this.value;
    const sanitizedValue = inputValue.replace(/[^\d.]/g, ''); // Replace non-digit and non-dot characters
    const parts = sanitizedValue.split('.');
    if (parts.length > 2) {
        parts.pop();
    }
    this.value = parts.join('.');

    const monthlyIncome = parseFloat(this.value);
    const annualIncome = monthlyIncome * 12;
    annualIncomeInput.value = annualIncome.toFixed(2);

    if (annualIncome <= 300000) { // Change the value to 300,000
        monthlyIncomeInput.style.borderColor = ''; // Reset to default border color
        annualIncomeInput.style.borderColor = ''; // Reset to default border color
        annualIncomeInput.classList.remove("invalid");
        errorSpan.textContent = ''; // Clear the error message
    } else {
        monthlyIncomeInput.style.borderColor = '#A5040A';
        annualIncomeInput.style.borderColor = '#A5040A';
        annualIncomeInput.classList.add("invalid");
        errorSpan.textContent = 'Minimum of 300,000.'; // Display the error message
    }

    if (isNaN(monthlyIncome)) {
        monthlyIncomeInput.classList.add("invalid");
    } else {
        monthlyIncomeInput.classList.remove("invalid");
    }
});

const voterCertificateParentInput = document.querySelector('input[name="uploadVotersParent"]');
const itrInput = document.querySelector('input[name="uploadITR"]');
const residencyInput = document.querySelector('input[name="uploadResidency"]');

voterCertificateParentInput.addEventListener("change", handleFileUpload);
itrInput.addEventListener("change", handleFileUpload);
residencyInput.addEventListener("change", handleFileUpload);

function handleFileUpload(event) {
    const fileInput = event.target;
    const maxFileSize = 1024 * 1024; // 1MB in bytes
    const allowedExtensions = [".pdf"];
    const file = fileInput.files[0];

    if (!file) {
        return; // No file selected, do nothing
    }

    const fileSize = file.size;
    const fileExtension = file.name.substring(file.name.lastIndexOf(".")).toLowerCase();

    if (fileSize > maxFileSize || !allowedExtensions.includes(fileExtension)) {
        // Invalid file, update the error message span
        const errorSpanId = fileInput.getAttribute("id") + "_error";
        const errorSpan = document.getElementById(errorSpanId);
        errorSpan.textContent = "Please upload a valid PDF file (up to 1MB).";
        errorSpan.style.fontSize = "13px"; 

        // Optionally, you can add styling to indicate the error visually
        fileInput.classList.add("invalid");

        // Clear the file field
        fileInput.value = null;
    } else {
        // File is valid, clear any previous error message and styling
        const errorSpanId = fileInput.getAttribute("id") + "_error";
        const errorSpan = document.getElementById(errorSpanId);
        errorSpan.textContent = "";

        fileInput.classList.remove("invalid");
    }
}



// Function to check if all required fields are valid
function areFieldsValid(fields) {
    for (const field of fields) {
        if (field.classList.contains('invalid') || field.value.trim() === '') {
            return false;
        }
    }
    return true;
}

// Get all the required fields
const requiredFields = [
    guardianFirstNameInput,
    guardianLastNameInput,
    guardianOccupationInput,
    guardianRelationshipInput,
    monthlyIncomeInput,
    voterCertificateParentInput, // Add the file inputs here
    itrInput,
    residencyInput
];

// Function to enable/disable the submit button based on validation
function updateSubmitButton() {
    const submitButton = document.getElementById('popupsubmit');
    const isMonthlyIncomeValid = !isNaN(monthlyIncomeInput.value) && parseFloat(monthlyIncomeInput.value) <= 25000;
    submitButton.disabled = !areFieldsValid(requiredFields) || !isMonthlyIncomeValid;
}

// Attach input and blur event listeners to all required fields
for (const field of requiredFields) {
    field.addEventListener('input', updateSubmitButton);
    field.addEventListener('blur', updateSubmitButton);
}

// Initial check to disable/enable the submit button
updateSubmitButton();

// Handle form submission
const submitButton = document.getElementById('submit');
submitButton.addEventListener('click', function (event) {
   
        // Create a new FormData object to collect form data
        const formData = new FormData(document.getElementById('msform'));

        // Perform the AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'ceapregformdatabaseinsert.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // AJAX request was successful, handle the response as needed
                    console.log(xhr.responseText); // Log the response for debugging
                    // Re-enable the submit button after a successful submission
                    submitButton.disabled = false;
                } else {
                    // AJAX request encountered an error, handle it as needed
                    console.error('AJAX Error:', xhr.status, xhr.statusText);
                    // You might want to provide user feedback about the error.
                }
        };
        
        // Send the form data
        xhr.send(formData);
    }
});

