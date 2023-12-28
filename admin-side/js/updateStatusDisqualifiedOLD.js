
const reasonInput = document.getElementById("disqualificationReasonOLD");
const submitButton = document.getElementById("submitReason");
reasonInput.addEventListener("input", function () {
   if (reasonInput.value.length >= 5) {
      submitButton.classList.remove("disabled");
      submitButton.removeAttribute("disabled");
   } else {
      submitButton.classList.add("disabled");
      submitButton.setAttribute("disabled", "disabled");
   }
});
const DisqualifiedPopUpOLD = document.getElementById("DisqualifiedPopUpOLD");

function openDisqualifiedPopUpOLD(status, reason, applicantId) {
   document.getElementById("reasonModalOLD").style.display = "none";
   DisqualifiedPopUpOLD.style.display = "block";
}

function closeDisqualifiedPopUpOLD() {
   DisqualifiedPopUpOLD.style.display = "none";
   openReasonModalOLD(); // You should pass any required parameters to openReasonModalOLD if needed
}
const cancelCloseButtons = document.querySelectorAll("#disqualified-cancel-button"); // Use querySelectorAll to select all matching elements
cancelCloseButtons.forEach((cancelButton) => {
   cancelButton.addEventListener("click", closeDisqualifiedPopUpOLD);
});
const closeSymbol = document.querySelector(".close");
if (closeSymbol) {
   closeSymbol.addEventListener("click", function () {
      const disqualificationReasonInput = document.getElementById("disqualificationReasonOLD");
      if (disqualificationReasonInput) {
         disqualificationReasonInput.value = ''; // Reset the input field
      }
      closeReasonModalOLD();
   });
}

function closeReasonModalOLD() {
   const ReasonmodalOLD = document.getElementById("reasonModalOLD");
   ReasonmodalOLD.style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
    const confirmButtonOLD = document.getElementById("confirmButtonOLD");
    if (confirmButtonOLD) {
        confirmButtonOLD.addEventListener("click", function () {
            const status = "Disqualified"; // You can adjust this value as needed
            const reason = document.getElementById("disqualificationReasonOLD").value;
            const applicantId = ceapRegFormIdOLD; // Access the value from PHP
            submitStatusAndReasonOLD(status, reason, applicantId);
        });
    }
});


//disqualified and fail
let reason;
function openReasonModalOLD(status) {
   const modal = document.getElementById("reasonModalOLD");
   if (modal) {
      modal.style.display = "block";
      // Pass the status to the openDisqualifiedPopUpOLD function only if it's defined and not empty
      if (status) {
         const submitReasonButton = document.getElementById("submitReason");
         if (submitReasonButton) {
            submitReasonButton.onclick = function () {
               reason = document.getElementById("disqualificationReasonOLD").value;
               if (reason.trim() !== '') {
                  const applicantId = ceapRegFormIdOLD;
                  openDisqualifiedPopUpOLD(status, reason, applicantId);
               } else {
                  alert('Please enter a reason.');
               }
            };
         } else {
            console.error("Element with ID 'submitReason' not found.");
         }
      }
   } else {
      console.error("Element with ID 'reasonModalOLD' not found.");
   }
}

function submitStatusAndReasonOLD(status, reason, applicantId) {
   // Send an AJAX request to update both status and reason
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "../../../php/updateReasonOldGrantee.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
         var response = xhr.responseText.trim();
         console.log("Response from AJAX:", response); // Log the response
         if (response === 'success') {
            closeDisqualifiedPopUpOLD();
            closeReasonModalOLD();
            openConfirmationPopupOLD();
         } else {
            alert('Failed to update status and reason.');
         }
      } else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
         console.error("AJAX request failed with status:", xhr.status); // Log AJAX errors
      }
   };
   // Send the AJAX request with status, reason, and applicantId
   xhr.send("status=" + status + "&id=" + applicantId + "&reason=" + reason);
}

