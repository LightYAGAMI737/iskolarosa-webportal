const reasonInputFail = document.getElementById("FailReason");
const submitButtonFail = document.getElementById("submitReasonFail");
reasonInputFail.addEventListener("input", function () {
   if (reasonInputFail.value.length >= 5) {
      submitButtonFail.classList.remove("disabled");
      submitButtonFail.removeAttribute("disabled");
   } else {
      submitButtonFail.classList.add("disabled");
      submitButtonFail.setAttribute("disabled", "disabled");
   }
});
const FailPopUp = document.getElementById("FailPopUp");

function openFailPopUp(status, reasonFail, applicantId) {
   document.getElementById("reasonModalFail").style.display = "none";
   FailPopUp.style.display = "block";
}

function closeFailPopUp() {
   FailPopUp.style.display = "none";
   openReasonModalFail(); // You should pass any required parameters to openReasonModalFail if needed
}
const cancelCloseButtonsFail = document.querySelectorAll("#Fail-cancel-button"); // Use querySelectorAll to select all matching elements
cancelCloseButtonsFail.forEach((cancelButtonFail) => {
   cancelButtonFail.addEventListener("click", closeFailPopUp);
});
const closeSymbolFail = document.querySelector(".close");
if (closeSymbolFail) {
   closeSymbolFail.addEventListener("click", function () {
      const FailReasonInputFail = document.getElementById("FailReason");
      if (FailReasonInputFail) {
         FailReasonInputFail.value = ''; // Reset the input field
      }
      closeReasonModalFail();
   });
}

function closeReasonModalFail() {
   const reasonModalFail = document.getElementById("reasonModalFail");
   reasonModalFail.style.display = "none";
}


document.addEventListener("DOMContentLoaded", function () {
    const confirmButtonFail = document.getElementById("confirmButtonFail");
    if (confirmButtonFail) {
        confirmButtonFail.addEventListener("click", function () {
            const status = "Fail"; // You can adjust this value as needed
            const reasonFail = document.getElementById("FailReason").value;
            const applicantId = ceapRegFormId; // Access the value from PHP
            submitStatusAndReason(status, reasonFail, applicantId);
        });
    }
});

//Fail and fail
let reasonFail;
function openReasonModalFail(status) {
   const modal = document.getElementById("reasonModalFail");
   if (modal) {
      modal.style.display = "block";
      // Pass the status to the openFailPopUp function only if it's defined and not empty
      if (status) {
         const submitReasonFailButton = document.getElementById("submitReasonFail");
         if (submitReasonFailButton) {
            submitReasonFailButton.onclick = function () {
               reasonFail = document.getElementById("FailReason").value;
               if (reasonFail.trim() !== '') {
                  const applicantId = ceapRegFormId;
                  openFailPopUp(status, reasonFail, applicantId);
               } else {
                  alert('Please enter a reasonFail.');
               }
            };
         } else {
            console.error("Element with ID 'submitReasonFail' not found.");
         }
      }
   } else {
      console.error("Element with ID 'reasonModalFail' not found.");
   }
}

function submitStatusAndReason(status, reasonFail, applicantId) {
   // Send an AJAX request to update both status and reasonFail
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "../../../php/updateReasonFail.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
         var response = xhr.responseText.trim();
         console.log("Response from AJAX:", response); // Log the response
         if (response === 'success') {
            closeFailPopUp();
            closeReasonModalFail();
            openConfirmationPopup();
         } else {
            alert('Failed to update status and reasonFail.');
         }
      } else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
         console.error("AJAX request failed with status:", xhr.status); // Log AJAX errors
      }
   };
   // Send the AJAX request with status, reasonFail, and applicantId
   xhr.send("status=" + status + "&id=" + applicantId + "&reasonFail=" + reasonFail);
}

