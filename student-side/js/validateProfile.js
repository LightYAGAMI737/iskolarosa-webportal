// Function to check the toggle state and update info using XHR
function checkToggleAndInfo() {
    const xhr = new XMLHttpRequest();

    // Choose the appropriate URL based on your requirement
    xhr.open('GET', '../php/checkUpdateInfo.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const submitUpdatedInfo = document.getElementById('submitUpdatedInfo');
                const first_page = document.getElementById('first-page');
                const page_two = document.getElementById('page-two');
                const page_three = document.getElementById('page-three');
                const page_four = document.getElementById('page-four');
                // Get toggleValue and updatedInfo from the response
                const toggleValue = response.toggleValue;
                const updatedInfo = response.updatedInfo;

                // Get all input and select elements
                const inputElements = document.querySelectorAll('input, select');

                // Your logic based on toggleValue and updatedInfo
                if (toggleValue == '1' && updatedInfo == '1') {
                    // If both toggleValue and updatedInfo are 1, setAttribute to disabled, true
                    inputElements.forEach(element => {
                        element.setAttribute('disabled', true);
                        page_four.style.display = "block";
                        submitUpdatedInfo.style.display = "none";                        
                        first_page.style.display = "none";
                        page_two.style.display = "none";
                        page_three.style.display = "none";
                        
                    });
                } else if (toggleValue == '1' && updatedInfo == '0') {
                    // If toggleValue is 1 and updatedInfo is 0, removeAttribute disabled
                    inputElements.forEach(element => {
                        element.removeAttribute('disabled');
                    });
                } else if (toggleValue == '0' && updatedInfo == '0') {
                    // If toggleValue and updatedInfo are 0, setAttribute to disabled, true
                    inputElements.forEach(element => {
                        element.setAttribute('disabled', true);
                        page_four.style.display = "block";
                        submitUpdatedInfo.style.display = "none";                        
                        first_page.style.display = "none";
                        page_two.style.display = "none";
                        page_three.style.display = "none";
                    });
                } else if (toggleValue == '0' && updatedInfo == '1') {
                    // If toggleValue is 0 and updatedInfo is 1, setAttribute to disabled, true
                    inputElements.forEach(element => {
                        element.setAttribute('disabled', true);
                        page_four.style.display = "block";
                        submitUpdatedInfo.style.display = "none";                        
                        first_page.style.display = "none";
                        page_two.style.display = "none";
                        page_three.style.display = "none";
                    });
                }

                console.log('Toggle value:', toggleValue);
                console.log('Updated info:', updatedInfo);
            } else {
                console.error(`Error: XMLHttpRequest failed with status ${xhr.status}.`);
            }
        }
    };

    xhr.send();
}

// Call the function to check the toggle state and update info
checkToggleAndInfo();



const contactNumberInput = document.getElementById('contact_number');
const emailInput = document.getElementById('active_email_address');
const pageOneBtn = document.getElementById('pageOneBtn');
const pageTwoBtn = document.getElementById('pageTwoBtn');
const pageThreeBtn = document.getElementById('pageThreeBtn');

contactNumberInput.addEventListener('keydown', (event) => {
    const isNumericInput = (event.key >= '0' && event.key <= '9') || event.key === 'Backspace' || event.key === 'Delete' || event.key === 'ArrowLeft' || event.key === 'ArrowRight';

    if (!isNumericInput) {
        event.preventDefault();
    }
});

contactNumberInput.addEventListener('change', () => {
    validateContactNumber();
    checkValidityForPageOne();
});

emailInput.addEventListener('change', () => {
    validateEmail();
    checkValidityForPageOne();
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


// validate units
const graduatingSelect = document.getElementById("graduating");
const unitsInput = document.getElementById("no_of_units");
const unitsErrorSpan = document.getElementById("no_of_units_error"); // Get the error message span

graduatingSelect.addEventListener("change", validateUnits);
unitsInput.addEventListener("change", validateUnits);

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
    checkValidityForPageTwo();
}

    const noOfUnitsInput = document.getElementById('no_of_units');

    noOfUnitsInput.addEventListener('input', function () {
        // Remove non-numeric characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to a maximum of 2 characters
        if (this.value.length > 2) {
            this.value = this.value.substr(0, 2);
        }
        checkValidityForPageTwo();
    });

// Add this to your existing JavaScript code
const religionInput = document.getElementById('religion');
const houseNumberInput = document.getElementById('house_number');
const schoolNameInput = document.getElementById('school_name');
const schoolAddressInput = document.getElementById('school_address');
const studentIdNoInput = document.getElementById('student_id_no');
const courseEnrolledInput = document.getElementById('course_enrolled');

religionInput.addEventListener('change', () => {
    validateMinLength(religionInput, 2, 'religion_error');
    checkValidityForPageOne();
});

houseNumberInput.addEventListener('change', () => {
    validateMinLength(houseNumberInput, 2, 'house_number_error');
    checkValidityForPageOne();
});

schoolAddressInput.addEventListener('change', () => {
    validateMinLength(schoolAddressInput, 5, 'school_address_error');
    checkValidityForPageTwo(); // Assuming you have a function checkValidity to check overall form validity
});
schoolNameInput.addEventListener('change', () => {
    validateMinLength(schoolNameInput, 5, 'school_name_error');
    checkValidityForPageTwo(); // Assuming you have a function checkValidity to check overall form validity
});

studentIdNoInput.addEventListener('change', () => {
    validateMinLength(studentIdNoInput, 5, 'student_id_no_error');
    checkValidityForPageTwo(); // Assuming you have a function checkFormValidity to check overall form validity
});

courseEnrolledInput.addEventListener('change', () => {
    validateMinLength(courseEnrolledInput, 5, 'course_enrolled_error');
    checkValidityForPageTwo(); // Assuming you have a function checkValidity to check overall form validity
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
    checkValidityForPageThree();
});

guardianLastNameInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianLastNameInput, 2, "guardian_lastname_error");
    checkValidityForPageThree();

});

guardianRelationshipInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianRelationshipInput, 5, "guardian_relationship_error");
    checkValidityForPageThree();

});

guardianOccupationInput.addEventListener("input", function () {
    validateInputWithMinLength(guardianOccupationInput, 5, "guardian_occupation_error");
    checkValidityForPageThree();

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
    checkValidityForPageThree();
});


function checkValidityForPageOne() {
    const invalidElements = document.getElementsByClassName('invalid');

    // Check if there are no invalid elements
    if (invalidElements.length === 0) {
        // Enable the button for page one
        pageOneBtn.disabled = false;
    } else {
        // Disable the button for page one
        pageOneBtn.disabled = true;
    }
}

function checkValidityForPageTwo() {
    const invalidElements = document.getElementsByClassName('invalid');

    // Check if there are no invalid elements
    if (invalidElements.length === 0) {
        // Enable the button for page two
        pageTwoBtn.disabled = false;
    } else {
        // Disable the button for page two
        pageTwoBtn.disabled = true;
    }
}

function checkValidityForPageThree() {
    const invalidElements = document.getElementsByClassName('invalid');

    // Check if there are no invalid elements
    if (invalidElements.length === 0) {
        // Enable the button for page two
        pageThreeBtn.disabled = false;
    } else {
        // Disable the button for page two
        pageThreeBtn.disabled = true;
    }
}