const reasonInput = document.getElementById("disqualificationReasonLPPP");
const submitButton = document.getElementById("submitReasonLPPP");
const otherReasonInput = document.getElementById("otherReason");

function validateOtherReason() {
    const isValid = otherReasonInput.value.trim().length >= 5 && otherReasonInput.value.match(/^\S+(\s\S+)*$/);
    return isValid;
}

reasonInput.addEventListener("input", function () {
   const selectedReason = reasonInput.value;
   const isValidOtherReason = selectedReason === "Others" && otherReasonInput.value.trim().length >= 5;

   if ((selectedReason && selectedReason !== "Others") || isValidOtherReason) {
       submitButton.classList.remove("disabled");
       submitButton.removeAttribute("disabled");
   } else {
       submitButton.classList.add("disabled");
       submitButton.setAttribute("disabled", "disabled");
   }
});

otherReasonInput.addEventListener("input", function () {
   if (reasonInput.value === "Others" && otherReasonInput.value.trim().length >= 5) {
       submitButton.classList.remove("disabled");
       submitButton.removeAttribute("disabled");
   } else {
       submitButton.classList.add("disabled");
       submitButton.setAttribute("disabled", "disabled");
   }
});

const LPPPDisqualifiedPopUp = document.getElementById("LPPPDisqualifiedPopUp");

function openLPPPDisqualifiedPopUp(status, reason, LPPPapplicantId) {
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
                  const LPPPapplicantId = LPPPregFormID;
                  openLPPPDisqualifiedPopUp(status, reason, LPPPapplicantId);
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
           const reasonDropdown = document.getElementById("disqualificationReasonLPPP");
           const reason = reasonDropdown.value;
           const otherReasonInput = document.getElementById("otherReason");
           const otherReason = otherReasonInput.value;
           const LPPPapplicantId = LPPPregFormID; // Access the value from PHP

           // If the reason is "Others," use the value from the "otherReason" input
           const finalReason = (reason === "Others") ? otherReason : reason;

           submitStatusAndReasonLPPP(status, finalReason, LPPPapplicantId);
       });
   }
});

function submitStatusAndReasonLPPP(status, reason, LPPPapplicantId) {
   // Send an AJAX request to update both status and reason
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "../../../php/updateReasonLPPP.php", true);
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
   // Send the AJAX request with status, reason, and LPPPapplicantId
   xhr.send("status=" + status + "&reason=" + reason + "&id=" + LPPPapplicantId );
}


function openconfirmationLPPPpopup(){
 const confirmPopup = document.getElementById("ConfrimMsgPopUp");
 confirmPopup.style.display = "block";

 const okButton = document.getElementById("okConfirm");
 okButton.addEventListener("click", function () {
   confirmPopup.style.display = "none";
   goBackPrev();
 });
 // Call the goBack function after a 5-second delay
 setTimeout(goBackPrev, 5000);
}
function goBackPrev() {
   window.history.back();
}