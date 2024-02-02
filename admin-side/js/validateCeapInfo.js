document.addEventListener('DOMContentLoaded', function () {
    var personalInfoFields = document.getElementById('personal-info-fields');
    var familyBackgroundFields = document.getElementById('family-background-fields');
    var educationalBackgroundFields = document.getElementById('educational-background-fields');
    var saveChangesButton = document.getElementById('saveChanges');

   // Initialize original values
var originalValues = {};

enableSaveChangesOnInput(personalInfoFields, saveChangesButton);
enableSaveChangesOnInput(familyBackgroundFields, saveChangesButton);
enableSaveChangesOnInput(educationalBackgroundFields, saveChangesButton);

function enableSaveChangesOnInput(fieldset, button) {
    var inputFields = fieldset.querySelectorAll('input, select'); // Include select elements

    // Store the original values when the page loads
    inputFields.forEach(function (field) {
        originalValues[field.name] = field.value;
        field.addEventListener('input', checkChanges);
        if (field.tagName === 'SELECT') {
            field.addEventListener('change', checkChanges); // Listen for change events on select elements
        }
    });

    function checkChanges() {
        // Disable the button if any input field has the "invalid" class
        var hasInvalidClass = Array.from(inputFields).some(function (field) {
            return field.classList.contains("invalid");
        });

        if (hasInvalidClass) {
            button.setAttribute("disabled", true);
            console.log('Save Changes button is now disabled due to invalid input.');
            return;
        }

        // Enable the button if any value has changed
        var changesDetected = Array.from(inputFields).some(function (field) {
            return field.value !== originalValues[field.name];
        });

        if (changesDetected) {
            button.removeAttribute("disabled");
            console.log('Save Changes button is now enabled.');
        } else {
            // Disable the button if there are no changes
            button.setAttribute("disabled", true);
            console.log('Save Changes button is now disabled.');
        }
    }
}
});

const fieldsToValidate = document.querySelectorAll('input[name="last_name"], input[name="first_name"]');

fieldsToValidate.forEach(input => {
    
    const errorSpan = document.getElementById(input.getAttribute('name') + '_error');

    input.addEventListener("change", function() {
        
        this.value = this.value.replace(/[^a-zA-Z0-9\s-]/g, ''); // Allow letters, numbers, spaces, and dashes

        const words = this.value.split(/\s+/); // Split by spaces
        const sanitizedWords = words.map(word => {
            return word.replace(/-+/g, '-') // Remove multiple dashes in a row
                       .replace(/\s+/g, ' '); // Remove multiple spaces in a row
        });
        const finalValue = sanitizedWords.join(' '); // Rejoin words with spaces
        
        const minLength = 2;
        const maxLength = 25;
        
        if (finalValue.length >= minLength && finalValue.length <= maxLength) {
            this.classList.remove('invalid');
            errorSpan.textContent = ''; // Clear the error message
        } else {
            this.classList.add('invalid');
            errorSpan.textContent = 'Invalid input.'; // Display the error message
        }

    });
    
    input.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value === '') {
            this.classList.remove('invalid');
        } else {
            const sanitizedValue = value.replace(/[^a-zA-Z0-9\s-]/g, '');
            const words = sanitizedValue.split(/\s+/);
            const sanitizedWords = words.map(word => {
                return word.replace(/-+/g, '-').replace(/\s+/g, ' ');
            });
            const finalValue = sanitizedWords.join(' ');
            
            const minLength = 2;
            const maxLength = 25;
            if (finalValue.length >= minLength && finalValue.length <= maxLength) {
                this.value = finalValue;
                this.classList.remove('invalid');
                errorSpan.textContent = ''; // Clear the error message
            } else {
                this.classList.add('invalid');
                errorSpan.textContent = 'Invalid input length.'; // Display the error message
            }
    
        }
    });
});

const emailInput = document.getElementById('active_email_address');
let emailIsValid = false; // Flag to track email validation status

// Variable to track if the field has been blurred
let emailFieldBlurred = false;

