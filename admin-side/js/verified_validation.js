function seeMore(id) {
    // Redirect to a page where you can retrieve the reserved data based on the given ID
    window.location.href = "ceap_information.php?ceap_reg_form_id=" + id;
}

$(document).ready(function() {
             // Add an event listener to the search input field
             $('#search').on('input', function() {
                 searchApplicants();
             });
         });
         
         function searchApplicants() {
             var searchValue = $('#search').val().toUpperCase();
             var found = false; // Flag to track if any matching applicant is found
             $('.contents').each(function () {
                 var controlNumber = $(this).find('td:nth-child(2)').text().toUpperCase();
                 var lastName = $(this).find('td:nth-child(3)').text().toUpperCase();
                 if (searchValue.trim() === '' || controlNumber.includes(searchValue) || lastName.includes(searchValue)) {
                     $(this).show();
                     found = true;
                 } else {
                     $(this).hide();
                 }
             });
         
             // Display "No applicant found" message if no matching applicant is found
             if (!found) {
                 $('#noApplicantFound').show();
             } else {
                 $('#noApplicantFound').hide();
             }
         }
         // Get modal elements using plain JavaScript
         var openModalBtn = document.getElementById("openModalBtn");
         var closeModalBtn = document.getElementById("closeModalBtn");
         
         // Show modal when the button is clicked
         openModalBtn.addEventListener("click", function() {
            modal.style.display = "block";
         });
         
         // Close modal when the close button is clicked
         closeModalBtn.addEventListener("click", function() {
            modal.style.display = "none";
         });
         
         // Close modal when clicking outside the modal content
         window.addEventListener("click", function(event) {
            if (event.target === modal) {
               modal.style.display = "none";
            }
         });


         document.addEventListener("DOMContentLoaded", function() {
             const hoursInput = document.getElementById("interview_hours");
             hoursInput.addEventListener("input", function() {
               
                 const hoursValue = parseInt(hoursInput.value);
                 if (isNaN(hoursValue) || hoursValue < 1 || hoursValue > 12) {
                     hoursInput.value = '';
                 }
             });
             
             const minutesInput = document.getElementById("interview_minutes");
         minutesInput.addEventListener("input", function() {
         let minutesValue = minutesInput.value;
         
         // Remove leading zeros, except when the input is '0' or '00'
         minutesValue = minutesValue.replace(/^0+(?!$)/, '');
         
         // Ensure the value is within the valid range (0 to 59)
         const parsedMinutesValue = parseInt(minutesValue);
         if (isNaN(parsedMinutesValue) || parsedMinutesValue < 0 || parsedMinutesValue > 59) {
         minutesInput.value = '';
         } else {
         // Add leading zeros if the value is less than 10
         minutesInput.value = parsedMinutesValue < 10 ? `0${parsedMinutesValue}` : parsedMinutesValue.toString();
         }
         });
         
         
         });
         
         function formatInput(inputElement) {
             // Remove multiple consecutive white spaces
             inputElement.value = inputElement.value.replace(/\s+/g, ' ');
         
             // Convert input text to uppercase
             inputElement.value = inputElement.value.toUpperCase();
           }
         

