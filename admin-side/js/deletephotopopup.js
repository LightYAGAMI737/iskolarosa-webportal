const deletephotopopup = document.getElementById('deletephotoPopUp');

function opendeletephotopopup() {
    deletephotopopup.style.display ="block";
}
function closedeletephotopopup(){
    deletephotopopup.style.display="none";
}

const deletephotoconfirmButtons = document.querySelectorAll('.confirm-button');
 deletephotoconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const deletephotocancelButton = this.parentElement.querySelector('.cancel-button');
        deletephotocancelButton.classList.add('disabled');
    });
});


const deletephotocancelButtons = document.querySelectorAll(".cancel-button");
deletephotocancelButtons.forEach((deletephotocancelButton) => {
deletephotocancelButton.addEventListener("click", closedeletephotopopup);
});

function deletephotoconfirm(createPostId) {
  // Check if createPostId is valid
  if (createPostId) {
    console.log("createPostId:", createPostId);

    // Perform an AJAX request to delete the photo from the database
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/deletephotoconfirm.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Successfully deleted photo
                setTimeout(() => {
                    opendeleteConfirmationPopup();
                }, 1000); 
                console.log("Success: Photo deleted successfully.");
            } else {
                // Handle errors or display an error message
                console.error("Error deleting photo. Server returned status code: " + xhr.status);
            }
        }
    };

    // Send the request and include the create_post_id in the request data
    var data = "create_post_id=" + createPostId; // Use the parameter here
    xhr.send(data);
}
}



function opendeleteConfirmationPopup() {
    closedeletephotopopup();
  const confirmdeletePopup = document.getElementById("ConfrimdeleteMsgPopUp");
  confirmdeletePopup.style.display = "block";

  const okdeleteButton = document.getElementById("okConfirm");
  okdeleteButton.addEventListener("click", function () {
    confirmdeletePopup.style.display = "none";
    gobackreload();
  });
  // Call the goBack function after a 5-second delay
  setTimeout(gobackreload, 5000);
}

function gobackreload() {
    location.reload();
}