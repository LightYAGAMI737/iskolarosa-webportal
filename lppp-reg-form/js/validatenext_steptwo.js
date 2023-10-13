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

