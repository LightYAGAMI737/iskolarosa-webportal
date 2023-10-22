     // Declare employeeIdInput as a global variable
var employeeIdInput = document.getElementById('employeeId');
var employeeIdError = document.getElementById('employeeIdErrors'); // Declare employeeIdError as a global variable

employeeIdInput.addEventListener('input', function () {
    // Remove special characters and keep only letters, numbers, and dashes
    employeeIdInput.value = employeeIdInput.value.replace(/[^A-Za-z0-9-]/g, '');

    validateEmployeeId();
    checkAllFields(); // Check all fields after each input
});

function validateEmployeeId() {
    var employeeId = employeeIdInput.value;

    // Check if the input has a length of 5
    if (employeeId.length !== 5) {
        employeeIdError.textContent = ' 5 characters long.';
        employeeIdError.style.display = 'block';
        employeeIdInput.classList.add('invalid');
    } else {
        employeeIdError.textContent = '';
        employeeIdError.style.display = 'none';
        employeeIdInput.classList.remove('invalid');
    }
}

  // Declare passwordInput and confirmPasswordInput as global variables
var passwordInput = document.getElementById('password');
var confirmPasswordInput = document.getElementById('confirmPassword');
var confirmPasswordError = document.getElementById('confirmPasswordError'); // Declare confirmPasswordError as a global variable

function validatePassword() {
    var password = passwordInput.value;
    var passwordError = document.getElementById('passwordError'); // Declare passwordError here

    // var lengthRegex = /.{8,20}/;
    // var uppercaseRegex = /[A-Z]/;
    // var lowercaseRegex = /[a-z]/;
    // var numberRegex = /[0-9]/;

    // var validLength = lengthRegex.test(password);
    // var hasUppercase = uppercaseRegex.test(password);
    // var hasLowercase = lowercaseRegex.test(password);
    // var hasNumber = numberRegex.test(password);

    // // Check if any validation fails
    // if (!validLength || !hasUppercase || !hasLowercase || !hasNumber) {
    //     passwordError.textContent = '8 characters long, 1 uppercase letter, 1 lowercase letter, and one number.';
    //     passwordError.style.display = 'block';
    //     passwordInput.classList.add('invalid');
    // } else {
    //     passwordError.textContent = '';
    //     passwordError.style.display = 'none';
    //     passwordInput.classList.remove('invalid');
    // }

    // Check if the password and confirm password match in real-time
    if (password !== confirmPasswordInput.value) {
        confirmPasswordError.textContent = 'Passwords do not match.';
        confirmPasswordError.style.display = 'block';
        confirmPasswordInput.classList.add('invalid');
    } else {
        confirmPasswordError.textContent = 'Password matched';
        confirmPasswordError.style.display = 'block';

        setTimeout(function () {
            confirmPasswordError.textContent = '';
            confirmPasswordError.style.display = 'none';
            confirmPasswordInput.classList.remove('invalid');
        }, 2000);
    }
}

function validateConfirmPassword() {
    var password = passwordInput.value;
    var confirmPassword = confirmPasswordInput.value;

    if (password !== confirmPassword) {
        confirmPasswordError.textContent = 'Passwords do not match.';
        confirmPasswordError.style.display = 'block';
        confirmPasswordInput.classList.add('invalid');
    } else {
        confirmPasswordError.textContent = 'Password matched';
        confirmPasswordError.style.display = 'block';

        setTimeout(function () {
            confirmPasswordError.textContent = '';
            confirmPasswordError.style.display = 'none';
            confirmPasswordInput.classList.remove('invalid');
        }, 2000);
    }
}

// Rest of your existing code remains the same



// Function to validate first and last names
function validateNames(inputElement, errorElement) {
  const name = inputElement.value;
  const nameRegex = /^[a-zA-Z0-9-\s]*$/; // Allow letters, numbers, spaces, and dashes

  // Replace special characters other than '-' and space in real-time
  inputElement.value = name.replace(/[^a-zA-Z0-9-\s]/g, '');

  // Enforce one space and one dash per word
  const words = inputElement.value.split(/\s+/); // Split by spaces
  const sanitizedWords = words.map(word => {
    return word.replace(/-+/g, '-') // Remove multiple dashes in a row
      .replace(/\s+/g, ' '); // Remove multiple spaces in a row
  });
  inputElement.value = sanitizedWords.join(' '); // Rejoin words with spaces

  // Validate the minimum and maximum length
  if (inputElement.value.length < 3 || inputElement.value.length > 25) {
    // Display an error message for length
    errorElement.textContent = 'At least 2 characters.';
    
    // Add the 'invalid' class
    inputElement.classList.add('invalid');
  } else {
    // Clear the error message
    errorElement.textContent = '';
    
    // Remove the 'invalid' class
    inputElement.classList.remove('invalid');
  }
  checkAllFields();

}


// Function to validate the contact number (PH mobile sim format)
function validateContactNumber() {
  var contactNumberInput = document.getElementById('contactNumber');
  var contactNumberError = document.getElementById('contactNumberError');
  var regex = /^(09|\+639)\d{9}$/;

  if (!regex.test(contactNumberInput.value)) {
    contactNumberError.textContent = 'Invalid contact number';
    contactNumberError.style.display = 'block';
    contactNumberInput.classList.add('invalid');
  } else {
    contactNumberError.style.display = 'none';
    contactNumberInput.classList.remove('invalid');
  }
}

