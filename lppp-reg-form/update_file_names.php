<?php

   include '../admin-side/php/config_iskolarosa_db.php';


if (isset($_POST['ceap_reg_form_id'])) {
  $ceap_reg_form_id = $_POST['ceap_reg_form_id'];
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $uploadVotersApplicant = $last_name . ', ' . $first_name . '_ApplicantVoters.pdf';
  $uploadVotersParent = $last_name . ', ' . $first_name . '_VotersParent.pdf';
  $uploadITR = $last_name . ', ' . $first_name . '_ITR.pdf';
  $uploadResidency = $last_name . ', ' . $first_name . '_Residency.pdf';
  $uploadCOR = $last_name . ', ' . $first_name . '_COR.pdf';
  $uploadGrade = $last_name . ', ' . $first_name . '_Grade.pdf';
  $uploadPhotoJPG = $last_name . ', ' . $first_name . '_2x2_Picture.jpg';

  $updateQuery = "UPDATE ceap_reg_form SET 
                  uploadVotersApplicant = ?, 
                  uploadVotersParent = ?, 
                  uploadITR = ?, 
                  uploadResidency = ?, 
                  uploadCOR = ?, 
                  uploadGrade = ?, 
                  uploadPhotoJPG = ?
                  WHERE ceap_reg_form_id = ?";
  $stmtUpdate = mysqli_prepare($conn, $updateQuery);
  mysqli_stmt_bind_param($stmtUpdate, "sssssssi", $uploadVotersApplicant, $uploadVotersParent, $uploadITR, $uploadResidency, $uploadCOR, $uploadGrade, $uploadPhotoJPG, $ceap_reg_form_id);

  if (mysqli_stmt_execute($stmtUpdate)) {
    echo "File names updated successfully.";
  } else {
    echo "Error updating file names: " . mysqli_error($conn);
  }
}
?>
