const inputs = document.querySelectorAll(".input");


function addcl(){
	let parent = this.parentNode.parentNode;
	parent.classList.add("focus");
}

function remcl(){
	let parent = this.parentNode.parentNode;
	if(this.value == ""){
		parent.classList.remove("focus");
	}
}


inputs.forEach(input => {
	input.addEventListener("focus", addcl);
	input.addEventListener("blur", remcl);
});


const passwordField = document.getElementById("passwordField");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", function () {
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);

    // Change the eye icon based on the password field type
    if (type === "password") {
        togglePassword.classList.remove("ri-eye-off-fill");
        togglePassword.classList.add("ri-eye-fill");
    } else {
        togglePassword.classList.remove("ri-eye-fill");
        togglePassword.classList.add("ri-eye-off-fill");
    }
});


const usernameField = document.querySelector('input[name="username"]');
const LoginpasswordField = document.querySelector('input[name="password"]');
const loginButton = document.querySelector('.btn');

usernameField.addEventListener('input', validateInputs);
LoginpasswordField.addEventListener('input', validateInputs);

function validateInputs() {
    if (usernameField.value.trim() !== '' && LoginpasswordField.value.trim() !== '') {
        loginButton.removeAttribute('disabled');
    } else {
        loginButton.setAttribute('disabled', 'true');
    }
}
