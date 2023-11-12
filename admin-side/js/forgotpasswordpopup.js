$(document).ready(function () {
    // Handle form submission with AJAX
    $('#submitForgotPassword').click(function (event) {
        event.preventDefault();
        var email = $('#floatingInput').val();

        // Create an XHR object
        var xhr = new XMLHttpRequest();

        // Define the callback for when the request is complete
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === 'success') {
                    openemailconfirmpopup();
                } else {
                    // Handle the case where email sending failed
                    console.log('Email sending failed. Response: ' + response);
                    alert('Email sending failed.'); // You can keep the alert for user notification
                }
            } else {
                // Handle errors here
                console.log('XHR Request failed with status: ' + xhr.status);
            }
        };

        // Define the request details
        xhr.open('POST', './php/forgetpasswordemail.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // Build the data to send
        var data = 'email=' + email;

        // Send the request
        xhr.send(data);
    });
});

function openemailconfirmpopup() {
    const confirmessageemail = document.getElementById("EmailConfrimMsgPopUp"); // Correct the ID here
    confirmessageemail.style.display = "block";
  
    const okButtonemail = document.getElementById("okConfirm");
    okButtonemail.addEventListener("click", function () {
      confirmessageemail.style.display = "none";
      goBackemail();
    });
    // Call the goBack function after a 5-second delay
    setTimeout(goBackemail, 10000);
}

function goBackemail() {
    window.location.href="admin_index.php";
}
