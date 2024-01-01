const reasonInput = document.getElementById("disqualificationReasonLPPP");
const submitButton = document.getElementById("submitReasonLPPP");
reasonInput.addEventListener("input", function () {
   if (reasonInput.value.length >= 5) {
      submitButton.classList.remove("disabled");
      submitButton.removeAttribute("disabled");
   } else {
      submitButton.classList.add("disabled");
      submitButton.setAttribute("disabled", "disabled");
   }
});
const LPPPDisqualifiedPopUp = document.getElementById("LPPPDisqualifiedPopUp");

function openLPPPDisqualifiedPopUp(status, reason, applicantId) {
   document.getElementById("reasonModalLPPP").style.display = "none";
   LPPPDisqualifiedPopUp.style.display = "block";
}

function closeLPPPDisqualifiedPopUp() {
   LPPPDisqualifiedPopUp.style.display = "none";
   openReasonModalLPPP(); // You should pass any required parameters to openReasonModalLPPP if needed
}
const cancelCloseButtons = document.querySelectorAll("#disqualified-cancel-button"); // Use querySelectorAll to select all matching elements
cancelCloseButtons.forEach((cancelButton) => {
   cancelButton.addEventListener("click", closeLPPPDisqualifiedPopUp);
});
const closeSymbol = document.querySelector(".close");
if (closeSymbol) {
   closeSymbol.addEventListener("click", function () {
      const disqualificationReasonInput = document.getElementById("disqualificationReasonLPPP");
      if (disqualificationReasonInput) {
         disqualificationReasonInput.value = ''; // Reset the input field
      }
      closeReasonModalLPPP();
   });
}

function closeReasonModalLPPP() {
   const Reasonmodal = document.getElementById("reasonModalLPPP");
   Reasonmodal.style.display = "none";
}

//disqualified and fail
let reason;
function openReasonModalLPPP(status) {
   const modal = document.getElementById("reasonModalLPPP");
   if (modal) {
      modal.style.display = "block";
      // Pass the status to the openLPPPDisqualifiedPopUp function only if it's defined and not empty
      if (status) {
         const submitReasonButton = document.getElementById("submitReasonLPPP");
         if (submitReasonButton) {
            submitReasonButton.onclick = function () {
               reason = document.getElementById("disqualificationReasonLPPP").value;
               if (reason.trim() !== '') {
                  const applicantId = LPPPregFormID;
                  openLPPPDisqualifiedPopUp(status, reason, applicantId);
               } else {
                  alert('Please enter a reason.');
               }
            };
         } else {
            console.error("Element with ID 'submitReason' not found.");
         }
      }
   } else {
      console.error("Element with ID 'reasonModal' not found.");
   }
}

document.addEventListener("DOMContentLoaded", function () {
    const LPPPconfirmButton = document.getElementById("LPPPconfirmButton");
    if (LPPPconfirmButton) {
        LPPPconfirmButton.addEventListener("click", function () {
            const status = "Disqualified"; // You can adjust this value as needed
            const reason = document.getElementById("disqualificationReasonLPPP").value;
            const applicantId = LPPPregFormID; // Access the value from PHP
            submitStatusAndReasonLPPP(status, reason, applicantId);
        });
    }
});

function submitStatusAndReasonLPPP(status, reason, applicantId) {
   // Send an AJAX request to update both status and reason
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "./php/updateReasonLPPP.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
         var response = xhr.responseText.trim();
         console.log("Response from AJAX:", response); // Log the response
         if (response === 'success') {
            closeLPPPDisqualifiedPopUp();
            closeReasonModalLPPP();
            openconfirmationLPPPpopup();
         } else {
            alert('Failed to update status and reason.');
         }
      } else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
         console.error("AJAX request failed with status:", xhr.status);
         console.log("Response FAILED from AJAX:", xhr.responseText); // Log the response
         // Log AJAX errors
     }
   };
   // Send the AJAX request with status, reason, and applicantId
   xhr.send("status=" + status + "&id=" + applicantId + "&reason=" + reason);
}