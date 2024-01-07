// Function to check if all required fields are valid
function areFieldsValid(fields) {
    for (const field of fields) {
        if (field.classList.contains('invalid') || field.value.trim() === '') {
            return false;
        }
    }
    return true;
    
}
// Get all the required fields for step one
const stepOneFields = document.querySelectorAll('#step_one input[required], #step_one select[required]');

// Function to enable/disable the next button based on validation
function updateNextButtonStatus() {
    const nextButton = document.getElementById('nextButtonStep_One');
    nextButton.disabled = !areFieldsValid(stepOneFields);
    nextButton.addEventListener('mouseenter', displayTooltip);
    nextButton.addEventListener('mouseleave', () => {
        const tooltips = document.getElementById('tooltips');
        tooltips.style.visibility = 'hidden';
    });
}

// Attach input and blur event listeners to step one fields
stepOneFields.forEach(field => {
    field.addEventListener('input', updateNextButtonStatus);
    field.addEventListener('change', updateNextButtonStatus);
});

const fieldsToValidate = document.querySelectorAll('input[name="last_name"], input[name="first_name"]');

fieldsToValidate.forEach(input => {
    
    const errorSpan = document.getElementById(input.getAttribute('name') + '_error');

    input.addEventListener('input', function() {
        
        // Replace special characters other than '-' and space in real-time
        this.value = this.value.replace(/[^a-zA-Z0-9\s-]/g, ''); // Allow letters, numbers, spaces, and dashes

        // Enforce one space and one dash per word
        const words = this.value.split(/\s+/); // Split by spaces
        const sanitizedWords = words.map(word => {
            return word.replace(/-+/g, '-') // Remove multiple dashes in a row
                       .replace(/\s+/g, ' '); // Remove multiple spaces in a row
        });
        const finalValue = sanitizedWords.join(' '); // Rejoin words with spaces
        
        // Validate dynamic min length and max length
        const minLength = 2;
        const maxLength = 25;
        
        if (finalValue.length >= minLength && finalValue.length <= maxLength) {
            this.classList.remove('invalid');
            errorSpan.textContent = ''; // Clear the error message
        } else {
            this.classList.add('invalid');
            errorSpan.textContent = 'Invalid input.'; // Display the error message
        }
        updateNextButtonStatus();
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
            updateNextButtonStatus();
        }
    });

});
const emailInput = document.getElementById('active_email_address');
let emailIsValid = false; // Flag to track email validation status

// Variable to track if the field has been blurred
let emailFieldBlurred = false;

emailInput.addEventListener('input', () => {
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
    updateNextButtonStatus();
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
    updateNextButtonStatus();
});

const contactNumberInput = document.getElementById('contact_number');
let contactNumberIsValid = false; // Flag to track contact number validation status

contactNumberInput.addEventListener('input', () => {
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
    updateNextButtonStatus();
});


document.addEventListener('DOMContentLoaded', function() {
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 13, today.getMonth(), today.getDate())
        .toISOString()
        .split('T')[0];
    dateOfBirthInput.setAttribute('max', maxDate);
    dateOfBirthInput.addEventListener('input', function() {
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
    updateNextButtonStatus();
});

// Validate file uploads
const pictureInput = document.querySelector('input[name="uploadPhotoJPG"]');

pictureInput.addEventListener('change', () => {
    const file = pictureInput.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    const errorSpan = document.getElementById('uploadPhotoJPG_error'); // Get the error message span

    if (file && (file.type === 'image/jpeg' || file.type === 'image/jpg') && file.size <= maxSize) {
        pictureInput.classList.remove('invalid');
        errorSpan.textContent = ''; // Clear the error message
    } else {
        pictureInput.classList.add('invalid');
        errorSpan.textContent = 'Upload a valid JPEG/JPG image (up to 2MB).'; // Display the error message
        pictureInput.value = null; // Clear the file field
    }
    updateNextButtonStatus();
});

// Validate house number
const houseNumberInput = document.getElementById("house_number");

houseNumberInput.addEventListener("blur", function () {
    validateInput(this);
});

houseNumberInput.addEventListener("focus", function () {
    this.classList.remove("invalid");
});

// Validate place of birth
const placeOfBirthInput = document.getElementById("place_of_birth");

placeOfBirthInput.addEventListener("blur", function () {
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
    updateNextButtonStatus();

}

const suffixes  = [
    'Jr', 'Sr','I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
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
suffixInput.addEventListener('input', () => {
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

function displayTooltip() {
    const tooltips = document.getElementById('tooltips');
    const requiredFields = document.querySelectorAll('#step_one [required]');

    const tooltipsData = [
        { field: 'uploadPhotoJPG', tooltip: 'Applicant\'s 2x2 Picture' },
    ];

    const missingFieldNames = Array.from(requiredFields)
        .filter(field => !field.value.trim())
        .map(field => {
            const fieldName = field.getAttribute('id');
            const tooltipObj = tooltipsData.find(item => item.field === fieldName);
            if (tooltipObj) {
                return tooltipObj.tooltip;
            } else {
                const previousElement = field.previousElementSibling;
                return previousElement ? previousElement.textContent.trim() : '';
            }
        });

    // Combine missing field names with line breaks
    const tooltipText = (missingFieldNames.length > 0) ?
        missingFieldNames.join("\n") : "";

    if (missingFieldNames.length > 0) {
        tooltips.textContent = tooltipText;
        tooltips.style.visibility = 'visible';
        tooltips.classList.add('active');
    } else {
        tooltips.textContent = "";
        tooltips.style.visibility = 'hidden';
        tooltips.classList.remove('active');
    }
}

// Select all input and textarea elements with placeholders
const inputElements = document.querySelectorAll('input[placeholder]');

// Add event listeners for focus and blur
inputElements.forEach(input => {
    input.addEventListener('focus', function () {
        // Store the placeholder attribute in a data attribute only if it exists
        if (this.getAttribute('placeholder')) {
            this.setAttribute('data-placeholder', this.getAttribute('placeholder'));
            this.removeAttribute('placeholder');
        }
    });

    input.addEventListener('change', function () {
        // Restore the placeholder attribute from the data attribute if it exists
        if (this.getAttribute('data-placeholder')) {
            this.setAttribute('placeholder', this.getAttribute('data-placeholder'));
        }
    });
});

