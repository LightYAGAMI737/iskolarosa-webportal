function expandImage(element) {
    var expandedImage = element.nextElementSibling;
    expandedImage.style.display = 'flex';
 }
 
 function collapseImage(element) {
    element.style.display = 'none';
 }
 
 function goBack() {
    window.history.back();
 }
 
//verified and not need reasons
function updateStatusOLD(status, applicantId) {
    // Send an AJAX request to update the applicant status
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../../php/updateStatusOldGrantee.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
       if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          // Handle the response here
          var response = xhr.responseText.trim(); // Trim whitespace from the response text
          if (response === 'success') {
             // Status updated successfully; open the confirmation popupOLD
             openConfirmationPopupOLD();
          } else {
             alert('Failed to update status.');
             goBack(); // Corrected function name
          }
       }
    };
    // Send the AJAX request
    xhr.send("status=" + status + "&id=" + applicantId); // Add this line to send data
 }