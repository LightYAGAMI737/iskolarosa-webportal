// Function to check the toggle state using XHR
function checkToggleState() {
    const xhr = new XMLHttpRequest();

    xhr.open('GET', '../../admin-side/php/checkToggleState.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = xhr.responseText.trim();

                // Check if the toggle_value is 1
                if (response === '1') {
                    console.log('Toggle value is 1.');
                    const inputElements = document.querySelectorAll('input, select');

                    // Set the "readonly" attribute for each element
                    inputElements.forEach(element => {
                        element.removeAttribute('disabled', true);
                    });
                } else if (response === '0') {
                    console.error('Toggle value is 0. Making inputs readonly.');

                    // Get all input and select elements
                    const inputElements = document.querySelectorAll('input, select');

                    // Set the "readonly" attribute for each element
                    inputElements.forEach(element => {
                        element.setAttribute('disabled', true);
                    });
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


const contactNumberInput = document.getElementById('contact_number');
const emailInput = document.getElementById('active_email_address');
const pageOneBtn = document.getElementById('pageOneBtn');

contactNumberInput.addEventListener('keydown', (event) => {
    const isNumericInput = (event.key >= '0' && event.key <= '9') || event.key === 'Backspace' || event.key === 'Delete' || event.key === 'ArrowLeft' || event.key === 'ArrowRight';

    if (!isNumericInput) {
        event.preventDefault();
    }
});

contactNumberInput.addEventListener('change', () => {
    validateContactNumber();
    checkValidity();
});

emailInput.addEventListener('change', () => {
    validateEmail();
    checkValidity();
});

function validateContactNumber() {
    const errorSpan = document.getElementById('contact_number_error');

    const value = contactNumberInput.value;
    const sanitizedValue = value.replace(/[^0-9]/g, '');

    if (sanitizedValue.startsWith('09') && sanitizedValue.length === 11) {
        contactNumberInput.classList.remove('invalid');
        errorSpan.textContent = '';
    } else {
        contactNumberInput.classList.add('invalid');
        errorSpan.textContent = 'Invalid contact number.';
    }
}

function validateEmail() {
    const errorSpan = document.getElementById('active_email_address_error');

    const value = emailInput.value;
    const sanitizedValue = value.replace(/[^a-zA-Z0-9@._+-]/g, '');

    if (sanitizedValue.length === 0 || /^[\w\.-]+@\w+\.\w+$/.test(sanitizedValue) && sanitizedValue.endsWith('.com')) {
        emailInput.classList.remove('invalid');
        errorSpan.textContent = '';
    } else {
        emailInput.classList.add('invalid');
        errorSpan.textContent = 'Invalid email address.';
    }
}

// Add this to your existing JavaScript code
const religionInput = document.getElementById('religion');
const houseNumberInput = document.getElementById('house_number');

religionInput.addEventListener('change', () => {
    validateMinLength(religionInput, 2, 'religion_error');
    checkValidity();
});

houseNumberInput.addEventListener('change', () => {
    validateMinLength(houseNumberInput, 2, 'house_number_error');
    checkValidity();
});

function validateMinLength(inputElement, minLength, errorSpanId) {
    const errorSpan = document.getElementById(errorSpanId);
    const value = inputElement.value;

    if (value.length >= minLength) {
        inputElement.classList.remove('invalid');
        errorSpan.textContent = '';
    } else {
        inputElement.classList.add('invalid');
        errorSpan.textContent = `Minimum length is ${minLength}.`;
    }
}

function checkValidity() {
    const invalidElements = document.getElementsByClassName('invalid');
    pageOneBtn.disabled = invalidElements.length > 0;
}
