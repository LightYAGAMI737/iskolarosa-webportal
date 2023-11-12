   // JavaScript code to handle the popup
   const Verifiedpopup = document.getElementById("verifiedPopUp");
   const Granteepopup = document.getElementById("GranteePopUp");
   const interviewpopup = document.getElementById("interviewPopUp");
   const CEAPconfigPopup = document.getElementById("CEAPconfigurationPopUp");
   const LPPPconfigPopup = document.getElementById("LPPPconfigurationPopUp");
   const postdeletepopup = document.getElementById("DeletepostPopUp");

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
    if (Verifiedpopup) {
        Verifiedpopup.style.display = "none";
    }
    if (Granteepopup) {
        Granteepopup.style.display = "none";
    }
    if (interviewpopup) {
        interviewpopup.style.display = "none";
    }
    if (CEAPconfigPopup) {
        CEAPconfigPopup.style.display = "none";
    }
    if (LPPPconfigPopup) {
      LPPPconfigPopup.style.display = "none";
  }
  if (postdeletepopup) {
    postdeletepopup.style.display = "none";
}
}


  const StatusconfirmButtons = document.querySelectorAll('.confirm-button');
  StatusconfirmButtons.forEach(function(button) {
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
