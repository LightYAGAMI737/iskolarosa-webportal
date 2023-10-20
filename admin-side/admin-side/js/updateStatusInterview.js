
document.getElementById('confirmSetInterview').addEventListener('click', function() {
    // Gather the data you want to send
    const interviewDate = document.getElementById('interview_date').value;
    const interviewHours = document.getElementById('interview_hours').value;
    const interviewMinutes = document.getElementById('interview_minutes').value;
    const interviewAmPm = document.getElementById('interview_ampm').value;
    const limit = document.getElementById('limit').value;

    const data = {
        interview_date: interviewDate,
        interview_hours: interviewHours,
        interview_minutes: interviewMinutes,
        interview_ampm: interviewAmPm,
        limit: limit
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../../php/updateStatusInterview.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response && response.response === 'success') {
                  setTimeout(openConfirmationPopup, 1000);
                } else {
                    alert('Failed to update status.');
                    goBack(); 
                }
            } else {
                console.log('Request failed: ' + xhr.status + ' ' + xhr.statusText);
                alert('Request failed: ' + xhr.status + ' ' + xhr.statusText);
            }
        }
    };

    // Create a URL-encoded form data string
    const formData = new URLSearchParams(data).toString();

    xhr.send(formData);
});

function goBack() {
    location.reload();
}

function openConfirmationPopup() {
    // Close the verified popup
    closStatusPopup();
  
    const confirmPopup = document.getElementById("ConfrimMsgPopUp");
    confirmPopup.style.display = "block";
  
    // Add a click event listener to the "OK" button
    const okButton = document.getElementById("okConfirm");
    okButton.addEventListener("click", function () {
      confirmPopup.style.display = "none";
      // Call the goBack function when the "OK" button is clicked
      goBack();
    });
  
    // Call the goBack function after a 5-second delay
    setTimeout(goBack, 5000);
  }

  // Function to close the modal
function closeModal() {
    const modals = document.getElementById('myModal');
    modals.style.display = 'none';
  }