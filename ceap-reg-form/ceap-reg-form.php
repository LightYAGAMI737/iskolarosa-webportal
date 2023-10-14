<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | CEAP REG FORM</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel="stylesheet" type="text/css" href="css/popup.css">
      <link rel="stylesheet" href="css/style.css">
      <style>
      </style>
   </head>
   <body>
      <div class="header">
         <h3>COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)</h3>
         <h4>APPLICATION FORM</h4>
         <h5>To be filled-up by the applicant</h5>
      </div>
      <form id="msform" action="./php/ceapregformdatabaseinsert.php" enctype="multipart/form-data" method="post">
         
<!-- submit popup button -->
<div class="sub-containerPopup" id="sub-containerPopup">
  <div class="subpopup" id="subpopup"><br>
    <i class="ri-error-warning-line" style="font-size: 8em; color: #F54021;"></i>
    <strong><h2>Submit Now?</h2></strong>
    <center><p>You are about to submit your Application Form. Please review your information carefully. Are you sure you want to submit your application?</p></center>
    <div style="padding: 3px;">
      <button type="button" onclick="closesub()" id="cancelConfirm" style="margin-right: 15px; background-color: #C0C0C0;"><i class="ri-close-fill" style="font-size: 20px; margin-left: -76px;"></i><p style="margin-left: 16px; margin-top: -31px;">Cancel</p></button>
      <button type="submit" name="submit" id="submitConfirm">
    <i class="ri-check-line" style="font-size: 20px; margin-left: -85px;"></i>
    <p style="margin-left: 16px; margin-top: -31px;">Confirm</p>
</button>

</div>
  </div>
