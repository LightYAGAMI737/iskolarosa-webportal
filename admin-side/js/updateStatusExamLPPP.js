document.getElementById('confirmSetexamLPPP').addEventListener('click', function() {
    // Gather the data you want to send
    const examDate = document.getElementById('exam_date').value;
    const examHour = document.getElementById('exam_hour').value;
    const examMinutes = document.getElementById('exam_minutes').value;
    const examAmPm = document.getElementById('exam_ampm').value;
    const limit = document.getElementById('limit').value;

    const data = {
        exam_date: examDate,
        exam_hour: examHour,
        exam_minutes: examMinutes,
        exam_ampm: examAmPm,
        limit: limit
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateStatusExam.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') 
                {
                console.log('Request success: ' + xhr.status + ' ' + xhr.responseText);
                setTimeout(openconfirmationLPPPpopup, 1000);
                } else {
                console.log('Request failed: ' + xhr.status + ' ' + xhr.responseText);
                setTimeout(openconfirmationLPPPpopup, 1000);
                } 
            } else {
                console.log('Request failed: ' + xhr.status + ' ' + xhr.responseText);
                alert('Request failed: ' + xhr.status + ' ' + xhr.responseText);
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

const modalsetexam = document.getElementById('myModal');
function closeModalEXAMLPPP(){
    modalsetexam.style.display="none";
}

function openconfirmationLPPPpopup() {
    closeModalEXAMLPPP();
    closeLPPPStatusPopup();
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
