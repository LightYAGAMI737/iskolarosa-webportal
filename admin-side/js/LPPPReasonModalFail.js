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
const LPPPFailPopUp = document.getElementById("LPPPFailPopUp");

function openLPPPFailPopUp(status, reasonFail, applicantId) {
   document.getElementById("reasonModalLPPPFail").style.display = "none";
   LPPPFailPopUp.style.display = "block";
}

function closeLPPPFailPopUp() {
   LPPPFailPopUp.style.display = "none";
   openReasonModalLPPPFail(); // You should pass any required parameters to openReasonModalLPPPFail if needed
}
const cancelCloseButtonsFail = document.querySelectorAll("#disqualified-cancel-button"); // Use querySelectorAll to select all matching elements
cancelCloseButtonsFail.forEach((cancelButtonFail) => {
   cancelButtonFail.addEventListener("click", closeLPPPFailPopUp);
});
const closeSymbolFail = document.querySelector(".close");
if (closeSymbolFail) {
   closeSymbolFail.addEventListener("click", function () {
      const FailReasonInput = document.getElementById("FailReason");
      if (FailReasonInput) {
         FailReasonInput.value = ''; // Reset the input field
      }
      closeReasonModalLPPPFail();
   });
}

function closeReasonModalLPPPFail() {
   const Fail = document.getElementById("reasonModalLPPPFail");
   Fail.style.display = "none";
}

//disqualified and fail
let reasonFail;
function openReasonModalLPPPFail(status) {
   const modal = document.getElementById("reasonModalLPPPFail");
   if (modal) {
      modal.style.display = "block";
      // Pass the status to the openLPPPFailPopUp function only if it's defined and not empty
      if (status) {
         const submitReasonButton = document.getElementById("submitReasonFail");
         if (submitReasonButton) {
            submitReasonButton.onclick = function () {
               reasonFail = document.getElementById("FailReason").value;
               if (reasonFail.trim() !== '') {
                  const applicantId = LPPPregFormID;
                  openLPPPFailPopUp(status, reasonFail, applicantId);
               } else {
                  alert('Please enter a reasonFail.');
               }
            };
         } else {
            console.error("Element with ID 'submitReason' not found.");
         }
      }
   } else {
      console.error("Element with ID 'Fail' not found.");
   }
}

document.addEventListener("DOMContentLoaded", function () {
    const LPPPFailconfirmButton = document.getElementById("LPPPFailconfirmButton");
    if (LPPPFailconfirmButton) {
        LPPPFailconfirmButton.addEventListener("click", function () {
            const status = "Fail"; // You can adjust this value as needed
            const reasonFail = document.getElementById("FailReason").value;
            const applicantId = LPPPregFormID; // Access the value from PHP
            submitStatusAndReasonLPPPFail(status, reasonFail, applicantId);
        });
    }
});

function submitStatusAndReasonLPPPFail(status, reasonFail, applicantId) {
   // Send an AJAX request to update both status and reasonFail
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "../../../php/updateReasonLPPPFail.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
         var response = xhr.responseText.trim();
         console.log("Response from AJAX:", response); // Log the response
         if (response === 'success') {
            closeLPPPFailPopUp();
            closeReasonModalLPPPFail();
            openconfirmationLPPPpopup();
         } else {
            alert('Failed to update status and reasonFail.');
         }
      } else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
         console.error("AJAX request failed with status:", xhr.status);
         console.log("Response FAILED from AJAX:", xhr.responseText); // Log the response
         // Log AJAX errors
     }
   };
   // Send the AJAX request with status, reasonFail, and applicantId
   xhr.send("status=" + status + "&id=" + applicantId + "&reasonFail=" + reasonFail);
}