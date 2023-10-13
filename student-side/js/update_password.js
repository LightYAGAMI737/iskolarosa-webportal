document.addEventListener('DOMContentLoaded', function () {
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const updateButton = document.getElementById('update_button');
    const errorMessage = document.querySelector('.error-message');

    confirmPasswordInput.addEventListener('input', function () {
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (newPassword === confirmPassword) {
            // Enable the update button
            updateButton.removeAttribute('disabled');
            confirmPasswordInput.style.borderColor = 'green'; // Apply green border
            errorMessage.style.display = 'none'; // Hide the error message

            // Reset the border color to default after 3 seconds
            setTimeout(function () {
                confirmPasswordInput.style.borderColor = ''; // Remove the green border
            }, 3000);
        } else {
            // Disable the update button
            updateButton.setAttribute('disabled', 'disabled');
            confirmPasswordInput.style.borderColor = 'red'; // Apply red border
            errorMessage.style.display = 'block'; // Show the error message
        }
    });
});