<?php
// Include configuration and functions
// Include configuration and functions
session_start();

include 'config_iskolarosa_db.php';
include 'functions.php';

$schoolCounts = []; // Initialize the variable

if (isset($_POST['tableChoice'])) {
    $tableName = $_POST['tableChoice'] === "ceap" ? "ceap_reg_form" : "lppp_reg_form";
    $columnName = $_POST['tableChoice'] === "ceap" ? "school_name" : "elementary_school";
    $query = "SELECT $columnName FROM $tableName";
    $result = mysqli_query($conn, $query); // Replace $conn with your database connection variable

    // Fetch and process the results
    while ($row = mysqli_fetch_assoc($result)) {
        $schoolName = strtolower($row[$columnName]);

        // Increment the count for the school name
        if (!isset($schoolCounts[$schoolName])) {
            $schoolCounts[$schoolName] = 1;
        } else {
            $schoolCounts[$schoolName]++;
        }
    }
} else {
    echo '<p>No data submitted.</p>'; // Add an error message if data isn't set
}

// Sort the school counts based on sorting choice
$sortingChoice = isset($_POST['sortingChoice']) ? $_POST['sortingChoice'] : 'asc';

if ($sortingChoice === 'desc') {
    arsort($schoolCounts);
} else {
    asort($schoolCounts);
}

?>

<div class="background">
    <h2 style="text-align: center">
    <?php
      if (mysqli_num_rows($result) === 0) {
        // Display the empty state image and message
        echo '<div class="empty-state">';
        echo '<img src="../empty-state-img/dashboard.png" alt="No records found" class="empty-state-image">';
        echo '<p>No Data. </p>';
        echo '</div>';
    } else {
        ?>
        <?php
        if ($_POST['tableChoice'] === 'ceap') {
            echo 'CEAP SCHOLAR MASTER LIST';
        } elseif ($_POST['tableChoice'] === 'lppp') {
            echo 'LPPP SCHOLAR MASTER LIST';
        }
        ?>
    </h2>
    <table class="applicant-info">
        <tr>
            <th>SCHOOL NAME</th>
            <th>TOTAL APPLICANT</th>
        </tr>

        <?php
       
// Display the filtered data in the HTML table
foreach ($schoolCounts as $schoolName => $count) {
    echo '<tr>';
    echo '<td>' . strtoupper($schoolName) . '</td>';
    echo '<td>' . $count . '</td>';
    echo '</tr>';
}
        ?>
    </table>
</div>
<?php } ?>