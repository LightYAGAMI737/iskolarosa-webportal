<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | LPPP REG FORM</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel="stylesheet" type="text/css" href="css/popup.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="../admin-side/css/status_popup.css">
      <style>
.suffix-options {
    display: none;
    position: absolute;
    border: 1px solid #ccc;
    width: 200px;
    max-height: 150px; /* Set the maximum height for scrolling */
    margin-top: -8px;
    overflow-y: auto;
    background-color: white;
}


.suffix-option {
    padding: 5px;
    cursor: pointer;
}

.suffix-option:hover {
    background-color: #f0f0f0;
}


         </style>

   </head>
   <body>
      <?php
         include './php/submitLPPPpopup.php';
         ?>
   <div class="header">
      <h3>LIBRENG PAGPAPAARAL SA PRIBADONG PAARALAN (LPPP)</h3>
      <h4>APPLICATION FORM</h4>
      <h5>To be filled-up by the Parents / Guardian</h5>
      </div>
   <form id="msform" enctype="multipart/form-data" action="lpppregformdatabaseinsert.php" method="post">
      <!-- MultiStep Form -->
      <div class="row">
      <div class="col-md-6 col-md-offset-3">
            <!-- progressbar -->
            <ul id="progressbar">
               <li class="active">Personal Details</li>
               <li>Educational Background</li>
               <li>Head of the Family</li>
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
                        <select name="gender" required>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select>
                     </div>
                     <div class="input-container">
                        <label class="required" for="civil_status">Civil Status</label>
                        <select name="civil_status" required>
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
                        <input type="text" name="religion" id="religion" placeholder="Religion" minlength="2" maxlength="25">
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
                        <input type="text" name="place_of_birth" id="place_of_birth" placeholder="Place of Birth" minlength="5" maxlength="100" required/>
                        <span class="error-message" id="place_of_birth_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="date_of_birth">Date of birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" min="1960-01-01" required onkeydown="event.preventDefault();" />
                     </div>
                     <div class="input-container">
                        <label class="required" for="age">Age</label>
                        <input type="text" id="age" name="age"  style="border: none;" required readonly/>
                     </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                  <div class="input-container">
                        <label class="required" for="house_number">House No. / Blk / Lot No.</label>
                        <input type="text" name="house_number" id="house_number" placeholder="House No. / Blk / Lot No." minlength="3" maxlength="100" required/>
                        <span class="error-message" id="house_number_error"></span>
                     </div>
                     <div class="input-container">
                        <label class="required" for="barangay">Barangay</label>
                        <select name="barangay" required>
                           <option value="APLAYA">APLAYA</option>
                           <option value="BALIBAGO">BALIBAGO</option>
                           <option value="CAINGIN">CAINGIN</option>
                           <option value="DITA">DITA</option>
                           <option value="DILA">DILA</option>
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
                        <input type="text" name="municipality" style="border: none;" placeholder="Municipality" value ="CITY OF SANTA ROSA" required readonly/>
                     </div>
                     <div class="input-container">
                        <label class="required" for="province">Province</label>
                        <input type="text" name="province" style="border: none;" placeholder="Province" value="LAGUNA" readonly required/>
                     </div>
                  </div>
                  <div class="text-row upload-file">
                     <h3 class="fs-subtitle">Upload Document Requirements</h3>
                  </div>
                  <div class="text-row">
                     <div class="input-container upload">
                        <label class="required">Upload 2x2 Picture (jpg, 2mb)</label>
                        <input type="file" name="uploadPhotoJPG" accept="image/jpeg" required/>
                        <span class="error-message" id="uploadPhotoJPG_error"></span>
                     </div>
                  </div>
                  <input type="button" name="next" id="nextButtonStep_One" class="next action-button next-button" value="Next" disabled/>
               </fieldset>
               <!--STEP TWO-->
               <fieldset id="step_two">
                  <h2 class="fs-title"><strong>Educational Background</strong></h2>
                  <h3 class="fs-subtitle"></h3>
                  <h2 class="fs-title"><strong>Elementary</strong></h2>
                  <div class="text-row">
                  <div class="input-container school-graduated elementary-school-width">
                     <label class="required">Full Name of School Graduated (Do not abbreviate)</label>
                     <input type="text" name="elementary_school" placeholder="School Graduated" minlength="5" maxlength="100" required/>
                     <span class="error-message" id="elementary_school_error"></span>
                  </div>
                     <div class="input-container">
                        <label class="required">Year Graduated</label>
                        <select name="elementary_year" class="year-graduated" required>
                        <?php
    $currentYear = date("Y");
    for ($year = $currentYear; $year >= 2010; $year--) {
        echo '<option value="' . $year . '">' . $year . '</option>';
        // Add a hidden option for separation if needed
        if ($year > 2000) {
            echo '<option hidden></option>';
        }
    }
    ?>
                        </select>
                     </div>
                  </div>
                  <div class="text-row">
                     <div class="input-container school-graduated school-address-width">
                        <label class="required">School Address</label>
                        <input type="text" name="school_address" id="school_address" placeholder="School Adress" minlength="5" maxlength="150" required/>
                        <span class="error-message" id="school_address_error"></span>
                     </div>
                     </div>
                  <div class="text-row upload-file">
                     <h3 class="fs-subtitle">Upload Document Requirements</h3>
                  </div>
                  <div class="text-row">
                     <div class="input-container upload">
                        <label class="required">Upload Current Grade (pdf, 1mb)</label>
                        <input type="file" name="uploadGrade" accept=".pdf" required/>
                        <span class="error-message" id="uploadGrade_error"></span>

                     </div>
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
                     <input type="text" name="guardian_firstname" id="guardian_firstname" placeholder="LAST NAME, FIRST NAME" minlength="2" maxlength="25" required/>
                     <span class="error-message" id="guardian_firstname_error"></span>
                  </div>
                  <div class="input-container">
                     <label class="required">Guardian Last Name</label>
                     <input type="text" name="guardian_lastname" id="guardian_lastname" placeholder="LAST NAME, FIRST NAME" minlength="2" maxlength="25" required/>
                     <span class="error-message" id="guardian_lastname_error"></span>
                  </div>
                  <div class="input-container">
                     <label class="required">Relationship</label>
                     <input type="text" name="guardian_relationship" id="guardian_relationship" placeholder="Relationship" minlength="5" maxlength="25" required/>
                     <span class="error-message" id="guardian_relationship_error"></span>
                  </div>
                  </div>
                  <!--new row-->
                  <div class="text-row">
                  <div class="input-container">
                     <label class="required">Occupation</label>
                     <input type="text" name="guardian_occupation" id="guardian_occupation" placeholder="Occupation" minlength="5" maxlength="50" required/>
                     <span class="error-message" id="guardian_occupation_error"></span>
                  </div>
                     <div class="input-container">
                        <label class="required">Monthly income</label>
                        <input type="text" id="guardian_monthly_income" name="guardian_monthly_income" placeholder="Monthly income" required pattern="\d+(\.\d{1,2})?" maxlength="8"/>
                     </div>
                     <div class="input-container">
                        <label class="required">Annual Income</label>
                        <input type="number" id="guardian_annual_income" style="border: none;" name="guardian_annual_income" placeholder="Annual Income" minlength="5" maxlength="12" readonly required/>
                        <span class="error-message" id="guardian_annual_income_error"></span>
                     </div>
                  </div>
                  <div class="text-row upload-file">
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
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                  <input type="button" id="popupsubmit" class="submit action-button"  onclick="openLPPPpopup()" value="Submit" disabled>
               </fieldset>
         </form>
         </div>
      </div>
      <!-- /.MultiStep Form -->
      <!-- partial -->
      <!-- partial -->
      <script>
    let subpopup = document.getElementById("sub-containerPopup");

    function opensub(){
        subpopup.classList.add("open-subpopup")
    }
    function closesub(){
        subpopup.classList.remove("open-subpopup")
    }
    let subconfirmpopup = document.getElementById("subconfirmcontainerpopup");
    // let containerpopup = document.getElementById("containerpopup");

    function opensubConfirmpopup(){
        subconfirmpopup.classList.add("open-subconfirmpopup")
        subpopup.classList.remove("open-subpopup")
    }
    function closesubconfirmPopup(){
        subconfirmpopup.classList.remove("open-subconfirmpopup")
    }
    // window.setTimeout(function(){
    //     $(".session_alerts").fadeTo(100,0).slideUp(100,function(){
    //         $(this).remove();
    //     });
    // }, 7000);
    </script>
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
      <script src="./js/script.js"></script>
      <script src="./js/validatenext_stepone.js"></script>
      <script src="./js/validatenext_steptwo.js"></script>
      <script src="./js/validatenext_stepthree.js"></script>
      <script src="./js/lpppPopup.js"></script>
   </body>
</html>