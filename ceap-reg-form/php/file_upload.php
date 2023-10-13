<?php 
       function sanitizeFileName($filename) {
        $filename = preg_replace("/[^\w\d.-]/", "", $filename);
        return $filename;
    }
    $uploadVotersApplicant = sanitizeFileName($_FILES['uploadVotersApplicant']['name']);
    $uploadVotersParent = sanitizeFileName($_FILES['uploadVotersParent']['name']);
    $uploadITR = sanitizeFileName($_FILES['uploadITR']['name']);
    $uploadResidency = sanitizeFileName($_FILES['uploadResidency']['name']);
    $uploadCOR = sanitizeFileName($_FILES['uploadCOR']['name']);
    $uploadGrade = sanitizeFileName($_FILES['uploadGrade']['name']);
    $uploadPhotoJPG = sanitizeFileName($_FILES['uploadPhotoJPG']['name']);

//file naming insert in pdf folder
    $uploadVotersApplicant_tmp = $_FILES['uploadVotersApplicant']['tmp_name'];
    $uploadVotersApplicant_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_VotersApplicant.pdf';
    
    $uploadVotersParent_tmp = $_FILES['uploadVotersParent']['tmp_name'];
    $uploadVotersParent_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_VotersParent.pdf';
    
    $uploadITR_tmp = $_FILES['uploadITR']['tmp_name'];
    $uploadITR_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_ITR.pdf';
    
    $uploadResidency_tmp = $_FILES['uploadResidency']['tmp_name'];
    $uploadResidency_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Residency.pdf';
    
    $uploadCOR_tmp = $_FILES['uploadCOR']['tmp_name'];
    $uploadCOR_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_COR.pdf';
    
    $uploadGrade_tmp = $_FILES['uploadGrade']['tmp_name'];
    $uploadGrade_path = '../pdfFiles/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_Grade.pdf';
    
    $uploadPhotoJPG_tmp = $_FILES['uploadPhotoJPG']['tmp_name'];
    $uploadPhotoJPG_path = '../applicant2x2/' . $_POST['last_name'] . '_' . $_POST['first_name'] . '_2x2_Picture.jpg';
    
           // Function to check if the uploaded file is a PDF
           function isPdfFile($filename)
           {
               $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
               return $fileExtension === 'pdf';
           }
       
           // Function to check if the uploaded file is a JPG
           function isJpgFile($filename)
           {
               $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
               return $fileExtension === 'jpg';
           }
       
           // Move the files only if they don't already exist and are PDF files
           if (!file_exists($uploadVotersApplicant_path) && isPdfFile($uploadVotersApplicant)) {
               move_uploaded_file($uploadVotersApplicant_tmp, $uploadVotersApplicant_path);
           }
       
           if (!file_exists($uploadVotersParent_path) && isPdfFile($uploadVotersParent)) {
               move_uploaded_file($uploadVotersParent_tmp, $uploadVotersParent_path);
           }
       
           if (!file_exists($uploadITR_path) && isPdfFile($uploadITR)) {
               move_uploaded_file($uploadITR_tmp, $uploadITR_path);
           }
       
           if (!file_exists($uploadResidency_path) && isPdfFile($uploadResidency)) {
               move_uploaded_file($uploadResidency_tmp, $uploadResidency_path);
           }
       
           if (!file_exists($uploadCOR_path) && isPdfFile($uploadCOR)) {
               move_uploaded_file($uploadCOR_tmp, $uploadCOR_path);
           }
       
           if (!file_exists($uploadGrade_path) && isPdfFile($uploadGrade)) {
               move_uploaded_file($uploadGrade_tmp, $uploadGrade_path);
           }
       
           // Move the files only if they don't already exist and are JPG files
           if (!file_exists($uploadPhotoJPG_path) && isJpgFile($uploadPhotoJPG)) {
               move_uploaded_file($uploadPhotoJPG_tmp, $uploadPhotoJPG_path);
           } 
?>