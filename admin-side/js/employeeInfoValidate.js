
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
  document.getElementById('firstName').addEventListener("change", function () {
    validateNames(this);
  });
  
  document.getElementById('lastName').addEventListener("change", function () {
    validateNames(this);
  });


  
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
      contactNumberError.textContent = '';
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
  
  
  // Add event listeners to contact number fields
  document.getElementById('contactNumber').addEventListener("change", validateContactNumber);

  function validateUpdateEmailAndCheck(employeeId) {
    var emailInput = document.getElementById("email");
    var emailError = document.getElementById("emailError");
  
    var emailPattern = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[cC][oO][mM]$/;
    if (!emailPattern.test(emailInput.value)) {
        emailError.textContent = 'Invalid email';
        emailError.style.display = 'block';
        emailInput.classList.add('invalid');
    } else {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
  
        // Prepare the request
        xhr.open("POST", "../php/checkUpdateEmailEmployee.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                // Parse the response
                var result = xhr.responseText;
                if (result === 'email_exist') {
                    // Log to console or display an alert
                    console.log('Email already exists');
                    // Update the content of the emailError span
                    emailError.textContent = 'Email already exists';
                    emailError.style.display = "block";
                    emailInput.classList.add('invalid');
                } else if (result === 'email_unique') {
                    // Email is unique, clear the error message
                    emailError.textContent = '';
                    emailError.style.display = 'none';
                    emailInput.classList.remove('invalid');
                }
            }
        };
  
        // Send the request with the data, including the employee_id
        xhr.send("email=" + encodeURIComponent(emailInput.value) + "&employee_id=" + encodeURIComponent(employeeId));
    }
}

// Event listener for input change
document.getElementById("email").addEventListener("change", function () {
    // Assuming you have access to the employee_id, pass it to the function
    validateUpdateEmailAndCheck(employeeId);
});

 
  const updateButton = document.getElementById("updateButton");

  updateButton.disabled = true;

  // Add an input event listener to the document to continuously check for invalid fields
  document.addEventListener("change", checkUpdateFormValidity);
  
  function checkUpdateFormValidity() {
    const invalidFields = document.querySelectorAll('.invalid'); // Get all elements with the 'invalid' class
  
    // Optional: Log information about invalid fields
    invalidFields.forEach(field => {
      console.log(`Field '${field.name}' has the 'invalid' class.`);
    });
  
   if(invalidFields.length === 0) {
        updateButton.removeAttribute("disabled");
   }else {
    updateButton.setAttribute("disabled","disabled")
   }
  }
  