emailInput.addEventListener("input", () => {
    const value = emailInput.value;
    const sanitizedValue = value.replace(/[^a-zA-Z0-9@._+-]/g, '');
    emailInput.value = sanitizedValue;

    const errorSpan = document.getElementById('active_email_address_error'); // Get the error message span

    // Check if the email input is valid
    if (sanitizedValue.length === 0 || /^[\w\.-]+@\w+\.\w+$/.test(sanitizedValue) && sanitizedValue.endsWith('.com')) {
        emailIsValid = true;
        errorSpan.textContent = ''; // Clear the error message
    } else {
        emailIsValid = false;
        if (emailFieldBlurred) {
            errorSpan.textContent = 'Invalid email address.'; // Display the error message if the field has been blurred
        }
    }
});

emailInput.addEventListener('blur', () => {
    const errorSpan = document.getElementById('active_email_address_error'); // Get the error message span
    if (!emailIsValid) {
        emailInput.classList.add('invalid'); // Add "invalid" class if email is invalid when leaving the field
        errorSpan.textContent = 'Invalid email address.'; // Display the error message
    } else {
        emailInput.classList.remove('invalid'); // Remove "invalid" class if email is valid when leaving the field
        errorSpan.textContent = ''; // Clear the error message
    }
    emailFieldBlurred = true; // Set the flag to true when the field is blurred
});

const contactNumberInput = document.getElementById('contact_number');
let contactNumberIsValid = false; // Flag to track contact number validation status

contactNumberInput.addEventListener('input', (event) => {
    // Remove any non-digit characters from the input
    contactNumberInput.value = contactNumberInput.value.replace(/[^0-9]/g, '');

    // Limit the input to exactly 13 digits
    if (contactNumberInput.value.length > 13) {
        contactNumberInput.value = contactNumberInput.value.slice(0, 13);
    }

    // Format with hyphens after the fourth and seventh digits
    if (contactNumberInput.value.length >= 2) {
        contactNumberInput.value = '09' + contactNumberInput.value.slice(2);
    }
    if (contactNumberInput.value.length >= 5) {
        contactNumberInput.value =
            contactNumberInput.value.slice(0, 4) + '-' + contactNumberInput.value.slice(4);
    }
    if (contactNumberInput.value.length >= 10) {
        contactNumberInput.value =
            contactNumberInput.value.slice(0, 9) + '-' + contactNumberInput.value.slice(9);
    }

    // Prevent modification of initial "09"
    if (contactNumberInput.selectionStart < 2) {
        event.preventDefault();
        contactNumberInput.setSelectionRange(2, 2);
    }

    // Check if the contact number input is valid
    if (contactNumberInput.value.startsWith('09') && contactNumberInput.value.length === 13) {
        contactNumberIsValid = true;
    } else {
        contactNumberIsValid = false;
    }

    // Validate the contact number after modification
    validateContactNumber();
});

// Add event listeners to contact number fields
document.getElementById('contact_number').addEventListener('change', validateContactNumber);

