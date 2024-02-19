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
    
    var tableHTML = '<table style="border-collapse: separate; border-spacing: 15px 0px; width: 100%; text-transform: uppercase;">';
    tableHTML += '<tr><span class="HeadingInfo"><th colspan="4" style="text-align: center;  font-weight: bold; background: #ececec; padding: 8px; font-size: 18px;">Personal Information</th></span></tr>';
    tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';

    // Full Name
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class="spanReview">First Name:</span><br><span class="ReviewField">' + ((firstName !== "N/A") ? firstName : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Middle Name:</span><br><span class="ReviewField">' + ((middleName !== "N/A") ? middleName : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Last Name:</span><br><span class="ReviewField">' + ((lastName !== "N/A") ? lastName : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Suffix:</span><br><span class="ReviewField">' + ((suffixName !== "N/A") ? suffixName : '') + '</span></td>';
    tableHTML += '</tr>';

    // Gender, Civil Status, Religion
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Birth Sex:</span><br><span class="ReviewField">' + gender + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Civil Status:</span><br><span class="ReviewField">' + civilStatus + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Religion:</span><br><span class="ReviewField">' + ((religion !== "N/A") ? religion : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Contact Number:</span><br><span class="ReviewField">' + ((contactNumber !== "N/A") ? contactNumber : '') + '</span></td>';
    tableHTML += '</tr>';
    
    // Contact Number, Date of Birth, Email Address
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Email Address:</span><br><span class="ReviewField">' + ((activeEmailAddress !== "N/A") ? activeEmailAddress : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Date of Birth:</span><br><span class="ReviewField">' + dateOfBirth + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Age:</span><br><span class="ReviewField">' + age + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Place of Birth:</span><br><span class="ReviewField">' + placeOfBirth + '</span></td>';
    tableHTML += '</tr>';

    //address
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class="spanReview">House Number:</span><br><span class="ReviewField">' + ((houseNumber !== "N/A") ? houseNumber : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Barangay:</span><br><span class="ReviewField">' + ((barangay !== "N/A") ? barangay : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Municipality:</span><br><span class="ReviewField">' + ((municipality !== "N/A") ? municipality : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Province:</span><br><span class="ReviewField">' + ((province !== "N/A") ? province : '') + '</span></td>';
    tableHTML += '</tr>';

   // Spacer
tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';
    tableHTML += '<tr><th colspan="4" style=" text-align: center; font-weight: bold; background: #ececec; padding: 8px; font-size: 18px;">Educational Background</th></tr>';
tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';
//school
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Elementary:</span><br><span class="ReviewField">' + elementarySchool + ', (' + elementaryYear + ')' + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Junior Highschool:</span><br><span class="ReviewField">' + ((secondarySchool !== "N/A") ? secondarySchool + ', (' + secondaryYear + ')' : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Senior Highschool:</span><br><span class="ReviewField">' + ((seniorHighSchool !== "N/A") ? seniorHighSchool + ', (' + seniorHighYear + ')' : '') + '</span></td>';
    tableHTML += '<td class="equal-width"><span class="spanReview">Tertiary School Name:</span><br><span class="ReviewField">' + schoolName + '</span></td>';
   
    tableHTML += '<tr>';
    tableHTML+= '<td class="equal-width"><span class="spanReview">Course Enrolled (Tertiary):</span><br><span class="ReviewField">' + courseEnrolled + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class="spanReview">Year / Semester </span><br><span class="ReviewField">' + yearLevel + ' / ' + currentSemester + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">Student ID No.:</span><br><span class="ReviewField">' + studentIdNo + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">No. of Units (Tertiary):</span><br><span class="ReviewField">' + noOfUnits + '</span></td>';
   
    tableHTML += '</tr>';

    tableHTML += '<tr>';
    tableHTML+= '<td class="equal-width"><span class="spanReview">Graduating (Tertiary):</span><br><span class="ReviewField">' + graduating + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class="spanReview">Expected Year of Graduation (Tertiary):</span><br><span class="ReviewField">' + expectedYearOfGraduation + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">School Address:</span><br><span class="ReviewField">' + schoolAddress + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">School Type:</span><br><span class="ReviewField">' + schoolType + '</span></td>';
    tableHTML += '</tr>';
    
    tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';
    tableHTML += '<tr><th colspan="4" style="    text-align: center; font-weight: bold; background: #ececec; padding: 8px; font-size: 18px;">Family Background</th></tr>';
    tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';

      //family
    tableHTML+= '<td class="equal-width"><span class ="spanReview">Guardian Full Name:</span><br><span class="ReviewField">' +guardianLastName  + ', ' +  guardianFirstName + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">Relationship:</span><br><span class="ReviewField">' + guardianRelationship + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">Occupation:</span><br><span class="ReviewField">' + guardianOccupation + '</span></td>';
    tableHTML+= '<td class="equal-width"><span class ="spanReview">Annual Income:</span><br><span class="ReviewField">' + guardianAnnualIncome + '</span></td>';
    tableHTML += '</tr>';
    
    tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';
    tableHTML += '<tr><th colspan="4" style="    text-align: center; font-weight: bold; background: #ececec; padding: 8px; font-size: 18px;">Requirements Uploaded</th></tr>';
    tableHTML += '<tr><td colspan="4" class="HeadingInfo"><br></td></tr>';
        
        // Update the tableHTML with the extracted file names
    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Applicant\'s Voter Certificate:</span><br><span class="ReviewField">' + uploadVotersApplicant + '</span></td>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload 2x2 Picture:</span><br><span class="ReviewField">' + uploadPhotoJPG + '</span></td>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload Current Grade:</span><br><span class="ReviewField">' + uploadGrade + '</span></td>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload Certificate of Registration:</span><br><span class="ReviewField">' + uploadCOR + '</span></td>';
    tableHTML += '</tr>';

    tableHTML += '<tr>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload Guardian\'s Voter Certificate:</span><br><span class="ReviewField">' + uploadVotersParent + '</span></td>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload Guardian\'s Income Tax Return:</span><br><span class="ReviewField">' + uploadITR + '</span></td>';
    tableHTML += '<td class="equal-width"><span class ="spanReview">Upload Guardian\'s Voter Certificate:</span><br><span class="ReviewField">' + uploadResidency + '</span></td>';
    tableHTML += '</tr>';

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
    var cooldownSeconds = 20;
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
        cooldownSeconds = 20; // Reset the cooldown timer
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

