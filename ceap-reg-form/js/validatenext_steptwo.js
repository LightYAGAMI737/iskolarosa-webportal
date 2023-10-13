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


//validate year graduated
document.addEventListener('DOMContentLoaded', function() {
    const elementaryYearSelect = document.querySelector('select[name="elementary_year"]');
    const secondaryYearSelect = document.querySelector('select[name="secondary_year"]');
    const seniorHighYearSelect = document.querySelector('select[name="senior_high_year"]');
    const expectedGraduationSelect = document.querySelector('select[name="expected_year_of_graduation"]');

    
    const currentYear = new Date().getFullYear();

     // Function to populate a year select with options from minYear to maxYear
     function populateYearSelect(selectElement, minYear, maxYear) {
        selectElement.innerHTML = ''; // Clear previous options
        for (let year = maxYear; year >= minYear; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            selectElement.appendChild(option);
        }
    }
        // Populate secondary and senior high years based on elementary year selection
        elementaryYearSelect.addEventListener('change', function() {
            const selectedYear = parseInt(elementaryYearSelect.value);
            populateYearSelect(secondaryYearSelect, selectedYear + 3, currentYear - 2);
            populateYearSelect(seniorHighYearSelect, selectedYear + 5, currentYear);
            populateYearSelect(expectedGraduationSelect, selectedYear + 8, currentYear + 6);
        });
        
            // Populate senior high years based on secondary year selection
            secondaryYearSelect.addEventListener('change', function() {
                const selectedYear = parseInt(secondaryYearSelect.value);
                populateYearSelect(seniorHighYearSelect, selectedYear + 2, currentYear);

                // Populate expected graduation based on senior high year with a minimum of +2 years and maximum of currentYear + 6
                seniorHighYearSelect.addEventListener('change', function() {
                    const selectedYear = parseInt(seniorHighYearSelect.value);
                const minYear = selectedYear + 2; // Minimum +2 years
                const maxYear = currentYear + 6; // Maximum currentYear + 6 years
                populateYearSelect(expectedGraduationSelect, minYear, maxYear);
            });
        });
                
        const initialElementaryYear = parseInt(elementaryYearSelect.value);
        populateYearSelect(secondaryYearSelect, initialElementaryYear + 3, currentYear - 2);
        
        const initialSecondaryYear = parseInt(secondaryYearSelect.value);
        populateYearSelect(seniorHighYearSelect, initialSecondaryYear + 2, currentYear);
        
        const initialSeniorHighYear = parseInt(seniorHighYearSelect.value);
        populateYearSelect(expectedGraduationSelect, initialSeniorHighYear + 2, currentYear + 6);
    
});


// Select all input elements with the attribute 'name' set to 'elementary_school', 'secondary_school', 'senior_high_school', or 'school_name'
const schoolGraduatedInputs = document.querySelectorAll('input[name="elementary_school"], input[name="secondary_school"], input[name="senior_high_school"], input[name="school_name"]');

