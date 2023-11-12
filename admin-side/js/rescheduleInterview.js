function RescheduleAplaya() {
    // Gather the data you want to send
    const RescheduleinterviewDate = document.getElementById('interview_date').value;
    const RescheduleinterviewHours = document.getElementById('interview_hours').value;
    const RescheduleinterviewMinutes = document.getElementById('interview_minutes').value;
    const RescheduleinterviewAmPm = document.getElementById('interview_ampm').value;
    const Reschedulelimit = document.getElementById('limit').value;

    const data = {
        interview_date: RescheduleinterviewDate,
        interview_hours: RescheduleinterviewHours,
        interview_minutes: RescheduleinterviewMinutes,
        interview_ampm: RescheduleinterviewAmPm,
        limit: Reschedulelimit
    };

    console.log('Data to be sent:', data); // Log the data you're sending

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'rescheduleAPLAYA.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('Response received:', xhr.responseText); // Log the response
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    console.log('Success: closeRescheduleAPLAYA');
                    setTimeout(openConfirmationPopup, 1000);
                } else {
                    console.log('Response is not "success".');
                    setTimeout(openConfirmationPopup, 1000);
                }
            } else {
                console.log('Request failed: ' + xhr.status + ' ' + xhr.statusText);
                alert('Request failed: ' + xhr.status + ' ' + xhr.statusText);
            }
        }
    };

    // Create a URL-encoded form data string
    const formData = new URLSearchParams(data).toString();

    console.log('Sending request with data:', formData); // Log the data being sent

    xhr.send(formData);
}
