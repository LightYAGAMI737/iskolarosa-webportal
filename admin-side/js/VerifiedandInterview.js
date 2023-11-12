function seeMore(id) {
    // Redirect to a page where you can retrieve the reserved data based on the given ID
    window.location.href = "ceap_information.php?ceap_reg_form_id=" + id;
}

document.addEventListener("DOMContentLoaded", function() {
    const hoursInput = document.getElementById("interview_hours");
    hoursInput.addEventListener("input", function() {
      
        const hoursValue = parseInt(hoursInput.value);
        if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
            hoursInput.value = '';
        }
    });
    
    const minutesInput = document.getElementById("interview_minutes");
minutesInput.addEventListener("input", function() {
let minutesValue = minutesInput.value;

// Remove leading zeros, except when the input is '0' or '00'
minutesValue = minutesValue.replace(/^0+(?!$)/, '');

// Ensure the value is within the valid range (0 to 59)
const parsedMinutesValue = parseInt(minutesValue);
if (isNaN(parsedMinutesValue) || parsedMinutesValue < 0 || parsedMinutesValue > 59) {
minutesInput.value = '';
} else {
// Add leading zeros if the value is less than 10
minutesInput.value = parsedMinutesValue < 10 ? `0${parsedMinutesValue}` : parsedMinutesValue.toString();
}
});


});

function formatInput(inputElement) {
    // Remove multiple consecutive white spaces
    inputElement.value = inputElement.value.replace(/\s+/g, ' ');

    // Convert input text to uppercase
    inputElement.value = inputElement.value.toUpperCase();
  }

//limit input max 200
  const limitInputCount = document.getElementById('limit');
  const recordCountElement = document.getElementById('record-count');
  
  limitInputCount.addEventListener('input', function () {
      const userInput = limitInputCount.value.trim();
      let sanitizedInput = userInput.replace(/^0+|(\..*)\./gm, '$0');
      
      if (sanitizedInput === '' || isNaN(sanitizedInput)) {
          limitInputCount.value = '';
      } else {
          const parsedInput = parseInt(sanitizedInput);
          // Ensure the value is within the valid range
          const validValue = Math.min(Math.max(parsedInput, 1), 200);
          limitInputCount.value = validValue;
      }
  });
