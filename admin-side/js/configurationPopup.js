const CEAPconfigurationPopup = document.getElementById("CEAPconfigurationPopUp");
const LPPPconfigurationPopup = document.getElementById("LPPPconfigurationPopUp");

function openCEAPConfigurationPopup() {
    CEAPconfigurationPopup.style.display = "block";
}
function openLPPPConfigurationPopup() {
    LPPPconfigurationPopup.style.display = "block";
}
function closeLPPPConfigurationPopup() {
    LPPPconfigurationPopup.style.display = "none";
}
function closeCEAPConfigurationPopup() {
    CEAPconfigurationPopup.style.display = "none";
}

function formatInput(inputElement) {
    // Replace multiple consecutive spaces with a single space
    inputElement.value = inputElement.value.replace(/ +/g, ' ');
}

//error message
const errorstartmsgconfiguration = document.getElementById("errorstartpopupinside");
const errorendmsgconfiguration = document.getElementById("errorendpopupinside");

function openenderrormsgconfig() {
    errorendmsgconfiguration.style.display="block";
}
function openstarterrormsgconfig() {
    errorstartmsgconfiguration.style.display="block";
}

function closeerrormsgconfig(){
    errorendmsgconfiguration.style.display="none";
    errorstartmsgconfiguration.style.display="none";
    
}

function openConfigConfirmationPopup() {
    closeCEAPConfigurationPopup();
    closeLPPPConfigurationPopup();

    const configConfrimPopup = document.getElementById('ConfigConfrimMsgPopUp');
    configConfrimPopup.style.display = "block";
    const okConfigButton = document.getElementById("okConfigConfirm");
    okConfigButton.addEventListener("click", function () {
        configConfrimPopup.style.display = "none";
        location.reload();
    });
    // Call the location.reload function after a 5-second delay
    setTimeout(function () {
        location.reload();
    }, 5000);
}

function CEAPSubmitConfig() {
    const xhr = new XMLHttpRequest();
    const url = './php/ceapconfigurationinsert.php';
    const method = 'POST';
    const formData = new FormData(document.querySelector('form'));
    xhr.open(method, url, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Ajax request successful');
            setTimeout(openConfigConfirmationPopup,1500);
        } else {
            alert('Error updating configuration');
            console.error('Ajax request failed');
        }
    };

    xhr.onerror = function () {
        console.error('Network error occurred during the Ajax request');
    };

    xhr.send(formData);
}


function LPPPSubmitConfig() {
    const xhr = new XMLHttpRequest();
    const url = './php/lpppconfigurationinsert.php';
    const method = 'POST';
    const formData = new FormData(document.querySelector('form'));
    xhr.open(method, url, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Ajax request successful');
            setTimeout(openConfigConfirmationPopup,1500);
        } else {
            alert('Error updating configuration');
            console.error('Ajax request failed');
        }
    };

    xhr.onerror = function () {
        console.error('Network error occurred during the Ajax request');
    };

    xhr.send(formData);
}