<?php
    include '../../admin-side/php/config_iskolarosa_db.php';
    session_start();
    // Check if the session is not set (user is not logged in)
    if (!isset($_SESSION['control_number'])) {
        // You can either show a message or redirect to the login page
        echo 'You need to log in to access this page.';
        // OR
         header("Location: index.php"); // Redirect to the login page
        exit();
    }

    include '../php/fetchedApplicantInfo.php';

// Function to calculate age based on the birthdate<?php
// Function to format the birthdate
function formatBirthdate($birthdate)
{
    $formattedDate = new DateTime($birthdate);
    return $formattedDate->format('F j, Y'); // Format as "Month Day, Year"
}

// Function to calculate age based on the birthdate
function calculateAge($birthdate)
{
    $today = new DateTime();
    $birthDate = new DateTime($birthdate);
    $age = $today->diff($birthDate)->y;
    return $age;
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>iSKOLAROSA | Profile</title>
      <link rel="icon" href="../../admin-side/system-images/iskolarosa-logo.png" type="image/png">
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
      <link rel="stylesheet" href="../../admin-side/css/remixicon.css">
      <link rel="stylesheet" href="../css/tempAcc_nav.css">
      <link rel="stylesheet" href="../css/personalAcc_profile.css">
   </head>
   <body>
      <?php
         include 'personalAcc_nav.php';
         ?>
    <div class="heading">
        <h4>iSkolar Profile</h4>
    </div>
      <div class="content-out-profile">
        <div class="content-head">
            <div class="profile-applicantname">
            <span class="profilename-uppercase">
                    <?php
                    $fullName = $last_name . ', ' . $first_name;

                    // Check if middle_name and suffix_name are not 'n/a' before adding them to the full name
                    if (isset($middle_name) && $middle_name !== 'N/A') {
                        $fullName .= ' ' . $middle_name;
                    }

                    if (isset($suffix_name) && $suffix_name !== 'N/A') {
                        $fullName .= ' ' . $suffix_name;
                    }

                    echo $fullName;
                    echo ' (' . $control_number . ')';
                    ?>
                </span>
            </div>
            </div>
            <div class="content-in-profile">
                <fieldset id="first-page">
                    <div>
                        <p class="info-head">PERSONAL DETAILS</p>
                    </div>
                    <div class="content-applicant-info">
                        <div class="applicant-info-left">
                            <table>
                            <tr>
                                <td>Control Number:</td>
                                <td><strong><?php echo $control_number; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td><strong><?php echo $last_name . ' ' . $first_name . ' ' . $middle_name . ' ' . $suffix_name; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Date of Birth:</td>
                                <td><strong><?php echo formatBirthdate($date_of_birth); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Age:</td>
                                <td><strong><?php echo calculateAge($date_of_birth); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Place of Birth:</td>
                                <td><strong><?php echo $place_of_birth; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Contact Number:</td>
                                <td><input type="text" id="contact_number" placeholder="Contact Number" minlength="11" maxlength="11" name="contact_number" value="<?php echo $contact_number; ?>"></td>
                                <tr><td></td>
                                <td><span id="contact_number_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="text" name="email_address" id="active_email_address"  placeholder="Active email address" minlength="10" maxlength="100" value="<?php echo $active_email_address; ?>"></td>
                                <tr><td></td>
                                <td><span id="active_email_address_error"></span></td></tr>
                            </tr>
                            <tr>
                            <td>Civil Status:</td>
                            <td>
                                <select name="civil_status">
                                    <option value="Single" <?php if ($civil_status == 'Single') echo 'selected'; ?>>Single</option>
                                    <option value="Married" <?php if ($civil_status == 'Married') echo 'selected'; ?>>Married</option>
                                    <option value="Divorced" <?php if ($civil_status == 'Divorced') echo 'selected'; ?>>Divorced</option>
                                    <option value="Widowed" <?php if ($civil_status == 'Widowed') echo 'selected'; ?>>Widowed</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </td>
                            </tr>
                            <tr>
                                <td>Religion:</td>
                                <td><input type="text" name="religion" id="religion" placeholder="Religion" minlength="2" maxlength="25" value="<?php echo $religion; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="religion_error"></span></td></tr>
                            </tr>
                            </table>
                        </div>
                        <div class="applicant-info-right">
                            <table>
                            <tr>
                                <td>House No. / Blk / Lot No. :</td>
                                <td><input type="text" name="house_number" id="house_number" placeholder="House No. / Blk / Lot No." minlength="2" maxlength="100" value="<?php echo $house_number; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="house_number_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Barangay:</td>
                                <td>
                                    <select name="barangay">
                                        <option value="APLAYA" <?php if ($barangay == 'APLAYA') echo 'selected'; ?>>APLAYA</option>
                                        <option value="BALIBAGO" <?php if ($barangay == 'BALIBAGO') echo 'selected'; ?>>BALIBAGO</option>
                                        <!-- Add more barangay options as needed -->
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Municipality: </td>
                                <td><strong><?php echo $municipality; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Province: </td>
                                <td><strong><?php echo $province; ?></strong></td>
                            </tr>
                            </table>
                        </div>
                        </div>
                        <div class="NavBtn">
                        <!--add next button for page two-->
                        <button type="button" id="pageOneBtn" onclick="nextPage('page-two')">Next</button>
                        </div>
                </fieldset>
                <fieldset id="page-two" style="display: none;">
                    <div>
                        <p class="info-head">EDUCATIONAL BACKGROUND</p>
                    </div>
                    <div class="content-applicant-info">
                        <div class="applicant-info-left">
                            <table>
                            <tr>
                                <td>Elementary School & Year Graduated:  </td>
                                <td><strong><?php echo $elementary_school . ' (' . $elementary_year . ')'; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Junior High School & Year Graduated: </td>
                                <td><strong><?php echo $secondary_school . ' (' . $secondary_year . ')'; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Senior High School & Year Graduated: </td>
                                <td><strong><?php echo $senior_high_school . ' (' . $senior_high_year . ')'; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Tertiary/University School Name: </td>
                                <td><input type="text" name="school_name" id="school_name" placeholder="Tertiary/University School Name" minlength="2" maxlength="100" value="<?php echo $school_name; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="school_name_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>School Address: </td>
                                <td><input type="text" name="school_address" id="school_address" placeholder="School Address" minlength="2" maxlength="100" value="<?php echo $school_address; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="school_address_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>School Type: </td>
                                <td>
                                    <select name="school_type">
                                        <option value="Public" <?php if ($school_type == 'Public') echo 'selected'; ?>>Public</option>
                                        <option value="Private" <?php if ($school_type == 'Private') echo 'selected'; ?>>Private</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Course Enrolled: </td>
                                <td><input type="text" name="course_enrolled" id="course_enrolled" placeholder="Course Enrolled" minlength="2" maxlength="100" value="<?php echo $course_enrolled; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="course_enrolled_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Current Year Level:</td>
                                <td>
                                    <select name="year_level">
                                        <option value="one" <?php if ($year_level == 'one') echo 'selected'; ?>>1</option>
                                        <option value="two" <?php if ($year_level == 'two') echo 'selected'; ?>>2</option>
                                        <option value="three" <?php if ($year_level == 'three') echo 'selected'; ?>>3</option>
                                        <option value="four" <?php if ($year_level == 'four') echo 'selected'; ?>>4</option>
                                        <option value="five" <?php if ($year_level == 'five') echo 'selected'; ?>>5</option>
                                        <option value="six" <?php if ($year_level == 'six') echo 'selected'; ?>>6</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Current Semester: </td>
                                <td>
                                    <select name="current_semester">
                                        <option value="one" <?php if ($current_semester == 'one') echo 'selected'; ?>>1</option>
                                        <option value="two" <?php if ($current_semester == 'two') echo 'selected'; ?>>2</option>
                                        <option value="three" <?php if ($current_semester == 'three') echo 'selected'; ?>>3</option>
                                    </select>
                                </td>
                            </tr>
                  
                            </table>
                        </div>
                        <div class="applicant-info-right">
                            <table>
                            <tr>
                                <td>Graduating?: </td>
                                <td>
                                    <select name="graduating" id="graduating">
                                        <option value="yes" <?php if ($graduating == 'yes') echo 'selected'; ?>>Yes</option>
                                        <option value="no" <?php if ($graduating == 'no') echo 'selected'; ?>>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>No of Units:  </td>
                                <td><input type="text" name="no_of_units" id="no_of_units" placeholder="No of Units" minlength="2" maxlength="100" value="<?php echo $no_of_units; ?>"></td>
                                <tr><td></td>
                                <td><span id="no_of_units_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>expected_year_of_graduation?: </td>
                                <td>
                                    <select name="expected_year_of_graduation">
                                        <option value="2029" <?php if ($expected_year_of_graduation == '2029') echo 'selected'; ?>>2029</option>
                                        <option value="2025" <?php if ($expected_year_of_graduation == '2025') echo 'selected'; ?>>2025</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Student ID No.:  </td>
                                <td><input type="text" name="student_id_no" id="student_id_no" placeholder="Student ID No." minlength="2" maxlength="100" value="<?php echo $student_id_no; ?>" oninput="validateMinLength(this, 2)"></td>
                                <tr><td></td>
                                <td><span id="student_id_no_error"></span></td></tr>
                            </tr>
                            </table>
                        </div>
                    </div>
                    <div class="NavBtn">
                    <!-- Add Previous button for page one -->
                    <button type="button" class="prev" onclick="prevPage('first-page')">Previous</button>
                    <!-- Add Next button for page three -->
                    <button type="button" id="pageTwoBtn" onclick="nextPage('page-three')">Next</button>
                    </div>
                </fieldset>
                <fieldset id="page-three" style="display: none;">
                    <div>
                        <p class="info-head">FAMILY BACKGROUND</p>
                    </div>
                    <div class="content-applicant-info">
                        <div class="applicant-info-left">
                            <table>
                            <tr>
                                <td>Guradian Last Name: </td>
                                <td><input type="text" name="guardian_lastname" id="guardian_lastname" placeholder="Guardian Last Name" minlength="2" maxlength="100" value="<?php echo $guardian_lastname; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_lastname_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Guradian First Name: </td>
                                <td><input type="text" name="guardian_firstname" id="guardian_firstname" placeholder="Guardian First Name" minlength="2" maxlength="100" value="<?php echo $guardian_firstname; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_firstname_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Relationship: </td>
                                <td><input type="text" name="guardian_relationship" id="guardian_relationship" placeholder="Relationship" minlength="2" maxlength="100" value="<?php echo $guardian_relationship; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_relationship_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Occupation: </td>
                                <td><input type="text" name="guardian_occupation" id="guardian_occupation" placeholder="Occupation" minlength="2" maxlength="100" value="<?php echo $guardian_occupation; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_occupation_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Monthly Income: </td>
                                <td><input type="text" name="guardian_monthly_income" id="guardian_monthly_income" placeholder="Monthly Income" minlength="2" maxlength="100" value="<?php echo $guardian_monthly_income; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_monthly_income_error"></span></td></tr>
                            </tr>
                            <tr>
                                <td>Annual Income: </td>
                                <td><input type="text"  style="border: none;"  name="guardian_annual_income" id="guardian_annual_income" placeholder="Annual Income" minlength="2"  readonly maxlength="100" value="<?php echo $guardian_annual_income; ?>"></td>
                                <tr><td></td>
                                <td><span id="guardian_annual_income_error"></span></td></tr>
                            </tr>
                            </table>
                        </div>
                    </div>
                    <div class="NavBtn">
                    <!-- Add Previous button for page one -->
                    <button type="button" class="prev" onclick="prevPage('page-two')">Previous</button>
                    <!-- Add Next button for page three -->
                    <button type="button" id="pageThreeBtn" onclick="nextPage('page-four')">Next</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

      <script src="../js/bootstrap.min.js"></script>
      <script src="../js/validateProfile.js"></script>


<!-- Add this JavaScript code after your PHP code and HTML -->
<script>
 function nextPage(nextFieldsetId) {
    document.getElementById(currentFieldsetId).style.display = 'none';
    document.getElementById(nextFieldsetId).style.display = 'block';
    currentFieldsetId = nextFieldsetId;
}

function prevPage(prevFieldsetId) {
    document.getElementById(currentFieldsetId).style.display = 'none';
    document.getElementById(prevFieldsetId).style.display = 'block';
    currentFieldsetId = prevFieldsetId;
}

var currentFieldsetId = 'first-page'; // Initialize with the first fieldset ID

</script>




   </body>
</html>