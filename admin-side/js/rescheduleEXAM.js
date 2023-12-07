function RescheduleEXAM() {
    // Gather the data you want to send
    const RescheduleexamDate = document.getElementById('exam_date').value;
    const RescheduleexamHours = document.getElementById('exam_hours').value;
    const RescheduleexamMinutes = document.getElementById('exam_minutes').value;
    const RescheduleexamAmPm = document.getElementById('exam_ampm').value;
    const Reschedulelimit = document.getElementById('reschedlimit').value;

    const data = {
        exam_date: RescheduleexamDate,
        exam_hours: RescheduleexamHours,
        exam_minutes: RescheduleexamMinutes,
        exam_ampm: RescheduleexamAmPm,
        limit: Reschedulelimit
    };

    console.log('Data to be sent:', data); // Log the data you're sending

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'rescheduleEXAM.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log('Response received:', xhr.responseText); // Log the response
            if (xhr.status === 200) {
                const responseParts = xhr.responseText.split('|');
                if (responseParts[0] === 'success') {
                    console.log('Success: closeRescheduleAPLAYA');
                    setTimeout(openConfirmationEXAMPopup, 1000);
                } else {
                    console.log('Response is not "success".');
                    setTimeout(openConfirmationEXAMPopup, 1000);
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
