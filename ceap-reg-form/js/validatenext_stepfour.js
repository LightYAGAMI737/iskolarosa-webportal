 // Function to display user input inside the fieldset
 function displayUserInput() {
    //personal
    var lastName = document.getElementById("last_name").value;
    var firstName = document.getElementById("first_name").value;
    var middleName = document.getElementById("middle_name").value || "N/A";
    var suffixName = document.getElementById("suffix_name").value || "N/A";
    var gender = document.getElementById("gender").value;
    var civilStatus = document.getElementById("civil_status").value;
    var religion = document.getElementById("religion").value || "N/A";
    var contactNumber = document.getElementById("contact_number").value;
    var activeEmailAddress = document.getElementById("active_email_address").value;
    var placeOfBirth = document.getElementById("place_of_birth").value;
    var dateOfBirth = document.getElementById("date_of_birth").value;
    var age = document.getElementById("age").value;
    var houseNumber = document.getElementById("house_number").value;
    var barangay = document.getElementById("barangay").value;
    var municipality = document.getElementById("municipality").value;
    var province = document.getElementById("province").value;
    //school
    var elementarySchool = document.getElementById("elementary_school").value;
    var elementaryYear = document.getElementById("elementary_year").value;
    var secondarySchool = document.getElementById("secondary_school").value;
    var secondaryYear = document.getElementById("secondary_year").value;
    var seniorHighSchool = document.getElementById("senior_high_school").value;
    var seniorHighYear = document.getElementById("senior_high_year").value;
    var courseEnrolled = document.getElementById("course_enrolled").value;
    var graduating = document.getElementById("graduating").value;
    var noOfUnits = document.getElementById("no_of_units").value;
    var yearLevel = document.getElementById("year_level").value;
    var currentSemester = document.getElementById("current_semester").value;
    var expectedYearOfGraduation = document.getElementById("expected_year_of_graduation").value;
    var schoolName = document.getElementById("school_name").value;
    var schoolAddress = document.getElementById("school_address").value;
    var schoolType = document.getElementById("school_type").value;
    var studentIdNo = document.getElementById("student_id_no").value;
    //family
    var guardianFirstName = document.getElementById("guardian_firstname").value;
    var guardianLastName = document.getElementById("guardian_lastname").value;
    var guardianRelationship = document.getElementById("guardian_relationship").value;
    var guardianOccupation = document.getElementById("guardian_occupation").value;
    var guardianMonthlyIncome = document.getElementById("guardian_monthly_income").value;
    var guardianAnnualIncome = document.getElementById("guardian_annual_income").value;

    // Extract file name from the full path for the file inputs
    var uploadVotersApplicant = document.getElementById("uploadVotersApplicant").value;
    var uploadPhotoJPG = document.getElementById("uploadPhotoJPG").value;

    uploadVotersApplicant = getFileName(uploadVotersApplicant);
    uploadPhotoJPG = getFileName(uploadPhotoJPG);
    var uploadGrade = getFileName(document.getElementById("uploadGrade").value);
    var uploadCOR = getFileName(document.getElementById("uploadCOR").value);
    var uploadVotersParent = getFileName(document.getElementById("uploadVotersParent").value);
    var uploadITR = getFileName(document.getElementById("uploadITR").value);
    var uploadResidency = getFileName(document.getElementById("uploadResidency").value);


    // Create a container element to display the review information
    var reviewContainer = document.getElementById("review-container");

   var tableHTML = '<table>';
   tableHTML += '<tr class="personal-info"><td>Last Name:</td><td>' + lastName + '</td></tr>';
   tableHTML += '<tr class="personal-info"><td>First Name:</td><td>' + firstName + '</td></tr>';
   tableHTML += '<tr class="personal-info"><td>Middle Name:</td><td>' + middleName + '</td></tr>';
   tableHTML += '<tr class="personal-info"><td>Suffix Name:</td><td>' + suffixName + '</td></tr>';
        tableHTML+= '<tr><td>Gender:</td><td>' + gender + '</td></tr>';
        tableHTML+= '<tr><td>Civil Status:</td><td>' + civilStatus + '</td></tr>';
        tableHTML+= '<tr><td>Religion:</td><td>' + religion + '</td></tr>';
        tableHTML+= '<tr><td>Contact Number:</td><td>' + contactNumber + '</td></tr>';
        tableHTML+= '<tr><td>Active Email Address:</td><td>' + activeEmailAddress + '</td></tr>';
        tableHTML+= '<tr><td>Place of Birth:</td><td>' + placeOfBirth + '</td></tr>';
        tableHTML+= '<tr><td>Date of Birth:</td><td>' + dateOfBirth + '</td></tr>';
        tableHTML+= '<tr><td>Age:</td><td>' + age + '</td></tr>';
        tableHTML+= '<tr><td>House No. / Blk / Lot No.:</td><td>' + houseNumber + '</td></tr>';
        tableHTML+= '<tr><td>Barangay:</td><td>' + barangay + '</td></tr>';
        tableHTML+= '<tr><td>Municipality:</td><td>' + municipality + '</td></tr>';
        tableHTML+= '<tr><td>Province:</td><td>' + province  + '</td></tr>';
        //school
        tableHTML+= '<tr><td>Elementary School:</td><td>' + elementarySchool + '</td></tr>';
        tableHTML+= '<tr><td>Year Graduated (Elementary):</td><td>' + elementaryYear + '</td></tr>';
        tableHTML+= '<tr><td>Junior High School:</td><td>' + secondarySchool + '</td></tr>';
        tableHTML+= '<tr><td>Year Graduated (Junior High):</td><td>' + secondaryYear + '</td></tr>';
        tableHTML+= '<tr><td>Senior High School:</td><td>' + seniorHighSchool + '</td></tr>';
        tableHTML+= '<tr><td>Year Graduated (Senior High):</td><td>' + seniorHighYear + '</td></tr>';
        tableHTML+= '<tr><td>Course Enrolled (Tertiary):</td><td>' + courseEnrolled + '</td></tr>';
        tableHTML+= '<tr><td>Graduating (Tertiary):</td><td>' + graduating + '</td></tr>';
        tableHTML+= '<tr><td>No. of Units (Tertiary):</td><td>' + noOfUnits + '</td></tr>';
        tableHTML+= '<tr><td>Current Year Level (Tertiary):</td><td>' + yearLevel + '</td></tr>';
        tableHTML+= '<tr><td>Current Semester (Tertiary):</td><td>' + currentSemester + '</td></tr>';
        tableHTML+= '<tr><td>Expected Year of Graduation (Tertiary):</td><td>' + expectedYearOfGraduation + '</td></tr>';
        tableHTML+= '<tr><td>School Name:</td><td>' + schoolName + '</td></tr>';
        tableHTML+= '<tr><td>School Address:</td><td>' + schoolAddress + '</td></tr>';
        tableHTML+= '<tr><td>School Type:</td><td>' + schoolType + '</td></tr>';
        tableHTML+= '<tr><td>Student ID No.:</td><td>' + studentIdNo + '</td></tr>';
        //family
        tableHTML+= '<tr><td>Guardian First Name:</td><td>' + guardianFirstName + '</td></tr>';
        tableHTML+= '<tr><td>Guardian Last Name:</td><td>' + guardianLastName + '</td></tr>';
        tableHTML+= '<tr><td>Relationship:</td><td>' + guardianRelationship + '</td></tr>';
        tableHTML+= '<tr><td>Occupation:</td><td>' + guardianOccupation + '</td></tr>';
        tableHTML+= '<tr><td>Monthly Income:</td><td>' + guardianMonthlyIncome + '</td></tr>';
        tableHTML+= '<tr><td>Annual Income:</td><td>' + guardianAnnualIncome + '</td></tr>';

        // Update the tableHTML with the extracted file names
    tableHTML += '<tr><td>Applicant\'s Voter Certificate:</td><td>' + uploadVotersApplicant + '</td></tr>';
    tableHTML += '<tr><td>Upload 2x2 Picture:</td><td>' + uploadPhotoJPG + '</td></tr>';
    tableHTML += '<tr><td>Upload Current Grade:</td><td>' + uploadGrade + '</td></tr>';
    tableHTML += '<tr><td>Upload Certificate of Registration:</td><td>' + uploadCOR + '</td></tr>';
    tableHTML += '<tr><td>Upload Guardian\'s Voter Certificate:</td><td>' + uploadVotersParent + '</td></tr>';
    tableHTML += '<tr><td>Upload Guardian\'s Income Tax Return:</td><td>' + uploadITR + '</td></tr>';
    tableHTML += '<tr><td>Upload Guardian\'s Voter Certificate:</td><td>' + uploadResidency + '</td></tr>';

    // Clear the review container before adding new content
    reviewContainer.innerHTML = "";

    tableHTML += '</table>';

    // Get the reviewContainer element
    var reviewContainer = document.getElementById("review-container");

    // Set the innerHTML of the reviewContainer to the generated table HTML
    reviewContainer.innerHTML = tableHTML;
}

