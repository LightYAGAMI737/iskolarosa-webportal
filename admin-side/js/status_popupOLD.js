   // JavaScript code to handle the popupOLD
   const VerifiedpopupOLD = document.getElementById("verifiedPopUpOLD");
   const GranteepopupOLD = document.getElementById("GranteePopUpOLD");
   const interviewpopupOLD = document.getElementById("interviewPopUpOLD");
   const CEAPconfigPopupOLD = document.getElementById("CEAPconfigurationPopUpOLD");
   const LPPPconfigPopupOLD = document.getElementById("LPPPconfigurationPopUpOLD");

   function openVerifiedPopupOLD() {
       VerifiedpopupOLD.style.display = "block";
   }

    function openInterviewPopupOLD() {
        interviewpopupOLD.style.display = "block";
    }

   function openGranteePopupOLD() {
       GranteepopupOLD.style.display = "block";
   }

   function closeStatusPopupOLD() {
    if (VerifiedpopupOLD) {
        VerifiedpopupOLD.style.display = "none";
    }
    if (GranteepopupOLD) {
        GranteepopupOLD.style.display = "none";
    }
    if (interviewpopupOLD) {
        interviewpopupOLD.style.display = "none";
    }
    if (CEAPconfigPopupOLD) {
        CEAPconfigPopupOLD.style.display = "none";
    }
    if (LPPPconfigPopupOLD) {
      LPPPconfigPopupOLD.style.display = "none";
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
  cancelButton.addEventListener("click", closeStatusPopupOLD);
});

function openConfirmationPopupOLD() {
    closeStatusPopupOLD();
  const confirmPopupOLD = document.getElementById("ConfrimMsgPopUp");
  confirmPopupOLD.style.display = "block";

  const okButton = document.getElementById("okConfirm");
  okButton.addEventListener("click", function () {
    confirmPopupOLD.style.display = "none";
    goBack();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(goBack, 5000);
}
