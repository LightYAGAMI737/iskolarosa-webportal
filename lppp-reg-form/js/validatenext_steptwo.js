// Validate fields in real-time

// Function to check if all required fields are valid
function areFieldsValid(fields) {
    for (const field of fields) {
        if (field.classList.contains('invalid') || field.value.trim() === '') {
            return false;
        }
    }
    return true;
    
}

// Get all the required fields for step two
const steptwoFields = document.querySelectorAll('#step_two input[required], #step_two select[required]');

// Function to enable/disable the next button based on validation
function updateNextButtonTwoStatus() {
    const nextButton = document.getElementById('nextButtonStep_two');
    nextButton.disabled = !areFieldsValid(steptwoFields);
}

// Add event listeners to show/hide tooltips on hover of the Next button
const nextButton = document.getElementById('nextButtonStep_two');
nextButton.addEventListener('mouseenter', () => {
    displayTooltipStepTwo();
});
nextButton.addEventListener('mouseleave', () => {
    hideTooltipStepTwo();
});

// Function to hide the tooltip for step two
function hideTooltipStepTwo() {
    const tooltips = document.getElementById('tooltips_step_two');
    tooltips.style.visibility = 'hidden';
    tooltips.classList.remove('active');
}

// Attach input and blur event listeners to step two fields
steptwoFields.forEach(field => {
    field.addEventListener('input', updateNextButtonTwoStatus);
    field.addEventListener('blur', updateNextButtonTwoStatus);
});



// Validate file uploads
const currentGradeInput = document.querySelector('input[name="uploadGrade"]');
const corInput = document.querySelector('input[name="uploadCOR"]');

currentGradeInput.addEventListener('change', () => {
    const errorSpan = document.getElementById('uploadGrade_error'); // Get the error message span
    validateAndHandleFileUpload(currentGradeInput, 'application/pdf', 1024 * 1024, "PDF file", errorSpan);
});

function validateAndHandleFileUpload(input, allowedTypes, maxSize, label, errorSpan) {
    const file = input.files[0];

    if (!file) {
        input.classList.remove('invalid');
        errorSpan.textContent = ''; // Clear the error message
        return;
    }

    if (file.type && allowedTypes.split(',').includes(file.type) && file.size <= maxSize) {
        input.classList.remove('invalid');
        errorSpan.textContent = ''; // Clear the error message
    } else {
        input.classList.add('invalid');
        errorSpan.textContent = `Please upload a valid ${label} (up to 1MB).`; // Display the error message
        input.value = null; // Clear the file field
    }
    updateNextButtonTwoStatus();
}


// Function to display tooltips with required fields that have no value for step_two
function displayTooltipStepTwo() {
    const tooltips = document.getElementById('tooltips_step_two');
    const requiredFields = document.querySelectorAll('#step_two [required]');

    const tooltipsData = [
        { field: 'elementary_school', tooltip: 'School Graduated Elementary' },
        { field: 'secondary_school', tooltip: 'School Graduated Secondary' },
        { field: 'senior_high_school', tooltip: 'School Graduated Senior High' },
        { field: 'elementary_year', tooltip: 'Year Graduated Elementary' },
        { field: 'secondary_year', tooltip: 'Year Graduated Secondary' },
        { field: 'senior_high_year', tooltip: 'Year Graduated Senior High' },
        { field: 'school_name', tooltip: 'Full School Name' },
        { field: 'uploadGrade', tooltip: 'Applicant\'s Grades' },
        { field: 'uploadCOR', tooltip: 'Applicant\'s COR' },
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
        hideTooltipStepTwo(); // Hide the tooltip if there are no missing fields
    }
}
// Select all input and textarea elements with placeholders
const inputElementstwo = document.querySelectorAll('input[placeholder]');

// Add event listeners for focus and blur
inputElementstwo.forEach(input => {
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
