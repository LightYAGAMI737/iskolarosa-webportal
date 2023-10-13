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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/temporary_account_nav.css">
  <link rel="stylesheet" href="../css/personal_account.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  <script src="../js/temporary_account_nav.js"></script>
  <title>iSKOLAROSA | Personal Account</title>
  <style>
    *{
        text-transform: uppercase;
        text-align: left;
    }
    fieldset {
        border: none;
        margin-top: 20px;
    }
     /* Add this CSS to remove the border from non-editable fields */
     .no-border {
        border: none;
        font-weight: bolder;
    }
    .out-applicant-info {
        margin-top: 70px;
    }
th {
    font-weight: normal;
    padding-right: 20px;


}
td {
    font-weight: bolder;
}
input[type='text'] {
    font-weight: bolder;
}
    </style>
</head>
<body>
<?php
include 'personal_account_nav.php'; 
?>

<div class="content-side">
    <div class="main">
        <div class="container">
            <div class="head">
            <?php
if (isset($_SESSION['control_number'])) {
    $control_number = $_SESSION['control_number'];

    // Retrieve data from the ceap_personal_account table based on control_number
$tempAccountSql = "SELECT * FROM ceap_personal_account 
WHERE control_number = ?";
$stmt = mysqli_prepare($conn, $tempAccountSql);
mysqli_stmt_bind_param($stmt, "s", $control_number);
mysqli_stmt_execute($stmt);
$tempAccountResult = mysqli_stmt_get_result($stmt);

// Fetch the applicant's information
if (mysqli_num_rows($tempAccountResult) > 0) {
// Information of applicant-name-control number
$applicantData = mysqli_fetch_assoc($tempAccountResult);
$last_name = $applicantData['last_name'];
$first_name = $applicantData['first_name'];
$control_number = $applicantData['control_number'];

            echo '<table class="personal_info">';
            echo '<tr>';
            echo '<td>' . strtoupper($last_name) . ', ' . strtoupper($first_name) . ' (' . strtoupper($control_number) . ')</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            echo '<div class="border_line">'; echo '</div>';

        }
        
    }
?>
<!-- Table 1: Personal Info -->
<div class="out-applicant-info">
<div class="applicant-info">
<h2 style="margin-top: -55px;">Personal Information</h2>
    <form id="update-form" method="post" action="reapply_update_info.php">
        <fieldset id="personal-info-fields">
            <table>
                <?php foreach ($applicantData as $field => $value) : ?>
                    <?php if (in_array($field, ['control_number', 'last_name', 'date_of_birth', 'age', 'gender', 'civil_status', 'place_of_birth', 'religion', 'contact_number', 'active_email_address', 'house_number', 'province', 'municipality', 'barangay'])) : ?>
                        <?php if (in_array($field, ['last_name', 'first_name', 'middle_name', 'suffix_name'])) : ?>
                            <?php if ($value !== 'N/A') : ?>
                                <tr>
                                    <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                                    <td>
                                        <?php
                                        // Concatenate non-'N/A' values
                                        $fullNameParts = [$value];
                                        foreach (['last_name', 'first_name', 'middle_name', 'suffix_name'] as $part) {
                                            if ($part !== $field) {
                                                if ($applicantData[$part] !== 'N/A') {
                                                    $fullNameParts[] = $applicantData[$part];
                                                }
                                            }
                                        }
                                        echo implode(' ', $fullNameParts);
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php else : ?>
                            <tr>
                                <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                                <td>
                                    <input type="text" name="<?php echo $field; ?>" value="<?php echo $value; ?>" <?php if (in_array($field, ['last_name', 'first_name', 'middle_name', 'suffix_name', 'control_number', 'gender', 'place_of_birth', 'province', 'municipality'])) { echo 'readonly'; } ?> class="<?php if (in_array($field, ['last_name', 'first_name', 'middle_name', 'suffix_name', 'control_number', 'gender', 'place_of_birth', 'province', 'municipality'])) { echo 'no-border'; } ?>">
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </fieldset>

        
<!-- Table 2: Family Background -->
<div class="applicant-info">
    <h2>Family Background</h2>
    <fieldset id="family-background-fields">
        <table>
            <?php foreach ($applicantData as $field => $value) : ?>
                <?php if (in_array($field, ['guardian_name', 'guardian_occupation', 'guardian_relationship', 'guardian_monthly_income', 'guardian_annual_income'])) : ?>
                    <tr>
                        <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                        <td>
                            <input type="text" name="<?php echo $field; ?>" value="<?php echo $value; ?>">
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        </fieldset>
</div>


<!-- Table 3: Educational Background -->
<div class="applicant-info">
    <h2>Educational Background</h2>
    <fieldset id="educational-background-fields" >
        <table>
            <?php foreach ($applicantData as $field => $value) : ?>
                <?php if (in_array($field, ['elementary_school', 'elementary_year', 'elementary_honors', 'secondary_school', 'secondary_year', 'secondary_honors', 'senior_high_school', 'senior_high_year', 'senior_high_honors', 'course_enrolled', 'no_of_units', 'year_level', 'current_semester', 'graduating', 'school_name', 'school_type', 'expected_year_of_graduation', 'school_address', 'student_id_no'])) : ?>
                    <tr>
                        <th><?php echo ucwords(str_replace('_', ' ', $field)) . ': '; ?></th>
                        <td>
                            <input type="text" name="<?php echo $field; ?>" value="<?php echo $value; ?>">
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        </fieldset>
</div>
  
    
<!-- table for displaying the uploaded files as images -->
<div class="uploaded-files">
    <table>
 
    </table>
    <input type="hidden" name="ceap_reg_form_id" value="<?php echo $ceapRegFormId; ?>">
<button type="submit" name="update_all_info" class="status-button verified">Submit</button>
</form>


            </div>
            </div>
        </div> 
    </div>
</div>

<footer class="text-center py-4" style="display: flex; justify-content: space-between; align-items: center; height: 70px; position:relative; top: 39px;">
  <p>&copy; Copyright 2023</p>
  <div>
    <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
    <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
  </div>
</footer>

</body>
</html>