schoolGraduatedInputs.forEach(input => {
    input.addEventListener('input', () => {
        const value = input.value;
        // Replace any consecutive spaces with a single space
        const sanitizedValue = value.replace(/\s+/g, ' ')
            .replace(/[^a-zA-Z0-9#&(),\-.\s]/g, ''); // Allow only specified characters
        
        // Check if the sanitized value meets the minimum length requirement
        if (sanitizedValue.length >= 5) {
            input.value = sanitizedValue;
            input.classList.remove('invalid'); // Remove 'invalid' class if valid
            const errorSpanId = input.getAttribute("name") + "_error";
            const errorSpan = document.getElementById(errorSpanId);
            errorSpan.textContent = ""; // Clear the error message
        } else {
            input.classList.add('invalid'); // Add 'invalid' class if too short
            const errorSpanId = input.getAttribute("name") + "_error";
            const errorSpan = document.getElementById(errorSpanId);
            errorSpan.textContent = "At least 5 characters."; // Updated error message
        }
    });
});


// validate units
const graduatingSelect = document.getElementById("graduating");
const unitsInput = document.getElementById("no_of_units");
const unitsErrorSpan = document.getElementById("no_of_units_error"); // Get the error message span

graduatingSelect.addEventListener("change", validateUnits);
unitsInput.addEventListener("input", validateUnits);

function validateUnits() {
    const graduating = graduatingSelect.value;
    const units = parseInt(unitsInput.value);

    if (graduating === "no" && units <= 14) {
        unitsInput.classList.add("invalid");
        unitsErrorSpan.textContent = "Minimum of 15 units required.";
        unitsErrorSpan.style.fontSize = "13px"; 
    } else if (graduating === "yes" && units <= 5) {
        unitsInput.classList.add("invalid");
        unitsErrorSpan.textContent = "Minimum of 6 units required.";
        unitsErrorSpan.style.fontSize = "13px"; 
    } else {
        unitsInput.classList.remove("invalid");
        unitsErrorSpan.textContent = ""; // Clear the error message
    }
}


 $(document).ready(function() {
    $("#no_of_units").on("input", function() {
        // Remove non-numeric characters
        $(this).val($(this).val().replace(/\D/g, ""));
        
        // Limit to maximum of 2 characters
        if ($(this).val().length > 2) {
            $(this).val($(this).val().substr(0, 2));
        }
    });
});
const courseEnrolledInput = document.getElementById("course_enrolled");
const courseEnrolledErrorSpan = document.getElementById("course_enrolled_error");

courseEnrolledInput.addEventListener("input", function() {
    const inputValue = courseEnrolledInput.value;
    const sanitizedValue = inputValue.replace(/[^a-zA-Z\s]/g, "");
    courseEnrolledInput.value = sanitizedValue;
    
    if (sanitizedValue !== inputValue) {
        courseEnrolledInput.classList.add('invalid');
        courseEnrolledErrorSpan.textContent = "Invalid input.";
    } else if (sanitizedValue.length < 5) {
        courseEnrolledInput.classList.add('invalid');
        courseEnrolledErrorSpan.textContent = "At least 5 characters.";
    } else {
        courseEnrolledInput.classList.remove('invalid');
        courseEnrolledErrorSpan.textContent = ""; // Clear the error message
    }
    
    updateNextButtonTwoStatus();
});

function validateInputLength(inputElementId, errorElementId, minLength) {
    const inputElement = document.getElementById(inputElementId);
    const errorElement = document.getElementById(errorElementId);

    inputElement.addEventListener("input", function() {
        const inputValue = inputElement.value;

        if (inputValue.length < minLength) {
            inputElement.classList.add('invalid');
            errorElement.textContent = `At least ${minLength} characters.`;
        } else {
            inputElement.classList.remove('invalid');
            errorElement.textContent = ""; // Clear the error message
        }
    });
}

// Usage:
validateInputLength("school_address", "school_address_error", 5);
validateInputLength("student_id_no", "student_id_no_error", 5);



// Validate file uploads
const currentGradeInput = document.querySelector('input[name="uploadGrade"]');
const corInput = document.querySelector('input[name="uploadCOR"]');

currentGradeInput.addEventListener('change', () => {
    const errorSpan = document.getElementById('uploadGrade_error'); // Get the error message span
    validateAndHandleFileUpload(currentGradeInput, 'application/pdf', 1024 * 1024, "PDF file", errorSpan);
});

corInput.addEventListener('change', () => {
    const errorSpan = document.getElementById('uploadCOR_error'); // Get the error message span
    validateAndHandleFileUpload(corInput, 'application/pdf', 1024 * 1024, "PDF file", errorSpan);
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
        errorSpan.textContent = `Please upload a ${label} (up to 1MB).`; // Display the error message
        input.value = ''; // Clear the file field
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