function validateContactNumber() {
    var contactNumberInputFormat = document.getElementById('contact_number');
    var contactNumberError = document.getElementById('contact_number_error');

    if (contactNumberIsValid) {
        contactNumberError.textContent = '';
        contactNumberError.style.display = 'none';
        contactNumberInputFormat.classList.remove('invalid');
    } else {
        contactNumberError.textContent = 'Invalid contact number';
        contactNumberError.style.display = 'block';
        contactNumberInputFormat.classList.add('invalid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate())
        .toISOString()
        .split('T')[0];
    dateOfBirthInput.setAttribute('max', maxDate);
    dateOfBirthInput.addEventListener("change", function() {
        const dob = new Date(this.value);
        let age = today.getFullYear() - dob.getFullYear();

        // Check if the birthday has not occurred yet this year
        if (
            today.getMonth() < dob.getMonth() || 
            (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())
        ) {
            age--;
        }

        document.getElementById('age').value = age;
    });
});

// Validate house number
const houseNumberInput = document.getElementById("house_number");

houseNumberInput.addEventListener("change", function () {
    validateInput(this);
});

houseNumberInput.addEventListener("focus", function () {
    this.classList.remove("invalid");
});

// Validate place of birth
const placeOfBirthInput = document.getElementById("place_of_birth");

placeOfBirthInput.addEventListener("change", function () {
    validateInput(this);
});

placeOfBirthInput.addEventListener("focus", function () {
    this.classList.remove("invalid");
});

// Function to validate input with minimum length
function validateInput(inputElement) {
    const inputValue = inputElement.value;

    if (inputValue.trim().length < 5) { // Check for minimum length of 5 characters
        inputElement.classList.add("invalid");
        const errorSpanId = inputElement.getAttribute("id") + "_error";
        const errorSpan = document.getElementById(errorSpanId);
        errorSpan.textContent = "At lest 5 characters.";
    } else {
        inputElement.classList.remove("invalid");
        const errorSpanId = inputElement.getAttribute("id") + "_error";
        const errorSpan = document.getElementById(errorSpanId);
        errorSpan.textContent = ""; // Clear the error message
    }

}

const suffixes  = [
    'N/A','JR', 'SR','I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
    'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX',
    'XXI', 'XXII', 'XXIII', 'XXIV', 'XXV', 'XXVI', 'XXVII', 'XXVIII', 'XXIX', 'XXX',
    'XXXI', 'XXXII', 'XXXIII', 'XXXIV', 'XXXV', 'XXXVI', 'XXXVII', 'XXXVIII', 'XXXIX', 'XL',
    'XLI', 'XLII', 'XLIII', 'XLIV', 'XLV', 'XLVI', 'XLVII', 'XLVIII', 'XLIX', 'L',
    'LI', 'LII', 'LIII', 'LIV', 'LV', 'LVI', 'LVII', 'LVIII', 'LIX', 'LX',
    'LXI', 'LXII', 'LXIII', 'LXIV', 'LXV', 'LXVI', 'LXVII', 'LXVIII', 'LXIX', 'LXX',
    'LXXI', 'LXXII', 'LXXIII', 'LXXIV', 'LXXV', 'LXXVI', 'LXXVII', 'LXXVIII', 'LXXIX', 'LXXX',
    'LXXXI', 'LXXXII', 'LXXXIII', 'LXXXIV', 'LXXXV', 'LXXXVI', 'LXXXVII', 'LXXXVIII', 'LXXXIX', 'XC',
    'XCI', 'XCII', 'XCIII', 'XCIV', 'XCV', 'XCVI', 'XCVII', 'XCVIII', 'XCIX', 'C'
];

// Suffix input element
const suffixInput = document.getElementById('suffix_name');
// Suffix options container
const suffixOptionsContainer = document.getElementById('suffix_options');

// Event listener for input changes
suffixInput.addEventListener("change", () => {
    const enteredText = suffixInput.value.trim().toUpperCase();
    const matchingSuffixes = suffixes.filter(suffix => suffix.startsWith(enteredText));
    
    if (enteredText.length === 0 || matchingSuffixes.length === 0) {
        suffixOptionsContainer.style.display = 'none';
        suffixOptionsContainer.innerHTML = '';
        // Clear the input field for invalid suffixes
        suffixInput.value = '';
    } else {
        suffixOptionsContainer.style.display = 'block';
        suffixOptionsContainer.innerHTML = '';
        matchingSuffixes.forEach(suffix => {
            const option = document.createElement('div');
            option.className = 'suffix-option';
            option.textContent = suffix;
            option.addEventListener('click', () => {
                suffixInput.value = suffix;
                suffixOptionsContainer.style.display = 'none';
            });
            suffixOptionsContainer.appendChild(option);
        });
    }
});

// Close the options when clicking outside
document.addEventListener('click', (event) => {
    if (!suffixOptionsContainer.contains(event.target) && event.target !== suffixInput) {
        suffixOptionsContainer.style.display = 'none';
    }
});

// Select all input and textarea elements with placeholders
const inputElements = document.querySelectorAll('input[placeholder]');

// Add event listeners for focus and blur
inputElements.forEach(input => {
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
    input.addEventListener("change", () => {
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
    $("#no_of_units").on("change", function() {
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

courseEnrolledInput.addEventListener("change", function() {
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
    
});

function validateInputLength(inputElementId, errorElementId, minLength) {
    const inputElement = document.getElementById(inputElementId);
    const errorElement = document.getElementById(errorElementId);

    inputElement.addEventListener("change", function() {
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

guardianFirstNameInput.addEventListener("change", function () {
    validateInputWithMinLength(guardianFirstNameInput, 2, "guardian_firstname_error");
});

guardianLastNameInput.addEventListener("change", function () {
    validateInputWithMinLength(guardianLastNameInput, 2, "guardian_lastname_error");
});

guardianRelationshipInput.addEventListener("change", function () {
    validateInputWithMinLength(guardianRelationshipInput, 5, "guardian_relationship_error");
});

guardianOccupationInput.addEventListener("change", function () {
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