displayUserInput();

// Helper function to get the file name from the full path
function getFileName(fullPath) {
  // Using a regular expression to extract the file name
  var match = fullPath.match(/[^\\/]*$/);
  return match ? match[0] : fullPath;
}
// Add an event listener to each input field
document.getElementById("last_name").addEventListener("input", displayUserInput);
document.getElementById("first_name").addEventListener("input", displayUserInput);
document.getElementById("middle_name").addEventListener("input", displayUserInput);
document.getElementById("suffix_name").addEventListener("input", displayUserInput);
document.getElementById("gender").addEventListener("change", displayUserInput);
document.getElementById("civil_status").addEventListener("change", displayUserInput);
document.getElementById("religion").addEventListener("input", displayUserInput);
document.getElementById("contact_number").addEventListener("input", displayUserInput);
document.getElementById("active_email_address").addEventListener("input", displayUserInput);
document.getElementById("place_of_birth").addEventListener("input", displayUserInput);
document.getElementById("date_of_birth").addEventListener("input", displayUserInput);
document.getElementById("age").addEventListener("input", displayUserInput);
document.getElementById("house_number").addEventListener("input", displayUserInput);
document.getElementById("barangay").addEventListener("input", displayUserInput);
document.getElementById("municipality").addEventListener("input", displayUserInput);
document.getElementById("province").addEventListener("input", displayUserInput);
//school
document.getElementById("elementary_school").addEventListener("input", displayUserInput);
document.getElementById("elementary_year").addEventListener("change", displayUserInput);
document.getElementById("secondary_school").addEventListener("input", displayUserInput);
document.getElementById("secondary_year").addEventListener("change", displayUserInput);
document.getElementById("senior_high_school").addEventListener("input", displayUserInput);
document.getElementById("senior_high_year").addEventListener("change", displayUserInput);
document.getElementById("course_enrolled").addEventListener("input", displayUserInput);
document.getElementById("graduating").addEventListener("change", displayUserInput);
document.getElementById("no_of_units").addEventListener("input", displayUserInput);
document.getElementById("year_level").addEventListener("change", displayUserInput);
document.getElementById("current_semester").addEventListener("change", displayUserInput);
document.getElementById("expected_year_of_graduation").addEventListener("change", displayUserInput);
document.getElementById("school_name").addEventListener("input", displayUserInput);
document.getElementById("school_address").addEventListener("input", displayUserInput);
document.getElementById("school_type").addEventListener("change", displayUserInput);
document.getElementById("student_id_no").addEventListener("input", displayUserInput);
//family
// Add event listeners for the input fields in the "Head of the Family" section
document.getElementById("guardian_firstname").addEventListener("input", displayUserInput);
document.getElementById("guardian_lastname").addEventListener("input", displayUserInput);
document.getElementById("guardian_relationship").addEventListener("input", displayUserInput);
document.getElementById("guardian_occupation").addEventListener("input", displayUserInput);
document.getElementById("guardian_monthly_income").addEventListener("input", displayUserInput);
document.getElementById("guardian_annual_income").addEventListener("input", displayUserInput);
// Add an event listener to each file input field
document.getElementById("uploadVotersApplicant").addEventListener("change", displayUserInput);
document.getElementById("uploadPhotoJPG").addEventListener("change", displayUserInput);
document.getElementById("uploadGrade").addEventListener("change", displayUserInput);
document.getElementById("uploadVotersParent").addEventListener("change", displayUserInput);
document.getElementById("uploadITR").addEventListener("change", displayUserInput);
document.getElementById("uploadCOR").addEventListener("change", displayUserInput);
document.getElementById("uploadResidency").addEventListener("change", displayUserInput);



