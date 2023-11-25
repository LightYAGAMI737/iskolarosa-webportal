
// Function to display user input inside the fieldset
function displayUserInput() {
    //personal
    var lastName = last_name;
    var firstName = first_name;
    var middleName = middle_name;
    var suffixName = suffix_name;
    //var gender = document.getElementById("gender").value;
    var civilStatus = document.getElementById("civil_status").value;
    var religion = document.getElementById("religion").value || "N/A";
    var contactNumber = document.getElementById("contact_number").value;
    var activeEmailAddress = document.getElementById("active_email_address").value;
    var placeOfBirth = place_of_birth;
    var dateOfBirth = date_of_birth;
    var age = age_applicant;
    var houseNumber = document.getElementById("house_number").value;
    var barangay = document.getElementById("barangay").value;
    var municipality = municipality_applicant;
    var province = province_applicant   ;
   // var uploadVotersApplicant = document.getElementById("uploadVotersApplicant").value;
   // var uploadPhotoJPG = document.getElementById("uploadPhotoJPG").value;
    //school
    var elementarySchool = elementary_school;
    var elementaryYear = elementary_year;
    var secondarySchool = secondary_school;
    var secondaryYear = secondary_year;
    var seniorHighSchool = senior_high_school;
    var seniorHighYear = senior_high_year;
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
   // var uploadGrade = document.getElementById("uploadGrade").value;
   // var uploadCOR = document.getElementById("uploadCOR").value;
    //family
    var guardianFirstName = document.getElementById("guardian_firstname").value;
    var guardianLastName = document.getElementById("guardian_lastname").value;
    var guardianRelationship = document.getElementById("guardian_relationship").value;
    var guardianOccupation = document.getElementById("guardian_occupation").value;
    var guardianMonthlyIncome = document.getElementById("guardian_monthly_income").value;
    var guardianAnnualIncome = document.getElementById("guardian_annual_income").value;

    // Create a container element to display the review information
    var reviewContainer = document.getElementById("review-container");

   var tableHTML = '<table>';
   tableHTML += '<tr><td>Last Name:</td><td>' + lastName + '</td></tr>';
   tableHTML += '<tr><td>First Name:</td><td>' + firstName + '</td></tr>';
   tableHTML += '<tr><td>Middle Name:</td><td>' + middleName + '</td></tr>';
   tableHTML += '<tr><td>Suffix Name:</td><td>' + suffixName + '</td></tr>';
      //  tableHTML+= '<tr><td>Gender:</td><td>' + gender + '</td></tr>';
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
       //tableHTML+= '<tr><td>Applicant\'s Voter Certificate:</td><td>' + uploadVotersApplicant + '</td></tr>';
        //tableHTML+= '<tr><td>Upload 2x2 Picture:</td><td>' + uploadPhotoJPG + '</td></tr>';
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
       // tableHTML+= '<tr><td>Upload Current Grade:</td><td>' + uploadGrade + '</td></tr>';
      //  tableHTML+= '<tr><td>Upload Certificate of Registration:</td><td>' + uploadCOR + '</td></tr>';
        //family
        tableHTML+= '<tr><td>Guardian First Name:</td><td>' + guardianFirstName + '</td></tr>';
        tableHTML+= '<tr><td>Guardian Last Name:</td><td>' + guardianLastName + '</td></tr>';
        tableHTML+= '<tr><td>Relationship:</td><td>' + guardianRelationship + '</td></tr>';
        tableHTML+= '<tr><td>Occupation:</td><td>' + guardianOccupation + '</td></tr>';
        tableHTML+= '<tr><td>Monthly Income:</td><td>' + guardianMonthlyIncome + '</td></tr>';
        tableHTML+= '<tr><td>Annual Income:</td><td>' + guardianAnnualIncome + '</td></tr>';
    // Clear the review container before adding new content
    reviewContainer.innerHTML = "";

    tableHTML += '</table>';

    // Get the reviewContainer element
    var reviewContainer = document.getElementById("review-container");

    // Set the innerHTML of the reviewContainer to the generated table HTML
    reviewContainer.innerHTML = tableHTML;
}



