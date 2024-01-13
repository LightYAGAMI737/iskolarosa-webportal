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
    validateInputWithMinLength(guardianOccupationInput, 4, "guardian_occupation_error");
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
    const submitButton = document.getElementById('nextButtonStep_three');
    const isMonthlyIncomeValid = !isNaN(monthlyIncomeInput.value) && parseFloat(monthlyIncomeInput.value) <= 25000;
    submitButton.disabled = !areFieldsValid(requiredFields) || !isMonthlyIncomeValid;
}

// Add event listeners to show/hide tooltips on hover of the Next button
const submitButton = document.getElementById('nextButtonStep_three');
submitButton.addEventListener('mouseenter', () => {
    displayTooltipStepThree();
});
submitButton.addEventListener('mouseleave', () => {
    hideTooltipStepThree();
});

// Function to hide the tooltip for step three
function hideTooltipStepThree() {
    const tooltips = document.getElementById('tooltips_step_three');
    tooltips.style.visibility = 'hidden';
    tooltips.classList.remove('active');
}


// Attach input and blur event listeners to all required fields
for (const field of requiredFields) {
    field.addEventListener('input', updateSubmitButton);
    field.addEventListener('blur', updateSubmitButton);
}

// Initial check to disable/enable the submit button
updateSubmitButton();



// Function to display tooltips with required fields that have no value for step_three
function displayTooltipStepThree() {
    const tooltips = document.getElementById('tooltips_step_three');
    const requiredFields = document.querySelectorAll('#step_three [required]');

    const tooltipsData = [
        { field: 'uploadResidency', tooltip: 'Guardian\'s Residency' },
        { field: 'uploadITR', tooltip: 'Guardian\'s ITR' },
        { field: 'uploadVotersParent', tooltip: 'Guardian\'s Voter\'s Certificate' },
        { field: 'guardian_occupation', tooltip: 'Guardian\'s Occupation' },
    ];

    const missingRequiredFieldNames = Array.from(requiredFields)
        .filter(field => !field.value.trim())
        .map(field => {
            const fieldName = field.getAttribute('name'); // Use name attribute for mapping
            const tooltipObj = tooltipsData.find(item => item.field === fieldName);
            if (tooltipObj) {
                return tooltipObj.tooltip;
            } else {
                const previousElement = field.previousElementSibling;
                return previousElement ? previousElement.textContent.trim() : '';
            }
        });

    // Combine missing field names with line breaks
    const tooltipText = missingRequiredFieldNames.length > 0 ? missingRequiredFieldNames.join("\n") : "";

    if (missingRequiredFieldNames.length > 0) {
        tooltips.textContent = tooltipText;
        tooltips.style.visibility = 'visible';
        tooltips.classList.add('active');
    } else {
        hideTooltipStepThree(); // Hide the tooltip if there are no missing fields
    }
}

// Select all input and textarea elements with placeholders
const inputElementsThree = document.querySelectorAll('input[placeholder]');

// Add event listeners for focus and blur
inputElementsThree.forEach(input => {
    input.addEventListener('focus', function () {
        // Store the placeholder attribute in a data attribute only if it exists
        if (this.getAttribute('placeholder')) {
            this.setAttribute('data-placeholder', this.getAttribute('placeholder'));
            this.removeAttribute('placeholder'); // Remove the placeholder attribute
        }
    });

    input.addEventListener('blur', function () {
        // Restore the placeholder attribute from the data attribute if it exists
        if (this.getAttribute('data-placeholder')) {
            this.setAttribute('placeholder', this.getAttribute('data-placeholder'));
        }
    });
});