// Add event listener for contact number input
var contactNumberInput = document.getElementById('contactNumber');
contactNumberInput.addEventListener('input', function() {
  // Remove any non-digit characters from the input
  contactNumberInput.value = contactNumberInput.value.replace(/[^0-9]/g, '');

  // Limit the input to 11 digits
  if (contactNumberInput.value.length > 11) {
    contactNumberInput.value = contactNumberInput.value.slice(0, 11);
  }
});

// Add event listeners to contact number and email fields
document.getElementById('contactNumber').addEventListener('input', validateContactNumber);


// Function to validate email
function validateEmail() {
    var emailInput = document.getElementById("email");
    var emailError = document.getElementById("emailError");

    var emailPattern = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[cC][oO][mM]$/;

    if (!emailPattern.test(emailInput.value)) {
        emailError.textContent = 'Invalid email';
        emailError.style.display = 'block';
        emailInput.classList.add('invalid');
    } else {
        emailError.textContent = '';
        emailError.style.display = 'none';
        emailInput.classList.remove('invalid');

        // Reset the border color after 2 seconds
        setTimeout(function () {
            emailInput.style.borderColor = '';
        }, 2000);
    }
}


// Validate picture
function validatePicture() {
    var pictureInput = document.getElementById('picture');
    var pictureError = document.getElementById('pictureError');
    
    // Check if a file is selected
    if (pictureInput.files.length === 0) {
        pictureError.textContent = 'Please select a valid picture (JPG/JPEG, up to 2MB)';
        pictureError.style.display = 'block';
        pictureInput.classList.add('invalid');
        return false;
    }

    var allowedTypes = ["image/jpeg", "image/jpg"];
    var maxFileSize = 2 * 1024 * 1024; // 2MB

    var file = pictureInput.files[0];

    // Check file type
    if (!allowedTypes.includes(file.type)) {
        pictureError.textContent = 'Invalid file type. Please select a JPG/JPEG file.';
        pictureError.style.display = 'block';
        pictureInput.value = ''; // Clear the input
        pictureInput.classList.add('invalid');
        return false;
    }

    // Check file size
    if (file.size > maxFileSize) {
        pictureError.textContent = 'File size exceeds 2MB. Please select a smaller file.';
        pictureError.style.display = 'block';
        pictureInput.value = ''; // Clear the input
        pictureInput.classList.add('invalid');
        return false;
    }

    // If everything is valid, reset styles and hide error message
    pictureInput.style.borderColor = '';
    pictureError.textContent = '';
    pictureError.style.display = 'none';
    pictureInput.classList.remove('invalid');
    return true;
}

 function generateUsername() {
  const department = document.getElementById('department').value.toUpperCase();
  const employeeId = document.getElementById('employeeId').value;

  if (department && employeeId) {
    const formattedDepartment = department.replace(/\s+/g, '-');
    const generatedUsername = `${formattedDepartment}-${employeeId}`;
    document.getElementById('username').value = generatedUsername;
  } else {
    document.getElementById('username').value = '';
  }
}

  // Add event listeners to the department dropdown and employee ID input field
  document.getElementById('department').addEventListener('change', generateUsername);
  document.getElementById('employeeId').addEventListener('input', generateUsername);


// Function to check all required fields for 'invalid' class and enable/disable the submit button
function checkAllFields() {
  const requiredFields = document.querySelectorAll('.required-field'); // Add 'required-field' class to required fields
  const submitButton = document.getElementById('submitButton');
  let allFieldsValid = true; // Assume all fields are valid initially

  for (const field of requiredFields) {
    if (field.classList.contains('invalid') || !field.value.trim()) {
      // If a required field has the 'invalid' class or is empty, mark it as invalid
      allFieldsValid = false;
      break; // Exit loop if at least one required field is invalid
    }
  }
  
  // Check if the password and confirm password match
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirmPassword');

  if (
    passwordInput.value !== confirmPasswordInput.value ||
    !passwordInput.value.trim() ||
    !confirmPasswordInput.value.trim()
  ) {
    allFieldsValid = false;
  }

  // Enable or disable the submit button based on allFieldsValid
  submitButton.disabled = !allFieldsValid;
}
// Add event listeners to first and last name fields
const firstNameInput = document.getElementById('firstName');
const lastNameInput = document.getElementById('lastName');
const firstNameError = document.getElementById('first_NameError');
const lastNameError = document.getElementById('last_NameError');


employeeIdInput.addEventListener('input', function () {
    validateEmployeeId();
    checkAllFields(); // Check all fields after each input
});

firstNameInput.addEventListener('input', function () {
  validateNames(firstNameInput, firstNameError);
  checkAllFields(); // Check all fields after each input
});

lastNameInput.addEventListener('input', function () {
  validateNames(lastNameInput, lastNameError);
  checkAllFields(); // Check all fields after each input
});

passwordInput.addEventListener('input', function() {
  validatePassword(); // Validate the password input
  checkAllFields(); // Check password and confirm password
});

confirmPasswordInput.addEventListener('input', function() {
  validateConfirmPassword();
  checkAllFields(); // Check password and confirm password
});

// Add event listener for contact number input
var contactNumberInput = document.getElementById('contactNumber');
contactNumberInput.addEventListener('input', function() {
  validateContactNumber();
  checkAllFields(); // Check all fields after each input
});

// Add event listener for email input
var emailInput = document.getElementById('email');
emailInput.addEventListener('input', function() {
  validateEmail();
  checkAllFields(); // Check all fields after each input
});

// Add event listener for picture input
var pictureInput = document.getElementById('picture');
pictureInput.addEventListener('input', function() {
  validatePicture();
  checkAllFields(); // Check all fields after each input
});

