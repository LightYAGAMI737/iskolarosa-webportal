<?php
// Your database connection details
include '../../../php/config_iskolarosa_db.php';

// Start a transaction to group the operations
$conn->begin_transaction();

try {
    // Retrieve data from ceap_reg_form and temporary_account tables
    $sql = "SELECT * FROM ceap_reg_form AS p
            INNER JOIN temporary_account AS t ON p.ceap_reg_form_id = t.ceap_reg_form_id
            WHERE t.status = 'Grantee'"; // Modify the condition as per your requirements

    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Initialize the array to store the data
    $personal_accounts = array();

    // Loop through each row and store data in the array
    while ($row = $result->fetch_assoc()) {
        $personal_account = array(
            $row['ceap_reg_form_id'],
            $row['control_number'],
            $row['last_name'],
            $row['first_name'],
            $row['middle_name'],
            $row['suffix_name'],
            $row['date_of_birth'],
            $row['gender'],
            $row['civil_status'],
            $row['place_of_birth'],
            $row['religion'],
            $row['contact_number'],
            $row['active_email_address'],
            $row['house_number'],
            $row['province'],
            $row['municipality'],
            $row['barangay'],
            $row['guardian_firstname'],
            $row['guardian_lastname'],
            $row['guardian_occupation'],
            $row['guardian_relationship'],
            $row['guardian_monthly_income'],
            $row['guardian_annual_income'],
            $row['elementary_school'],
            $row['elementary_year'],
            $row['secondary_school'],
            $row['secondary_year'],
            $row['senior_high_school'],
            $row['senior_high_year'],
            $row['course_enrolled'],
            $row['no_of_units'],
            $row['year_level'],
            $row['current_semester'],
            $row['graduating'],
            $row['school_name'],
            $row['school_type'],
            $row['expected_year_of_graduation'],
            $row['school_address'],
            $row['student_id_no'],
            $row['uploadVotersParent'],
            $row['uploadVotersApplicant'],
            $row['uploadITR'],
            $row['uploadResidency'],
            $row['uploadCOR'],
            $row['uploadGrade'],
            $row['uploadPhotoJPG'],
            $row['status'],
            $row['interview_date'],
            $row['interview_hour'],
            $row['interview_minute'],
            $row['interview_period'],
            $row['status_updated_at'],
            $row['username'],
            $row['ceap_password'],
            $row['hashed_password'],
            $row['first_time_login']
        );

          // Add the personal account data to the array
          $personal_accounts[] = "('" . implode("','", $personal_account) . "')";
        }

       // Perform the bulk insert into the personal_account table
        $insert_sql = "INSERT INTO ceap_personal_account (ceap_personal_account_id, control_number, last_name, first_name, middle_name, suffix_name, date_of_birth, gender, civil_status, place_of_birth, religion, contact_number, active_email_address, house_number, province, municipality, barangay, guardian_firstname, guardian_lastname, guardian_occupation, guardian_relationship, guardian_monthly_income, guardian_annual_income, elementary_school, elementary_year, secondary_school, secondary_year, senior_high_school, senior_high_year, course_enrolled, no_of_units, year_level, current_semester, graduating, school_name, school_type, expected_year_of_graduation, school_address, student_id_no, uploadVotersParent, uploadVotersApplicant, uploadITR, uploadResidency, uploadCOR, uploadGrade, uploadPhotoJPG, status, interview_date,interview_hour,interview_minute,interview_period, status_updated_at, username, ceap_password, hashed_password, first_time_login)
                        VALUES " . implode(",", $personal_accounts);

        if ($conn->query($insert_sql) === TRUE) {
            // After successful insertion, delete the corresponding data from ceap_reg_form table
            $delete_sql = "DELETE FROM ceap_reg_form WHERE ceap_reg_form_id IN (SELECT ceap_reg_form_id FROM temporary_account WHERE status = 'Grantee')";
            if ($conn->query($delete_sql) === TRUE) {
                // Commit the transaction if everything is successful
                $conn->commit();
                echo "Data transferred and deleted successfully.";
                // Redirect to the desired page after successful deletion
                header("Location: ceap_list_interview.php");
                exit();
            } else {
                throw new Exception("Error deleting data from ceap_reg_form table: " . $conn->error);
            }
        } else {
            throw new Exception("Error inserting data: " . $conn->error);
        }
    }
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}
?>