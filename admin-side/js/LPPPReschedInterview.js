   // Get modal elements using plain JavaScript
   var modal = document.getElementById("InterviewmyModal");
   var openModalBtn = document.getElementById("openModalBtn");
   var closeModalBtnInterview = document.getElementById("closeModalBtnInterview");

   // Show modal when the button is clicked
   openModalBtn.addEventListener("click", function() {
      modal.style.display = "block";
   });

    // Function to close the modal
    function closeInterviewModal() {
        modal.style.display = "none";
    }

    // Close the modal when the close button is clicked
    document.getElementById("closeModalBtnInterview").addEventListener("click", function() {
        closeInterviewModal();
    });

   // Close modal when clicking outside the modal content
   window.addEventListener("click", function(event) {
      if (event.target === modal) {
         modal.style.display = "none";
      }
   });
   
const limitInput = document.getElementById('SetInterviewlimit');
limitInput.addEventListener('input', function() {
    const userInput = limitInput.value.trim();
    let sanitizedInput = userInput.replace(/^0+|(\..*)\./gm, '$0');

    if (sanitizedInput === '' || isNaN(sanitizedInput)) {
        limitInput.value = '';
    } else {
        const parsedInput = parseInt(sanitizedInput);

        // Ensure the value is within the valid range
        const validValue = Math.min(Math.max(parsedInput, 1), 200);

        limitInput.value = validValue;
    }
});


document.addEventListener("DOMContentLoaded", function() {
    const hoursInput = document.getElementById("interview_hours");
    hoursInput.addEventListener("input", function() {
      
        const hoursValue = parseInt(hoursInput.value);
        if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
            hoursInput.value = '';
        }
    });

    const minutesInput = document.getElementById("interview_minutes");
    minutesInput.addEventListener("input", function() {
         let minutesValue = minutesInput.value;
         
         // Remove leading zeros, except when the input is '0' or '00'
         minutesValue = minutesValue.replace(/^0+(?!$)/, '');
         
         // Ensure the value is within the valid range (0 to 59)
         const parsedMinutesValue = parseInt(minutesValue);
         if (isNaN(parsedMinutesValue) || parsedMinutesValue < 0 || parsedMinutesValue > 59) {
         minutesInput.value = '';
         } else {
         // Add leading zeros if the value is less than 10
         minutesInput.value = parsedMinutesValue < 10 ? `0${parsedMinutesValue}` : parsedMinutesValue.toString();
         }
    });
});

const InterviewEXAMconfirmButtons = document.querySelectorAll('.confirm-button');
InterviewEXAMconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const InterviewEXAMcancelButton = this.parentElement.querySelector('.cancel-button');
        InterviewEXAMcancelButton.classList.add('disabled');
    });
});


const InterviewEXAMcancelButtons = document.querySelectorAll(".cancel-button");
InterviewEXAMcancelButtons.forEach((InterviewEXAMcancelButton) => {
InterviewEXAMcancelButton.addEventListener("click", closeRescheduleExamLPPP);
});

function openConfirmationEXAMPopup() {
    closeLPPPStatusPopup();
const confirmPopup = document.getElementById("ConfrimMsgPopUp");
confirmPopup.style.display = "block";

const okButton = document.getElementById("okConfirm");
okButton.addEventListener("click", function () {
  confirmPopup.style.display = "none";
  goBackInterviewEXAMLPPP();
});
// Call the goBackInterviewEXAMLPPP function after a 5-second delay
setTimeout(goBackInterviewEXAMLPPP, 5000);
}

function goBackInterviewEXAMLPPP() {
  location.reload();
}