document.addEventListener("DOMContentLoaded", function () {
    var confirmButton = document.getElementById('submitReview');
    var cooldownSeconds = 2;
    var countdownInterval;

    // Function to update the button text and handle cooldown
    function startCooldown() {
        // Disable the confirm button initially
        confirmButton.disabled = true;

        // Update the button text to show the countdown
        function updateCountdown() {
            confirmButton.innerHTML = `<span>Submit (${cooldownSeconds})</span>`;
        }

        // Enable the confirm button after 5 seconds
        setTimeout(function () {
            confirmButton.disabled = false;
            confirmButton.classList.remove("cooldown");
            confirmButton.innerHTML = `<span>Submit</span>`; // Reset the button text
            clearInterval(countdownInterval); // Clear the interval once the cooldown is over
        }, cooldownSeconds * 1000);

        // Add cooldown styling during the 5 seconds
        confirmButton.classList.add("cooldown");

        // Set up the countdown timer
        countdownInterval = setInterval(function () {
            cooldownSeconds--;
            if (cooldownSeconds >= 0) {
                updateCountdown();
            }
        }, 1000);
        // Initial update
        updateCountdown();
    }

    const stepthreeBTN = document.getElementById('nextButtonStep_three')
    stepthreeBTN.addEventListener('click', function () {
        startCooldown(); 
    });

    // Add an event listener for the "Previous" button
    const previousBTN = document.getElementById('previous-stepthree')
    previousBTN.addEventListener('click', function () {
        clearInterval(countdownInterval); // Clear the countdown interval when going back
        cooldownSeconds = 2; // Reset the cooldown timer
        confirmButton.classList.remove("cooldown");
        confirmButton.innerHTML = `<span>Submit</span>`; // Reset the button text
    });
});