</div>
         <!-- MultiStep Form -->
         <div class="row">
         <div class="col-md-6 col-md-offset-3">
            <!-- progressbar -->
            <ul id="progressbar">
               <li class="active">Personal Details</li>
               <li>Educational Background</li>
               <li>Head of the Family</li>
               <li>Review</li>
            </ul>
            <!-- fieldsets -->
            <div class="fieldset-container">
               <fieldset id="step_one">
                  <h2 class="fs-title"><strong>Personal Details</strong></h2>
                  <h3 class="fs-subtitle"></h3>
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required" for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" minlength="2" maxlength="25" required />
                        <span class="error-message" id="last_name_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" minlength="2" maxlength="25" required>
                        <span class="error-message" id="first_name_error"></span>
                     </div>
                     <div class="input-container">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name"  minlength="2" maxlength="25"/>
                        <span class="error-message"></span>
                     </div>
                     <div class="input-container">
                        <label for="suffix_name">Suffix Name</label>
                        <input type="text" name="suffix_name" id="suffix_name" placeholder="Suffix Name" minlength="1" maxlength="8">
                        <div id="suffix_options" class="suffix-options"></div>
                     </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required" for="gender">Gender</label>
                        <select name="gender" id="gender" required>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required" for="civil_status">Civil Status</label>
                        <select name="civil_status" id="civil_status" required>
                           <option value="single">SINGLE</option>
                           <option value="married">MARRIED</option>
                           <option value="widowed">WIDOWED</option>
                           <option value="divorced">DIVORCED</option>
                           <option value="separated">SEPARATED</option>
                           <option value="lived_in">LIVED IN</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label>Religion</label>
                        <input type="text" name="religion" id="religion" placeholder="Religion"  minlength="2" maxlength="25">
                        <span class="error-message"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="contact_number">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number"  minlength="11" maxlength="11" required />
                        <span class="error-message" id="contact_number_error"></span>
                     </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required" for="active_email_address">Email address</label>
                        <input type="email" style="text-transform: none;" name="active_email_address" id="active_email_address" placeholder="Active Email Address" minlength="10" maxlength="100" required />
                        <span class="error-message" id="active_email_address_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="place_of_birth">Place of Birth</label>
                        <input type="text" name="place_of_birth" id="place_of_birth" placeholder="CITY, COUNTRY"  minlength="5" maxlength="100" required/>
                        <span class="error-message" id="place_of_birth_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="date_of_birth">Date of birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" min="1960-01-01" required onkeydown="event.preventDefault();" />
                     </div>
                     <div class="input-container noborder">
                        <label class="required" for="age">Age</label>
                        <input type="text" id="age" name="age"  style="border: none;" readonly/>
                     </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required" for="house_number">House No. / Blk / Lot No.</label>
                        <input type="text" name="house_number" id="house_number" placeholder="House No. / Blk / Lot No."  minlength="3" maxlength="100" required/>
                        <span class="error-message" id="house_number_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="barangay">Barangay</label>
                        <select name="barangay" id="barangay" required>
                           <option value="APLAYA">APLAYA</option>
                           <option value="BALIBAGO">BALIBAGO</option>
                           <option value="CAINGIN">CAINGIN</option>
                           <option value="DILA">DILA</option>
                           <option value="DITA">DITA</option>
                           <option value="DON JOSE">DON JOSE</option>
                           <option value="IBABA">IBABA</option>
                           <option value="KANLURAN">KANLURAN</option>
                           <option value="LABAS">LABAS</option>
                           <option value="MACABLING">MACABLING</option>
                           <option value="MALITLIT">MALITLIT</option>
                           <option value="MALUSAK">MALUSAK</option>
                           <option value="MARKET AREA">MARKET AREA</option>
                           <option value="POOC">POOC</option>
                           <option value="PULONG SANTA CRUZ">PULONG SANTA CRUZ</option>
                           <option value="SANTO DOMINGO">SANTO DOMINGO</option>
                           <option value="SINALHAN">SINALHAN</option>
                           <option value="TAGAPO">TAGAPO</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required" for="municipality">Municipality</label>
                        <input type="text" name="municipality" id="municipality" style="border: none;" placeholder="Municipality" value ="CITY OF SANTA ROSA" required readonly/>
                     </div>
                     <div class="input-container">
                        <label class="required" for="province">Province</label>
                        <input type="text" name="province" id="province" style="border: none;" placeholder="Province" value="LAGUNA" readonly required/>
                     </div>
                  </div>
                  <div class="text-row upload-docu">
                     <h3 class="fs-subtitle">Upload Document Requirements</h3>
                  </div>
                  <div class="text-row">
                     <div class="input-container upload">
                        <label class="required">Applicant's Voter Certificate (pdf, 1mb)</label>
                        <input type="file" name="uploadVotersApplicant" id="uploadVotersApplicant" accept=".pdf" required/>
                        <span class="error-message" id="uploadVotersApplicant_error"></span>
                     </div>
                     <div class="input-container upload">
                        <label class="required">Upload 2x2 Picture (jpg, 2mb)</label>
                        <input type="file" name="uploadPhotoJPG" id="uploadPhotoJPG" accept="image/jpeg" required/>
                        <span class="error-message" id="uploadPhotoJPG_error"></span>
                     </div>
                  </div>
                  <div class="tooltip-container">
                     <div class="tooltips" id="tooltips"></div>
                  </div>
                  <input type="button" name="next" id="nextButtonStep_One" class="next action-button next-button" value="Next" disabled />
               </fieldset>
               <!--STEP TWO-->
               <fieldset id="step_two">
                  <h2 class="fs-title"><strong>Educational Background</strong></h2>
                  <h3 class="fs-subtitle"></h3>
                  <h2 class="fs-title"><strong>Elementary</strong></h2>
                  <div class="text-row">
                  <div class="input-container school-graduated">
                     <label class="required">School Graduated</label>
                     <input type="text" name="elementary_school" id="elementary_school" placeholder="School Graduated" minlength="5" maxlength="100" required/>
                     <span class="error-message" id="elementary_school_error"></span>
                  </div>

                     <div class="input-container">
                        <label class="required">Year Graduated</label>
                        <select name="elementary_year" id="elementary_year" class="year-graduated" required>
                        <?php
                           $currentYear = date("Y");
                           for ($year = 2017; $year >= 2000; $year--) {
                             echo '<option  hidden>'.'</option>';
                               echo '<option  value="' . $year . '">' . $year . '</option>';
                               // Add a hidden option for separation if needed
                               if ($year > 2000) {
                                   echo '<option hidden></option>';
                               }
                           }
                           ?>
                        </select>
                     </div>
                  </div>
                  <!--new row-->
                  <h2 class="fs-title"><strong>Junior High</strong></h2>
                  <div class="text-row">
                     <div class="input-container school-graduated">
                        <label class="required">School Graduated</label>
                        <input type="text" name="secondary_school" id="secondary_school" placeholder="School Graduated"  minlength="5" maxlength="100" required/>
                        <span class="error-message" id="secondary_school_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Year Graduated</label>
                        <select name="secondary_year" id="secondary_year" class="year-graduated" required>
                        </select>
                     </div>
                  </div>
                  <!--new row-->
                  <h2 class="fs-title"><strong>Senior High</strong></h2>
                  <div class="text-row">
                     <div class="input-container school-graduated">
                        <label class="required">School Graduated</label>
                        <input type="text" name="senior_high_school" id="senior_high_school" placeholder="School Graduated"  minlength="5" maxlength="100" required/>
                        <span class="error-message" id="senior_high_school_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Year Graduated</label>
                        <select name="senior_high_year" id="senior_high_year" class="year-graduated" required>
                        </select>
                     </div>
                  </div>
                  <h2 class="fs-title"><strong>TERTIARY</strong></h2>
                  <!--new row-->
                  <div class="text-row">
                     <div class="input-container school-graduated ">
                        <label class="required">Course Enrolled</label>
                        <input type="text" name="course_enrolled" id="course_enrolled" placeholder="Course Enrolled"  minlength="5" maxlength="100" required/>
                        <span class="error-message" id="course_enrolled_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Graduating</label>
                        <select name="graduating" id="graduating" required>
                        <option value="no">NO</option>
                           <option value="yes">YES</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required">No. of Units</label>
                        <input type="number" name="no_of_units" id="no_of_units"  placeholder="No. of Units" required />
                        <span class="error-message" id="no_of_units_error"></span>
                     </div>
                  </div>
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required" >Current Year Level</label>
                        <select name="year_level" id="year_level" required>
                           <option value="one">1</option>
                           <option value="two">2</option>
                           <option value="three">3</option>
                           <option value="four">4</option>
                           <option value="five">5</option>
                           <option value="six">6</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required">Current Semester</label>
                        <select name="current_semester" id="current_semester" required>
                           <option value="one">1</option>
                           <option value="two">2</option>
                           <option value="three">3</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required">Expected Graduation</label>
                        <select name="expected_year_of_graduation" id="expected_year_of_graduation"  required>
                        </select>
                     </div>
                  </div>
                  <div class="text-row">
                     <div class="input-container school-graduated" id="school-name">
                        <label class="required">Full School Name (Do not abbreviate)</label>
                        <input type="text" name="school_name" id="school_name" placeholder="School Name" minlength="5" maxlength="150" required/>
                        <span class="error-message" id="school_name_error"></span>
                     </div>
                  </div>
                  <div class="text-row">
                     <div class="input-container school-graduated">
                        <label class="required">School Address</label>
                        <input type="text" name="school_address" id="school_address" placeholder="School Adress"  minlength="5" maxlength="150" required/>
                        <span class="error-message" id="school_address_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">School Type</label>
                        <select name="school_type" id="school_type" required>
                           <option value="public">PUBLIC</option>
                           <option value="private">PRIVATE</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required">Student ID No.</label>
                        <input type="text" name="student_id_no" id="student_id_no" placeholder="School ID No."  minlength="5" maxlength="50" required/>
                        <span class="error-message" id="student_id_no_error"></span>
                     </div>
                  </div>
                  <div class="text-row upload-docu">
                     <h3 class="fs-subtitle">Upload Document Requirements</h3>
                  </div>
                  <div class="text-row">
                     <div class="input-container upload">
                        <label class="required">Upload Current Grade (pdf, 1mb)</label>
                        <input type="file" name="uploadGrade" id="uploadGrade" accept=".pdf" required/>
                        <span class="error-message" id="uploadGrade_error"></span>
                     </div>
                     <div class="input-container upload">
                        <label class="required" style="margin-left: 10px; font-size: 12px;">Upload Certificate of Registration (pdf, 1mb)</label>
                        <input type="file" name="uploadCOR" id="uploadCOR" accept=".pdf" required/>
                        <span class="error-message" id="uploadCOR_error"></span>
                     </div>
                  </div>
                  <div class="tooltip-container">
                     <div class="tooltipstwo" id="tooltips_step_two"></div>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                  <input type="button" name="next" id="nextButtonStep_two" class="next action-button" value="Next" disabled/>
               </fieldset>
               <!--STEP THREE-->
               <fieldset id="step_three">
                  <h2 class="fs-title"><strong>Head of the Family</strong></h2>
                  <h3 class="fs-subtitle"></h3>
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required">Guardian First Name</label>
                        <input type="text" name="guardian_firstname" id="guardian_firstname" placeholder="LAST NAME, FIRST NAME"  minlength="2" maxlength="25" required/>
                        <span class="error-message" id="guardian_firstname_error"></span>
                        <span class="error-message" id="guardian_fullname_duplicate_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Guardian Last Name</label>
                        <input type="text" name="guardian_lastname" id="guardian_lastname" placeholder="LAST NAME, FIRST NAME"  minlength="2" maxlength="25" required/>
                        <span class="error-message" id="guardian_lastname_error"></span>
                        <span class="error-message" id="guardian_fullname_duplicate_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Relationship</label>
                        <input type="text" name="guardian_relationship" id="guardian_relationship" placeholder="Relationship"  minlength="5" maxlength="25" required/>
                        <span class="error-message" id="guardian_relationship_error"></span>
                     </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                     <div class="input-container">
                        <label class="required">Occupation</label>
                        <input type="text" name="guardian_occupation" id="guardian_occupation" placeholder="Occupation"  minlength="5" maxlength="50" required/>
                        <span class="error-message" id="guardian_occupation_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required">Monthly income</label>
                        <input type="text" id="guardian_monthly_income" name="guardian_monthly_income" placeholder="Monthly income" required pattern="\d+(\.\d{1,2})?" maxlength="8"/>
                     </div>
                     <div class="input-container">
                        <label class="required">Annual Income</label>
                        <input type="number" id="guardian_annual_income" style="border: none;" name="guardian_annual_income" placeholder="Annual Income"  minlength="5" maxlength="12" readonly required/>
                        <span class="error-message" id="guardian_annual_income_error"></span>
                     </div>
                  </div>
                  <div class="text-row upload-docu">
                     <h3 class="fs-subtitle">Upload Document Requirements</h3>
                  </div>
                  <div class="text-row">
                     <div class="input-container upload">
                        <label class="required" style="margin-left: 10px; font-size: 13px;">Guardian's Voter Certificate (pdf, 1mb)</label>
                        <input type="file" name="uploadVotersParent" id="uploadVotersParent" accept=".pdf" required/>
                        <span class="error-message" id="uploadVotersParent_error"></span>
                     </div>
                     <div class="input-container upload">
                        <label class="required">Upload Income Tax Return (pdf, 1mb)</label>
                        <input type="file" name="uploadITR" id="uploadITR" accept=".pdf" required/>
                        <span class="error-message" id="uploadITR_error"></span>
                     </div>
                     <div class="input-container upload">
                        <label class="required">Upload Residency (pdf, 1mb)</label>
                        <input type="file" name="uploadResidency"  id="uploadResidency" accept=".pdf" required/>
                        <span class="error-message" id="uploadResidency_error"></span>
                     </div>
                  </div>
                  <div class="tooltip-container">
                     <div class="tooltipstwo" id="tooltips_step_three"></div>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                  <input type="button" name="next" id="nextButtonStep_three" class="next action-button" value="Next" disabled/>
               </fieldset>
               <!-- Add a new fieldset for the review step -->
<fieldset id="step_four">
    <h2 class="fs-title"><strong>Review Your Information</strong></h2>
    <h3 class="fs-subtitle"></h3>
    <!-- Create a container to display the review information -->
    <div id="review-container">
    </div>

                  <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                  <input type="button" id="submitReview" class="submit action-button cooldown" onclick="opensub()" value="Submit">
               </fieldset>

      </form>
      </div>
      </div>
<!-- Include jQuery library -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
      <script src="./js/script.js"></script>
      <script src="./js/validatenext_stepone.js"></script>
      <script src="./js/validatenext_steptwo.js"></script>
      <script src="./js/validatenext_stepthree.js"></script>
      <script src="./js/validatenext_stepfour.js"></script>
      <script src="./js/check_duplicate.js"></script>
   </body>
</html>