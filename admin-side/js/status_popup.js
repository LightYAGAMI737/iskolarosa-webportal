   // JavaScript code to handle the popup
   const Verifiedpopup = document.getElementById("verifiedPopUp");
   const Granteepopup = document.getElementById("GranteePopUp");
   const interviewpopup = document.getElementById("interviewPopUp");

   function openVerifiedPopup() {
       Verifiedpopup.style.display = "block";
   }

   function openGranteePopup() {
       Granteepopup.style.display = "block";
   }

   function openInterviewPopup() {
    interviewpopup.style.display = "block";
   }

   function closStatusPopup() {
       Verifiedpopup.style.display = "none";
       Granteepopup.style.display = "none";
       interviewpopup.style.display = "none";

   }


  // Get all elements with the class name "confirm-button"
  const confirmButtons = document.querySelectorAll('.confirm-button');

  // Loop through each confirm button and add a click event listener
  confirmButtons.forEach(function(button) {
      button.addEventListener('click', function() {
          // Add the "disabled" class to the confirm button
          this.classList.add('disabled');

          // Find the associated cancel button
          const cancelButton = this.parentElement.querySelector('.cancel-button');

          // Add the "disabled" class to the cancel button
          cancelButton.classList.add('disabled');
      });
  });

 
        // Get a reference to the "Cancel" button
const cancelButtons = document.querySelectorAll(".cancel-button");

// Add a click event listener to each "Cancel" button to close the popup
cancelButtons.forEach((cancelButton) => {
  cancelButton.addEventListener("click", closStatusPopup);
});
