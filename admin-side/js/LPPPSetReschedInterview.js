function LPPPSetReschedInterview() {
    // Gather the data you want to send
    const SetReschedinterviewDate = document.getElementById('interview_date').value;
    const SetReschedinterviewHours = document.getElementById('interview_hours').value;
    const SetReschedinterviewMinutes = document.getElementById('interview_minutes').value;
    const SetReschedinterviewAmPm = document.getElementById('interview_ampm').value;
    const SetReschedlimit = document.getElementById('SetReschedInterviewlimit').value;

    const data = {
        Reschedinterview_date: SetReschedinterviewDate,
        Reschedinterview_hours: SetReschedinterviewHours,
        Reschedinterview_minutes: SetReschedinterviewMinutes,
        Reschedinterview_ampm: SetReschedinterviewAmPm,
        Reschedlimit: SetReschedlimit
    };

    console.log('Data to be sent:', data); // Log the data you're sending

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'LPPPSetRescheduleInterview.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('Response received:', xhr.responseText); // Log the response
            if (xhr.status === 200) {
                const responseParts = xhr.responseText.split('|');
                if (responseParts[0] === 'success') {
                    console.log('Success: closeSetAPLAYA');
                    setTimeout(openConfirmationEXAMPopup, 1000);
                } else {
                    console.log('Response is not "success".');
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
