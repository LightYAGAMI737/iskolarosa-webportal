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
        var inputFields = fieldset.querySelectorAll('input');

        // Store the original values when the page loads
        inputFields.forEach(function (field) {
            originalValues[field.name] = field.value;
            field.addEventListener('input', checkChanges);
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

contactNumberInput.addEventListener("input", () => {
    const value = contactNumberInput.value;
    const sanitizedValue = value.replace(/[^0-9]/g, '');
    if (sanitizedValue.length > 11) {
        contactNumberInput.value = sanitizedValue.slice(0, 11);
    } else {
        contactNumberInput.value = sanitizedValue;
    }

    // Check if the contact number input is valid
    if (sanitizedValue.startsWith('09') && sanitizedValue.length === 11) {
        contactNumberIsValid = true;
    } else {
        contactNumberIsValid = false;
    }
});

contactNumberInput.addEventListener('blur', () => {
    const errorSpan = document.getElementById('contact_number_error'); // Get the error message span
    if (!contactNumberIsValid) {
        contactNumberInput.classList.add('invalid'); // Add "invalid" class if contact number is invalid when leaving the field
        errorSpan.textContent = 'Invalid contact number.'; // Display the "invalid" error message
    } else {
        contactNumberInput.classList.remove('invalid'); // Remove "invalid" class if contact number is valid when leaving the field
        errorSpan.textContent = ''; // Clear the error message
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 13, today.getMonth(), today.getDate())
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


// Select all input elements with the attribute 'name' set to 'elementary_school', 'secondary_school', 'senior_high_school', or 'school_name'
const schoolGraduatedInputs = document.querySelectorAll('input[name="elementary_school"]');

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