const ceapConfirmpopup = document.getElementById('ceapConfirmPopUp');
function openceapconfirmpopup(){
  ceapConfirmpopup.style.display="block";
}
function closeceapconfirmpoup(){
  ceapConfirmpopup.style.display="none";
}

const CeapconfirmButtons = document.querySelectorAll('.confirm-button');
CeapconfirmButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const ceapcancelButton = this.parentElement.querySelector('.cancel-button');
        ceapcancelButton.classList.add('disabled');
    });
});

const ceapcancelButtons = document.querySelectorAll(".cancel-button");
ceapcancelButtons.forEach((ceapcancelButtons) => {
ceapcancelButtons.addEventListener("click", closeceapconfirmpoup);
});

function submitForm() {
  // Serialize the form data
  var formData = new FormData(document.getElementById('msform'));

  // Send an AJAX request to submit the form
  const xhr = new XMLHttpRequest();
  xhr.open('POST', './php/ceapregformdatabaseinsert.php', true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  // Handle the response from the server
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        // Handle the response from the server here
        if (response === 'success') {
          console.log('Request successful. Response: ' + xhr.status);
          openCeapConfirmationPopup();
        } else {
          openCeapConfirmationPopup();
          console.log('Request successful, but an error occurred. Response: ' + xhr.responseText);
          // You can handle specific error cases here
        }
      } else {
        // Handle errors here, providing meaningful error messages or actions
        console.log('Request failed with status ' + xhr.status);
        // You can add more specific error handling here
      }
    }
  };

  // Error handler for network-related issues
  xhr.onerror = function () {
    console.log('Request failed due to a network error.');
    // You can take appropriate actions for network errors here
  };

  xhr.send(formData);
}


function openCeapConfirmationPopup() {
  closeceapconfirmpoup();
const CeapconfirmationPopup = document.getElementById("CeapConfrimMsgPopUp");
CeapconfirmationPopup.style.display = "block";

const CeapokButton = document.getElementById("okConfirm");
CeapokButton.addEventListener("click", function () {
  CeapconfirmationPopup.style.display = "none";
  goBack();
});
// Call the goBack function after a 5-second delay
setTimeout(goBack, 20000);
}

function goBack() {
  window.location.href = "../home-page-two/home-page.php";
}

