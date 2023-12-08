const limitInputResched = document.getElementById('reschedlimit');
limitInputResched.addEventListener('input', function() {
    const userInputResched = limitInputResched.value.trim();
    let sanitizedInput = userInputResched.replace(/^0+|(\..*)\./gm, '$0');

    if (sanitizedInput === '' || isNaN(sanitizedInput)) {
        limitInputResched.value = '';
    } else {
        const parsedInputResched = parseInt(sanitizedInput);

        // Ensure the value is within the valid range
        const validValueResched = Math.min(Math.max(parsedInputResched, 1), 200);

        limitInputResched.value = validValueResched;
    }
});

// Function to open the reschedule modalReschedule
function openRescheduleModal() {
    var rescheduleButton = document.getElementById("rescheduleButton");
    if (!rescheduleButton.disabled) {
        var modalReschedule = document.getElementById("reschedulemyModal");
        modalReschedule.style.display = "block";
    }
}

// Function to close the modalReschedule
function closeRescheduleModal() {
    var modalReschedule = document.getElementById("reschedulemyModal");
    modalReschedule.style.display = "none";
}

// Close the modalReschedule when the close button is clicked
document.getElementById("closeModalBtnReschedule").addEventListener("click", function() {
    closeRescheduleModal();
});

// Close the modalReschedule when clicking outside of it
window.onclick = function(event) {
    var modalReschedule = document.getElementById("reschedulemyModal");
    if (event.target === modalReschedule) {
        closeRescheduleModal();
    }
}

// DOMContentLoaded event
document.addEventListener("DOMContentLoaded", function() {
    const hoursInputExam = document.getElementById("exam_hours");
    hoursInputExam.addEventListener("input", function() {
        const hoursValueExam = parseInt(hoursInputExam.value);
        if (isNaN(hoursValueExam) || hoursValueExam < 1 || hoursValueExam > 12) {
            hoursInputExam.value = '';
        }
    });

    const minutesInputExam = document.getElementById("exam_minutes");
    minutesInputExam.addEventListener("input", function() {
        let minutesValueExam = minutesInputExam.value;

        // Remove leading zeros, except when the input is '0' or '00'
        minutesValueExam = minutesValueExam.replace(/^0+(?!$)/, '');

        // Ensure the value is within the valid range (0 to 59)
        const parsedMinutesValueExam = parseInt(minutesValueExam);
        if (isNaN(parsedMinutesValueExam) || parsedMinutesValueExam < 0 || parsedMinutesValueExam > 59) {
            minutesInputExam.value = '';
        } else {
            // Add leading zeros if the value is less than 10
            minutesInputExam.value = parsedMinutesValueExam < 10 ? `0${parsedMinutesValueExam}` : parsedMinutesValueExam.toString();
        }
    });
});

const openReschedExam = document.getElementById('RescheduleEXAMPopUp');
function openRescheduleExamLPPP() {
    closeRescheduleModal();
    openReschedExam.style.display = "block";
}
function closeRescheduleExamLPPP() {
    openReschedExam.style.display = "none";
}


const EXAMconfirmButtons = document.querySelectorAll('.confirm-button');
EXAMconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const EXAMcancelButton = this.parentElement.querySelector('.cancel-button');
        EXAMcancelButton.classList.add('disabled');
    });
});


const EXAMcancelButtons = document.querySelectorAll(".cancel-button");
EXAMcancelButtons.forEach((EXAMcancelButton) => {
EXAMcancelButton.addEventListener("click", closeRescheduleExamLPPP);
});

function openConfirmationEXAMPopup() {
  closeRescheduleExamLPPP();
const confirmPopup = document.getElementById("ConfrimMsgEXAMPopUp");
confirmPopup.style.display = "block";

const okButton = document.getElementById("okConfirm");
okButton.addEventListener("click", function () {
  confirmPopup.style.display = "none";
  goBackEXAMLPPP();
});
// Call the goBackEXAMLPPP function after a 5-second delay
setTimeout(goBackEXAMLPPP, 5000);
}

function goBackEXAMLPPP() {
  location.reload();
}