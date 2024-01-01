<?php
include '../../admin-side/php/config_iskolarosa_db.php';

// Set the timezone to 'Asia/Manila'
date_default_timezone_set('Asia/Manila');

// Retrieve the latest configuration from the database
$sql = "SELECT id, start_date, start_time, end_date, end_time FROM lppp_configuration ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $startDate = $row['start_date'];
    $startTime = $row['start_time'];
    $endDate = $row['end_date'];
    $endTime = $row['end_time'];
    $configId = $row['id'];

    // Check if start_date and start_time have valid values
    if ($startDate !== '0000-00-00' && $startTime !== '00:00:00') {
        $startDateTime = $startDate . ' ' . $startTime;

        // Get the current date and time
        $currentDateTime = date('Y-m-d H:i:s');

        // Add debugging statements
        echo "Current DateTime: $currentDateTime\n";
        echo "End DateTime: $endDate $endTime\n";

        // Check if the current time is equal to or later than the specified end time
        if (new DateTime($currentDateTime) >= new DateTime($endDate . ' ' . $endTime)) {
            // If end time has occurred, insert a new record
            $toggleValue = 0;
            $startDate = '0000-00-00';
            $startTime = '00:00:00';
            $qualification = ''; // Replace with your default values
            $requirements = ''; // Replace with your default values

            // Insert a new record with the default values
            $insertSql = "INSERT INTO lppp_configuration (toggle_value, start_date, start_time, qualifications, requirements) VALUES ($toggleValue, '$startDate', '$startTime', '$qualification', '$requirements')";
            
            // Add debugging statement
            echo "Insert SQL: $insertSql\n";

            mysqli_query($conn, $insertSql);
        } else {
            // Update the database with the new toggle value
            $toggleValue = (new DateTime($currentDateTime) >= new DateTime($startDateTime)) ? 1 : 0;
            $updateSql = "UPDATE lppp_configuration SET toggle_value = $toggleValue WHERE id = $configId";

            // Add debugging statement
            echo "Update SQL: $updateSql\n";

            mysqli_query($conn, $updateSql);
        }
    }
} else {
    // Add debugging statement
    echo "No records found.\n";
}

mysqli_close($conn);
?>
