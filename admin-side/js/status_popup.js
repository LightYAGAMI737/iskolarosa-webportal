// JavaScript code to handle the popup
const popup = document.getElementById("verifiedPopUp");

function openVerifiedPopup() {
  popup.style.display = "block";
}

function closeVerifiedPopup() {
  popup.style.display = "none";
}

// Get a reference to the "Cancel" button
const cancelButton = document.getElementById("cancelConfirm");

// Add a click event listener to the "Cancel" button to close the popup
cancelButton.addEventListener("click", closeVerifiedPopup);




// Get a reference to the button elements by their IDs
var submitButtonConfirm = document.getElementById("submitConfirm");
var submitCancelConfirm = document.getElementById("cancelConfirm");

// Add a click event listener to the submitConfirm button
submitButtonConfirm.addEventListener("click", function() {
  // Add the "disabled" class to both buttons
  submitButtonConfirm.classList.add("disabled");
  submitCancelConfirm.classList.add("disabled");
});