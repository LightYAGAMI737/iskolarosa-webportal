const lpppPopup = document.getElementById('LPPPConfirmPopUp');

function openLPPPpopup() {
    lpppPopup.style.display="block";
}
function closeLPPPpopup() {
    lpppPopup.style.display="none";
}


const LPPPconfirmButtons = document.querySelectorAll('.confirm-button');
LPPPconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const LPPPcancelButton = this.parentElement.querySelector('.cancel-button');
        LPPPcancelButton.classList.add('disabled');
    });
});

const LPPPcancelButtons = document.querySelectorAll(".cancel-button");
LPPPcancelButtons.forEach((LPPPcancelButtons) => {
LPPPcancelButtons.addEventListener("click", closeLPPPconfirmpoup);
});

function submitFormLPPP() {
  // Serialize the form data
  var formData = new FormData(document.getElementById('msform'));

  // Send an AJAX request to submit the form
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'lpppregformdatabaseinsert.php', true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  // Handle the response from the server
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        // Handle the response from the server here
        if (response === 'success') {
          console.log('Request successful. Response: ' + xhr.status);
          openLPPPConfirmationPopup();
        } else {
          openLPPPConfirmationPopup();
          console.log('Request successful, but an error occurred. Response: ' + xhr.responseText);
          // You can handle specific error cases here
        }
      } else {
        // Handle errors here, providing meaningful error messages or actions
        console.log('Request failed with status ' + xhr.status);
        // You can add more specific error handling here
      }
    }
  };

  // Error handler for network-related issues
  xhr.onerror = function () {
    console.log('Request failed due to a network error.');
    // You can take appropriate actions for network errors here
  };

  xhr.send(formData);
}


function openLPPPConfirmationPopup() {
    closeLPPPpopup();
const LPPPconfirmationPopup = document.getElementById("LPPPConfrimMsgPopUp");
LPPPconfirmationPopup.style.display = "block";

const LPPPokButton = document.getElementById("okConfirm");
LPPPokButton.addEventListener("click", function () {
  LPPPconfirmationPopup.style.display = "none";
  goBack();
});
// Call the goBack function after a 5-second delay
setTimeout(goBack, 20000);
}

function goBack() {
  window.location.href = "../home-page-two/home-page.php";
}

