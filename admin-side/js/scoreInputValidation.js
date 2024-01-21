document.addEventListener('DOMContentLoaded', function () {
    var scoreInput = document.getElementById('applicantScore');
    var submitButton = document.getElementById('ScoreFormBTNSubmit');
    var errorSpan = document.getElementById('ScoreInputError');

    // Add input event listener to validate input using regex
    scoreInput.addEventListener('input', function () {
        var inputValue = scoreInput.value;
        
        // Allow only numbers
        var numericValue = inputValue.replace(/\D/g, '');

        // Restrict length to 3 characters
        if (numericValue.length > 3) {
            numericValue = numericValue.slice(0, 3);
        }

        // Check if the value is within the range 1-100
        var numericIntValue = parseInt(numericValue, 10);
        if (numericIntValue >= 1 && numericIntValue <= 100) {
            scoreInput.classList.remove('invalid');
            errorSpan.textContent = '';
        } else {
            scoreInput.classList.add('invalid');
            errorSpan.textContent = 'Invalid input. Please enter a number between 1 and 100.';
        }

        // Update the input value
        scoreInput.value = numericValue;

        // Enable or disable the button based on the presence of the "invalid" class
        submitButton.disabled = scoreInput.classList.contains('invalid');
    });
});
