document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('employeeconfigform');

  form.addEventListener('input', function (event) {
      if (event.target.tagName.toLowerCase() === 'input') {
          // Replace multiple spaces with a single space
          event.target.value = event.target.value.replace(/\s+/g, ' ');
      }
  });
});
var employeeIdInput = document.getElementById('employeeId');
var employeeIdErrors = document.getElementById('employeeIdErrors');

employeeIdInput.addEventListener("input", function () {
    // Remove non-numeric characters
    employeeIdInput.value = employeeIdInput.value.replace(/\D/g, '');

    // Check if the input has a length of 5
    if (employeeIdInput.value.length === 5) {
        checkDuplicateEmployeeId();
    } else {
        validateEmployeeId();
    }
});

employeeIdInput.addEventListener('input', function (event) {
  // Remove spaces from the input's value
  employeeIdInput.value = employeeIdInput.value.replace(/\s/g, '');
});

function validateEmployeeId() {
    var employeeId = employeeIdInput.value;
      // Check if the input has a length of 5
 if (employeeIdInput.value.length !== 5) {
      employeeIdErrors.textContent = 'At least 5 characters.';
      employeeIdErrors.style.display = 'block';
      employeeIdInput.classList.add('invalid');
  } else {
      // No errors, reset the error message and styles
      employeeIdErrors.textContent = '';
      employeeIdErrors.style.display = 'none';
      employeeIdInput.classList.remove('invalid');
  }
}

// Function to check for duplicate employee IDs
function checkDuplicateEmployeeId() {
    // Check if the input value is not empty
    if (employeeIdInput.value.trim() !== "") {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Prepare the request
        xhr.open("POST", "./php/check_duplicate_employeeId.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                // Parse the response
                var result = xhr.responseText;
                if (result === 'employeeId_exist') {
                    // Log to console or display an alert
                    console.log('ID already exists');
                    employeeIdInput.classList.add('invalid');
                    employeeIdErrors.textContent = 'ID already exists';
                    employeeIdErrors.style.display = "block";
                } else if (result === 'employeeId_unique') {
                    // ID is unique, clear the error message
                    employeeIdErrors.textContent = '';
                    employeeIdErrors.style.display = 'none';
                    employeeIdInput.classList.remove('invalid');
                }
            }
        };

        // Send the request with the data
        xhr.send("employeeId=" + encodeURIComponent(employeeIdInput.value));
    }
}

// Function to validate first and last names
function validateNames(inputElement) {
  // Check if the input element is defined and has a value
  if (inputElement && inputElement.value !== undefined) {
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

    // Get the corresponding error element
    const errorElementId = inputElement.id + 'Error';
    const errorElement = document.getElementById(errorElementId);

    // Validate the minimum and maximum length
    if (inputElement.value.length < 2 || inputElement.value.length > 25) {
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
  }
}

// Add event listeners for the 'input' event for each input element
document.getElementById('firstName').addEventListener("input", function () {
  validateNames(this);
});

document.getElementById('lastName').addEventListener("input", function () {
  validateNames(this);
});

// Function to validate the contact number (PH mobile sim format)
function validateContactNumber() {
  var contactNumberInputFormat = document.getElementById('contactNumber');
  var contactNumberError = document.getElementById('contactNumberError');

  if (contactNumberInput.value.length <= 12) {
    contactNumberError.textContent = 'Invalid contact number';
    contactNumberError.style.display = 'block';
    contactNumberInputFormat.classList.add('invalid');
  } else {
    contactNumberError.textContent = '';
    contactNumberError.style.display = 'none';
    contactNumberInputFormat.classList.remove('invalid');
  }
}

// Set initial value "09" and format with hyphens
var contactNumberInput = document.getElementById('contactNumber');
contactNumberInput.value = '09';

// Add event listener for contact number input
contactNumberInput.addEventListener('input', function(event) {
  // Remove any non-digit characters from the input
  contactNumberInput.value = contactNumberInput.value.replace(/[^0-9]/g, '');

  // Limit the input to 11 digits
  if (contactNumberInput.value.length > 13) {
    contactNumberInput.value = contactNumberInput.value.slice(0, 13);
  }

  // Format with hyphens after the fourth and seventh digits
  if (contactNumberInput.value.length >= 2) {
    contactNumberInput.value = "09" + contactNumberInput.value.slice(2);
  }
  if (contactNumberInput.value.length >= 5) {
    contactNumberInput.value =
      contactNumberInput.value.slice(0, 4) + '-' + contactNumberInput.value.slice(4);
  }
  if (contactNumberInput.value.length >= 9) {
    contactNumberInput.value =
      contactNumberInput.value.slice(0, 8) + '-' + contactNumberInput.value.slice(8);
  }
  
  // Prevent modification of initial "09"
  if (contactNumberInput.selectionStart < 2) {
    event.preventDefault();
    contactNumberInput.setSelectionRange(2, 2);
  }

  // Validate the contact number after modification
  validateContactNumber();
});

// Add event listeners to contact number fields
document.getElementById('contactNumber').addEventListener("change", validateContactNumber);


function validateEmailAndCheck() {
  var emailInput = document.getElementById("email");
  var emailError = document.getElementById("emailError");

  emailInput.addEventListener('input', function (event) {
    // Remove spaces from the input's value
    emailInput.value = emailInput.value.replace(/\s/g, '');

  });
  
  var emailPattern = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[cC][oO][mM]$/;
if (!emailPattern.test(emailInput.value)) {
      emailError.textContent = 'Invalid email';
      emailError.style.display = 'block';
      emailInput.classList.add('invalid');
    }else 
      // Create a new XMLHttpRequest object
      var xhr = new XMLHttpRequest();

      // Prepare the request
      xhr.open("POST", "./php/checkEmailEmployee.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          // Parse the response
          var result = xhr.responseText;
          if (result === 'email_exist') {
            // Log to console or display an alert
            console.log('Email already exists');
            emailInput.classList.add('invalid');
            emailError.textContent = 'Email already exists';
            emailError.style.display = "block";
          } else if (result === 'email_unique') {
            // Email is unique, clear the error message
            emailError.textContent = '';
            emailError.style.display = 'none';
            emailInput.classList.remove('invalid');
          }
        }
      };

      // Send the request with the data
      xhr.send("email=" + encodeURIComponent(emailInput.value));
    }
   
    
