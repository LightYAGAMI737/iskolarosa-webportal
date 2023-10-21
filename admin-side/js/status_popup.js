   // JavaScript code to handle the popup
   const Verifiedpopup = document.getElementById("verifiedPopUp");
   const Granteepopup = document.getElementById("GranteePopUp");
   const interviewpopup = document.getElementById("interviewPopUp");

   function openVerifiedPopup() {
       Verifiedpopup.style.display = "block";
   }

    function openInterviewPopup() {
        interviewpopup.style.display = "block";
    }

   function openGranteePopup() {
       Granteepopup.style.display = "block";
   }

   function closeStatusPopup() {
       Verifiedpopup.style.display = "none";
       Granteepopup.style.display = "none";
       interviewpopup.style.display = "none";

   }


  const confirmButtons = document.querySelectorAll('.confirm-button');
  confirmButtons.forEach(function(button) {
      button.addEventListener('click', function() {
          this.classList.add('disabled');
          const cancelButton = this.parentElement.querySelector('.cancel-button');
          cancelButton.classList.add('disabled');
      });
  });

 
const cancelButtons = document.querySelectorAll(".cancel-button");
cancelButtons.forEach((cancelButton) => {
  cancelButton.addEventListener("click", closeStatusPopup);
  
});

function openConfirmationPopup() {
    closeStatusPopup();
  const confirmPopup = document.getElementById("ConfrimMsgPopUp");
  confirmPopup.style.display = "block";

  const okButton = document.getElementById("okConfirm");
  okButton.addEventListener("click", function () {
    confirmPopup.style.display = "none";
    goBack();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(goBack, 5000);
}
