const deleteApplicantpopup = document.getElementById('DeleteApplicantLPPP');

function opendeleteApplicantpopupLPPP() {
  deleteApplicantpopup.style.display = "block";
}

function closedeleteApplicantpopupLPPP() {
  deleteApplicantpopup.style.display = "none";
}

const cancelButtonsDeleteApplicant = document.querySelectorAll(".delete-applicant");
cancelButtonsDeleteApplicant.forEach((cancelButtonDeleteApplicant) => {
  cancelButtonDeleteApplicant.addEventListener("click", closedeleteApplicantpopupLPPP);
});

function deleteApplicantLPPP(applicantId) {
        // Send an AJAX request to update is_deleted
        $.ajax({
            type: "POST",
            url: "./php/delete_applicantLPPPHA.php", // Replace with the actual URL to your PHP script
            data: {
                id: applicantId
            },
            success: function(response) {
                if (response === 'success') {
                    openDeleteConfirmationPopupLPPP();
                } else {
                    alert("Failed to delete the applicant.");
                }
            }
        });
}

function openDeleteConfirmationPopupLPPP() {
  closedeleteApplicantpopupLPPP();
  const confirmPopup = document.getElementById("ConfrimMsgDeleteApplicantLPPP");
  confirmPopup.style.display = "block";

  const okButton = document.getElementById("okConfirm");
  okButton.addEventListener("click", function () {
    confirmPopup.style.display = "none";
    goBacks();
  });

  // Call the goBack function after a 3-second delay
  setTimeout(goBacks, 3000);
}

function goBacks() {
  window.history.back();
}