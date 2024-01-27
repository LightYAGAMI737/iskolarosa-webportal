const newPassword = document.getElementById('new_password');
const confirmPassword = document.getElementById('confirm_password');
const submitButton = document.getElementById('submitForgotPassword');
const newPasswordShow = document.getElementById('new_password');
const confirmPasswordShow = document.getElementById('confirm_password');
const showPasswordCheckbox = document.getElementById('showPassword');
const tooltipsContainer = document.getElementById('tooltipsID'); // Add this line

const passwordRequirements = {
    minimumLength: 'Min. 8 characters.',
    uppercaseLowercase: '1 uppercase & 1 lowercase letter.',
    alphanumeric: 'Letters and numbers.',
    specialCharacter: 'At least one special character.',
};

newPassword.addEventListener('input', displayTooltipAndValidate);
confirmPassword.addEventListener('input', displayTooltipAndValidate);

function displayTooltipAndValidate() {
    const password = newPassword.value;
    const confirm = confirmPassword.value;

    const hasMinimumLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasAlphanumeric = /^(?=.*[0-9])(?=.*[a-zA-Z])/.test(password);
    const hasSpecialCharacter = /(?=.*[_\W])/.test(password);

    const requirementsMet = [];

    if (hasMinimumLength) {
        requirementsMet.push('✅ ' + passwordRequirements.minimumLength);
    } else {
        requirementsMet.push('❌ ' + passwordRequirements.minimumLength);
    }

    if (hasUppercase && hasLowercase) {
        requirementsMet.push('✅ ' + passwordRequirements.uppercaseLowercase);
    } else {
        requirementsMet.push('❌ ' + passwordRequirements.uppercaseLowercase);
    }

    if (hasAlphanumeric) {
        requirementsMet.push('✅ ' + passwordRequirements.alphanumeric);
    } else {
        requirementsMet.push('❌ ' + passwordRequirements.alphanumeric);
    }

    if (hasSpecialCharacter) {
        requirementsMet.push('✅ ' + passwordRequirements.specialCharacter);
    } else {
        requirementsMet.push('❌ ' + passwordRequirements.specialCharacter);
    }

    // Check if passwords match
    const passwordsMatch = confirm !== '' && password === confirm;
    
    // Combine requirements with line breaks
    const tooltipText = requirementsMet.join("<br>");

    tooltipsContainer.innerHTML = tooltipText;
    tooltipsContainer.style.visibility = 'visible';

    // Check if all requirements are met
    const allRequirementsMet = requirementsMet.every(req => req.startsWith('✅'));

    // Display inline error message for password mismatch
    if (!passwordsMatch) {
        tooltipsContainer.innerHTML += '<br>❌ Passwords do not match.';
    }

    // Hide tooltip if all requirements are met
    if (allRequirementsMet && passwordsMatch) {
        tooltipsContainer.style.visibility = 'hidden';
    }

    // Disable or enable the submit button based on validation checks
    submitButton.disabled = !allRequirementsMet || !passwordsMatch;
}

showPasswordCheckbox.addEventListener('change', togglePasswordVisibility);
function togglePasswordVisibility() {
    const type = showPasswordCheckbox.checked ? 'text' : 'password';
    newPasswordShow.type = type;
    confirmPasswordShow.type = type;
}
