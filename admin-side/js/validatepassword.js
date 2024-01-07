const newPassword = document.getElementById('new_password');
const confirmPassword = document.getElementById('confirm_password');
const newPasswordError = document.getElementById('new_password_error');
const confirmPasswordError = document.getElementById('confirm_password_error');
const submitButton = document.getElementById('submitForgotPassword');

newPassword.addEventListener('change', validatePassword);
confirmPassword.addEventListener('change', validatePassword);

function validatePassword() {
    const password = newPassword.value;
    const confirm = confirmPassword.value;

    const hasMinimumLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);  // New condition for at least one uppercase letter
    const hasLowercase = /[a-z]/.test(password);  // New condition for at least one lowercase letter
    const hasAlphanumeric = /^(?=.*[0-9])(?=.*[a-zA-Z])/.test(password);
    const hasSpecialCharacter = /[!@#$%^&*]/.test(password);

    newPasswordError.textContent = '';
    confirmPasswordError.textContent = '';

    if (!hasMinimumLength) {
        newPasswordError.textContent = 'Password must be at least 8 characters.';
    } else if (!hasUppercase || !hasLowercase) {
        newPasswordError.textContent = 'Password must contain at least one uppercase and one lowercase letter.';
    } else if (!hasAlphanumeric) {
        newPasswordError.textContent = 'Password must contain both letters and numbers.';
    } else if (!hasSpecialCharacter) {
        newPasswordError.textContent = 'Password must contain at least one special character.';
    }

    if (confirm !== '') {
        if (password !== confirm) {
            confirmPasswordError.textContent = 'Passwords do not match.';
        } else {
            confirmPasswordError.textContent = '';
        }
    }

    submitButton.disabled = !(hasMinimumLength && hasUppercase && hasLowercase && hasAlphanumeric && hasSpecialCharacter && password === confirm);
}


const newPasswordShow = document.getElementById('new_password');
const confirm_passwordShow = document.getElementById('confirm_password');
const showPasswordCheckbox = document.getElementById('showPassword');

showPasswordCheckbox.addEventListener('change', togglePasswordVisibility);

function togglePasswordVisibility() {
    const type = showPasswordCheckbox.checked ? 'text' : 'password';
    newPasswordShow.type = type;
    confirm_passwordShow.type = type;
}