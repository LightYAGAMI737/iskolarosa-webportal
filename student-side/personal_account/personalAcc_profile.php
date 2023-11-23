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

    $status='';
                            
    if (isset($_SESSION['control_number'])) {
    $control_number = $_SESSION['control_number'];
    
    // Retrieve data from the ceap_personal_account table based on control_number
    $tempAccountSql = "SELECT *
    FROM ceap_personal_account p
    WHERE p.control_number = ?";
    $stmt = mysqli_prepare($conn, $tempAccountSql);
    mysqli_stmt_bind_param($stmt, "s", $control_number);
    mysqli_stmt_execute($stmt);
    $tempAccountResult = mysqli_stmt_get_result($stmt);

    
    // Fetch the applicant's information
    if (mysqli_num_rows($tempAccountResult) > 0) {
    // Information of applicant-name-control number
    $applicantData = mysqli_fetch_assoc($tempAccountResult);
    $control_number = $applicantData['control_number'];
    $status = $applicantData['status'];
    $last_name = $applicantData['last_name'];
    $first_name = $applicantData['first_name'];
    $middle_name = $applicantData['middle_name'];
    $suffix_name = $applicantData['suffix_name'];
    $date_of_birth = $applicantData['date_of_birth'];
    $place_of_birth = $applicantData['place_of_birth'];
    $contact_number = $applicantData['contact_number'];
    $active_email_address = $applicantData['active_email_address'];
    $civil_status = $applicantData['civil_status'];
    $religion = $applicantData['religion'];
    $house_number = $applicantData['house_number'];
    $barangay = $applicantData['barangay'];
    $municipality = $applicantData['municipality'];
    $province = $applicantData['province'];
    $elementary_school = $applicantData['elementary_school'];
    $secondary_school = $applicantData['secondary_school'];
    $senior_high_school = $applicantData['senior_high_school'];
    $school_address = $applicantData['school_address'];
    $school_type = $applicantData['school_type'];
    $school_name = $applicantData['school_name'];
    $course_enrolled = $applicantData['course_enrolled'];
    $year_level = $applicantData['year_level'];
    $current_semester = $applicantData['current_semester'];
    $no_of_units = $applicantData['no_of_units'];
    $school_name = $applicantData['school_name'];
    }
}
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
                                <td><input type="text" name="contact_number" value="<?php echo $contact_number; ?>"></td>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="text" name="email_address" value="<?php echo $active_email_address; ?>"></td>
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
                                <td><input type="text" name="religion" value="<?php echo $religion; ?>"></td>
                            </tr>
                            </table>
                        </div>
                        <div class="applicant-info-right">
                            <table>
                            <tr>
                                <td>House No. / Blk / Lot No. :</td>
                                <td><input type="text" name="house_number" value="<?php echo $house_number; ?>"></td>
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
                        <button type="button" onclick="nextPage('page-two')">Next</button>
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
                                <td>Elementary School Year Graduated:  </td>
                                <td><strong><?php echo $elementary_school; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Junior High School Year Graduated: </td>
                                <td><strong><?php echo $secondary_school; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Senior High School Year Graduated: </td>
                                <td><strong><?php echo $senior_high_school; ?></strong></td>
                            </tr>
                            <tr>
                                <td>School Address: </td>
                                <td><strong><?php echo $school_address; ?></strong></td>
                            </tr>
                            <tr>
                                <td>School Type: </td>
                                <td><strong><?php echo $school_type; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Course Enrolled: </td>
                                <td><strong><?php echo $course_enrolled; ?></strong></td>
                            </tr>
                            <tr>
                                <td>School Address: </td>
                                <td><strong><?php echo $school_address; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Current Year Level:</td>
                                <td><strong><?php echo $year_level; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Current Semester: </td>
                                <td><strong><?php echo $current_semester; ?></strong></td>
                            </tr>
                            <tr>
                                <td>No of Units:  </td>
                                <td><strong><?php echo $no_of_units; ?></strong></td>
                            </tr>
                            </table>
                        </div>
                        <div class="applicant-info-right">
                            </table>
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
                    <!-- Add Previous button for page one -->
                    <button type="button" class="prev" onclick="prevPage('first-page')">Previous</button>
                    <!-- Add Next button for page three -->
                    <button type="button" onclick="nextPage('page-three')">Next</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

      <script src="../js/bootstrap.min.js"></script>


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