// Event listener for input change
document.getElementById("email").addEventListener("input", validateEmailAndCheck);



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
  document.getElementById('department').addEventListener("change", generateUsername);
  document.getElementById('employeeId').addEventListener("change", generateUsername);

  // const passwordInput = document.getElementById("password");
  // const confirmPasswordInput = document.getElementById("confirmPassword");
  // const passwordError = document.getElementById("passwordError");
  // const confirmPasswordError = document.getElementById("confirmPasswordError");
  
  // passwordInput.addEventListener('input', function (event) {
  //   // Remove spaces from the input's value
  //   passwordInput.value = passwordInput.value.replace(/\s/g, '');
  // });
  // confirmPasswordInput.addEventListener('input', function (event) {
  //   // Remove spaces from the input's value
  //   confirmPasswordInput.value = confirmPasswordInput.value.replace(/\s/g, '');
  // });
  
  // passwordInput.addEventListener("change", validatePassword);
  // confirmPasswordInput.addEventListener("change", validateConfirmPassword);
  
  // function validatePassword() {
  //     const password = passwordInput.value;
  //     // Include the underscore (_) as an allowed special character
  //     const passwordPattern = /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[_\W]).{8,}$/;
  
  //     if (passwordPattern.test(password)) {
  //         closepasswordpopup();
  //         passwordInput.classList.remove('invalid');
  //     } else {
  //         openpasswordpopup();
  //         passwordInput.classList.add('invalid');
  //     }
  //     validateConfirmPassword();
  // }
  
  // function validateConfirmPassword() {
  //     const password = passwordInput.value;
  //     const confirmPassword = confirmPasswordInput.value;
  
  //     if (confirmPassword) {
  //         if (password === confirmPassword) {
  //             confirmPasswordError.textContent = "";
  //             confirmPasswordInput.classList.remove('invalid');
  //         } else {
  //             confirmPasswordError.textContent = "Passwords do not match.";
  //             confirmPasswordInput.classList.add('invalid');
  //             checkFormValidity();
  //         }
  //     }
  // }
  
  // const passwordInputshowhide = document.getElementById("password");
  // const confirmpasswordInputshowhide = document.getElementById("confirmPassword");
  // const passwordToggle = document.querySelector(".password-toggle");
  // const confirmPasswordToggle = document.getElementById("password-toggle-confirmPassword");
  
  // function togglePassword() {
  //     if (passwordInputshowhide.type === "password") {
  //         passwordInputshowhide.type = "text";
  //         passwordToggle.innerHTML = '<i class="ri-eye-fill"></i>'; // Change to a crossed-out eye
  //     } else {
  //         passwordInputshowhide.type = "password";
  //         passwordToggle.innerHTML = '<i class="ri-eye-off-fill"></i>'; // Change back to a regular eye
  //     }
  // }

const buttonContainer = document.querySelector(".button-container"); // Get the button container
const submitButton = document.getElementById("submitButton");

// Add an input event listener to the document to continuously check for invalid fields
document.addEventListener("change", checkFormValidity);

function checkFormValidity() {
    const invalidFields = document.querySelectorAll('.invalid'); // Get all elements with the 'invalid' class
    const requiredFields = document.querySelectorAll('[required]'); // Get all required fields

    let hasEmptyRequiredField = false;

    requiredFields.forEach(field => {
        if (field.value.trim() === '') {
            hasEmptyRequiredField = true;
            console.log(`Required field '${field.name}' is empty.`);
        }
    });

    invalidFields.forEach(field => {
        console.log(`Field '${field.name}' has the 'invalid' class.`);
    });

    if (invalidFields.length === 0 && !hasEmptyRequiredField) {
        submitButton.removeAttribute("disabled");
    } else {
        submitButton.setAttribute("disabled", "disabled");
    }
}


// function toggleConfirmPassword() {
//   if (confirmpasswordInputshowhide.type === "password") {
//       confirmpasswordInputshowhide.type = "text";
//       confirmPasswordToggle.innerHTML = '<i class="ri-eye-off-fill"></i>'; // Change to a crossed-out eye
//   } else {
//       confirmpasswordInputshowhide.type = "password";
//       confirmPasswordToggle.innerHTML = '<i class="ri-eye-fill"></i>'; // Change back to a regular eye
//   }
// }
// const passwordpopop = document.getElementById('openpasswordpopup'); 

// function openpasswordpopup() {
// passwordpopop.style.display="block";
// } 
// function closepasswordpopup(){
// passwordpopop.style.display="none";

// }