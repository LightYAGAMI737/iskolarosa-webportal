function LPPPSetInterview() {
    // Gather the data you want to send
    const SetinterviewDate = document.getElementById('set_interview_date').value;
    const SetinterviewHours = document.getElementById('set_interview_hours').value;
    const SetinterviewMinutes = document.getElementById('set_interview_minutes').value;
    const SetinterviewAmPm = document.getElementById('set_interview_ampm').value;
    const Setlimit = document.getElementById('SetInterviewlimit').value;

    const data = {
        interview_date: SetinterviewDate,
        interview_hours: SetinterviewHours,
        interview_minutes: SetinterviewMinutes,
        interview_ampm: SetinterviewAmPm,
        limit: Setlimit
    };

    console.log('Data to be sent:', data); // Log the data you're sending

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'LPPPSetInterview.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('Response received:', xhr.responseText); // Log the response
            if (xhr.status === 200) {
                const responseParts = xhr.responseText.split('|');
                if (responseParts[0] === 'success') {
                    console.log('Success: closeSetAPLAYA');
                    setTimeout(openConfirmationEXAMPop, 1000);
                } else {
                    console.log('Response is not "success".');
                    setTimeout(openConfirmationEXAMPop, 1000);
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
