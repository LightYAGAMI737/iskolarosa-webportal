const RescheduleAPLAYA = document.getElementById ('RescheduleAplayaPopUp');

function openRescheduleAPLAYA(){
    RescheduleAPLAYA.style.display="block";
}
function closeRescheduleAPLAYA(){
    RescheduleAPLAYA.style.display="none";
    goBack();
}

function openConfirmationPopup() {
    closeRescheduleAPLAYA();
    closeModalInterview();
    const RescheduleconfirmPopup = document.getElementById("RescheduleMsgPopUp");
    RescheduleconfirmPopup.style.display = "block";
  
    // Add a click event listener to the "OK" button
    const rescheduleokButton = document.getElementById("okConfirm");
    rescheduleokButton.addEventListener("click", function () {
      RescheduleconfirmPopup.style.display = "none";
      // Call the goBack function when the "OK" button is clicked
      goBack();
    });
  
    // Call the goBack function after a 5-second delay
    setTimeout(goBack, 5000);
  }

 
 
const ReschedulecancelButtons = document.querySelectorAll(".cancel-button");
ReschedulecancelButtons.forEach((ReschedulecancelButton) => {
  ReschedulecancelButton.addEventListener("click", closeRescheduleAPLAYA);
});

function goBack() {
    location.reload();
}
  // Function to close the modal
  function closeModalInterview() {
    const modals = document.getElementById('myModal');
    modals.style.display = 'none';
  }