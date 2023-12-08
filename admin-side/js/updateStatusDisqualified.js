
const reasonInput = document.getElementById("disqualificationReason");
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
const disqualifiedpopup = document.getElementById("DisqualifiedPopUp");

function openDisqualifiedPopup(status, reason, applicantId) {
   document.getElementById("reasonModal").style.display = "none";
   disqualifiedpopup.style.display = "block";
}

function closeDisqualifiedpopup() {
   disqualifiedpopup.style.display = "none";
   openReasonModal(); // You should pass any required parameters to openReasonModal if needed
}
const cancelCloseButtons = document.querySelectorAll("#disqualified-cancel-button"); // Use querySelectorAll to select all matching elements
cancelCloseButtons.forEach((cancelButton) => {
   cancelButton.addEventListener("click", closeDisqualifiedpopup);
});
const closeSymbol = document.querySelector(".close");
if (closeSymbol) {
   closeSymbol.addEventListener("click", function () {
      const disqualificationReasonInput = document.getElementById("disqualificationReason");
      if (disqualificationReasonInput) {
         disqualificationReasonInput.value = ''; // Reset the input field
      }
      closeReasonModal();
   });
}

function closeReasonModal() {
   const Reasonmodal = document.getElementById("reasonModal");
   Reasonmodal.style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
    const confirmButton = document.getElementById("confirmButton");
    if (confirmButton) {
        confirmButton.addEventListener("click", function () {
            const status = "Disqualified"; // You can adjust this value as needed
            const reason = document.getElementById("disqualificationReason").value;
            const applicantId = ceapRegFormId; // Access the value from PHP
            submitStatusAndReason(status, reason, applicantId);
        });
    }
});


//disqualified and fail
let reason;
function openReasonModal(status) {
   const modal = document.getElementById("reasonModal");
   if (modal) {
      modal.style.display = "block";
      // Pass the status to the openDisqualifiedPopup function only if it's defined and not empty
      if (status) {
         const submitReasonButton = document.getElementById("submitReason");
         if (submitReasonButton) {
            submitReasonButton.onclick = function () {
               reason = document.getElementById("disqualificationReason").value;
               if (reason.trim() !== '') {
                  const applicantId = ceapRegFormId;
                  openDisqualifiedPopup(status, reason, applicantId);
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

function submitStatusAndReason(status, reason, applicantId) {
   // Send an AJAX request to update both status and reason
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "../../../php/updateReason.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
         var response = xhr.responseText.trim();
         console.log("Response from AJAX:", response); // Log the response
         if (response === 'success') {
            closeDisqualifiedpopup();
            closeReasonModal();
            openConfirmationPopup();
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