// Add an event listener to each input field
//document.getElementById("gender").addEventListener("change", displayUserInput);
document.getElementById("civil_status").addEventListener("change", displayUserInput);
document.getElementById("religion").addEventListener("input", displayUserInput);
document.getElementById("contact_number").addEventListener("input", displayUserInput);
document.getElementById("active_email_address").addEventListener("input", displayUserInput);
document.getElementById("house_number").addEventListener("input", displayUserInput);
document.getElementById("barangay").addEventListener("input", displayUserInput);
//document.getElementById("uploadVotersApplicant").addEventListener("change", displayUserInput);
//document.getElementById("uploadPhotoJPG").addEventListener("change", displayUserInput);
//school
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
//document.getElementById("uploadGrade").addEventListener("change", displayUserInput);
//document.getElementById("uploadCOR").addEventListener("change", displayUserInput);
//family
// Add event listeners for the input fields in the "Head of the Family" section
document.getElementById("guardian_firstname").addEventListener("input", displayUserInput);
document.getElementById("guardian_lastname").addEventListener("input", displayUserInput);
document.getElementById("guardian_relationship").addEventListener("input", displayUserInput);
document.getElementById("guardian_occupation").addEventListener("input", displayUserInput);
document.getElementById("guardian_monthly_income").addEventListener("input", displayUserInput);
document.getElementById("guardian_annual_income").addEventListener("input", displayUserInput);

displayUserInput();



// Function to enable the button after a specified cooldown time
function enableAfterCooldown(button, cooldownTime) {
    button.classList.add("disabled");
    button.setAttribute("disabled", "disabled");
    console.log("Button is now disabled");

    setTimeout(function() {
        button.classList.remove("disabled");
        button.removeAttribute("disabled");
        console.log("Button is now enabled");
    }, cooldownTime);
}

// Attach the enableAfterCooldown function to the "Next" button's click event
document.getElementById("pageTwoBtn").addEventListener("click", function() {
    enableAfterCooldown(document.getElementById("SubmitUpdatedInfoCEAP"), 20000); // 20000 milliseconds = 20 seconds
});


document.addEventListener("DOMContentLoaded", function () {
  var confirmButton = document.getElementById('SubmitUpdatedInfoCEAP');
  var cooldownSeconds = 20;
  var countdownInterval;

  // Function to update the button text and handle cooldown
  function startCooldown() {
      // Clear the previous interval if it exists
      clearInterval(countdownInterval);

      // Disable the confirm button initially
      confirmButton.disabled = true;

      // Update the button text to show the countdown
      function updateCountdown() {
          if (cooldownSeconds > 0) {
              confirmButton.innerHTML = `<span>Submit (${cooldownSeconds})</span>`;
          } else {
              clearInterval(countdownInterval);
              confirmButton.disabled = false;
              confirmButton.classList.remove("cooldown");
              confirmButton.innerHTML = `<span>Submit</span>`; // Reset the button text
          }
      }

      // Enable the confirm button after 20 seconds
      setTimeout(function () {
          confirmButton.disabled = false;
          confirmButton.classList.remove("cooldown");
          confirmButton.innerHTML = `<span>Submit</span>`; // Reset the button text
      }, cooldownSeconds * 1000);

      // Add cooldown styling during the 20 seconds
      confirmButton.classList.add("cooldown");

      // Set up the countdown timer
      countdownInterval = setInterval(function () {
          cooldownSeconds--;
          updateCountdown();
      }, 1000);

      // Initial update
      updateCountdown();
  }

  const pageThreeBtnNext = document.getElementById('pageThreeBtn');

  // Event listener for the applyNowButtonCEAP click event
  pageThreeBtnNext.addEventListener('click', function () {
      startCooldown(); // Start the cooldown after clicking the apply now button
  });
});

const ceapUpdateInfopopup = document.getElementById('ceapUpdateInfoPopUp');
function openceapUpdateInfopopup(){
  ceapUpdateInfopopup.style.display="block";
}
function closeceapUpdatedInfopoup(){
  ceapUpdateInfopopup.style.display="none";
}

const CeapUpdateInfoButtons = document.querySelectorAll('.confirm-button');
CeapUpdateInfoButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        this.classList.add('disabled');
        const ceapcancelButton = this.parentElement.querySelector('.cancel-button');
        ceapcancelButton.classList.add('disabled');
    });
});

const ceapcancelUpdateInfoButtons = document.querySelectorAll(".cancel-button");
ceapcancelUpdateInfoButtons.forEach((ceapcancelUpdateInfoButtons) => {
ceapcancelUpdateInfoButtons.addEventListener("click", closeceapUpdatedInfopoup);
});

function submitUpdatedInfoForm() {
  // Serialize the form data
  var formData = new FormData(document.getElementById('ApplicantUpdateInfo'));

  // Send an AJAX request to submit the form
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '../php/personalAcc_updateInfo.php', true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

  // Handle the response from the server
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        // Handle the response from the server here
        if (response === 'success') {
          console.log('Request successful. Response: ' + xhr.status);
          openCeapUpdatedInfoPopup();
        } else {
          console.log('Request successful, but an error occurred. Response: ' + xhr.responseText);
          openCeapUpdatedInfoPopup();
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


function openCeapUpdatedInfoPopup() {
  closeceapUpdatedInfopoup();
const CeapconfirmationPopup = document.getElementById("CeapUpdatedInfoMsgPopUp");
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
  window.location.href = "personalAcc_status.php";
}
