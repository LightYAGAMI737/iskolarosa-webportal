<?php 
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
$elementary_year = $applicantData['elementary_year'];
$secondary_school = $applicantData['secondary_school'];
$secondary_year = $applicantData['secondary_year'];
$senior_high_school = $applicantData['senior_high_school'];
$senior_high_year = $applicantData['senior_high_year'];
$school_address = $applicantData['school_address'];
$school_type = $applicantData['school_type'];
$school_name = $applicantData['school_name'];
$course_enrolled = $applicantData['course_enrolled'];
$year_level = $applicantData['year_level'];
$current_semester = $applicantData['current_semester'];
$no_of_units = $applicantData['no_of_units'];
$school_name = $applicantData['school_name'];
$graduating = $applicantData['graduating'];
$expected_year_of_graduation = $applicantData['expected_year_of_graduation'];
$student_id_no = $applicantData['student_id_no'];
$guardian_firstname = $applicantData['guardian_firstname'];
$guardian_lastname = $applicantData['guardian_lastname'];
$guardian_relationship = $applicantData['guardian_relationship'];
$guardian_occupation = $applicantData['guardian_occupation'];
$guardian_monthly_income = $applicantData['guardian_monthly_income'];
$guardian_annual_income = $applicantData['guardian_annual_income'];
}
}
?>