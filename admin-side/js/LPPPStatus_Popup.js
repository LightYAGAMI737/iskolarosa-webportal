   // JavaScript code to handle the popup
   const LPPPVerifiedpopup = document.getElementById("LPPPverifiedPopUp");
   const Granteepopup = document.getElementById("GranteePopUp");
   const examLPPPpopup = document.getElementById("examLPPPPopUp");
   const interviewLPPPPpopup = document.getElementById("interviewLPPPPopUp");
   const CEAPconfigPopup = document.getElementById("CEAPconfigurationPopUp");
   const LPPPconfigPopup = document.getElementById("LPPPconfigurationPopUp");
   const postdeletepopup = document.getElementById("DeletepostPopUp");

   function openLPPPVerifiedPopup() {
       LPPPVerifiedpopup.style.display = "block";
   }

    function openInterviewExamLPPP() {
        interviewLPPPPpopup.style.display = "block";
    }

    function openexamLPPPPopup() {
        examLPPPpopup.style.display = "block";
    }
   function openGranteePopup() {
       Granteepopup.style.display = "block";
   }

   function closeLPPPStatusPopup() {
    if (LPPPVerifiedpopup) {
        LPPPVerifiedpopup.style.display = "none";
    }
    if (Granteepopup) {
        Granteepopup.style.display = "none";
    }
    if (interviewLPPPPpopup) {
        interviewLPPPPpopup.style.display = "none";
    }
    if (CEAPconfigPopup) {
        CEAPconfigPopup.style.display = "none";
    }
    if (examLPPPpopup) {
        examLPPPpopup.style.display = "none";
    }
  if (postdeletepopup) {
    postdeletepopup.style.display = "none";
}
}


  const StatusLPPPconfirmButtons = document.querySelectorAll('.confirm-button');
  StatusLPPPconfirmButtons.forEach(function(button) {
      button.addEventListener('click', function() {
          this.classList.add('disabled');
          const LPPPcancelButton = this.parentElement.querySelector('.cancel-button');
          LPPPcancelButton.classList.add('disabled');
      });
  });

 
const LPPPcancelButtons = document.querySelectorAll(".cancel-button");
LPPPcancelButtons.forEach((LPPPcancelButton) => {
  LPPPcancelButton.addEventListener("click", closeLPPPStatusPopup);
});

function openconfirmationLPPPpopup(){
    closeLPPPStatusPopup();
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
