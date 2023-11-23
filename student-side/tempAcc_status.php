<?php
    include '../admin-side/php/config_iskolarosa_db.php';
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
    
    // Retrieve data from the ceap_reg_form table based on control_number
    $tempAccountSql = "SELECT p.last_name, p.first_name, p.middle_name, p.suffix_name, p.control_number, t.status 
    FROM ceap_reg_form p
    JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id 
    WHERE p.control_number = ?";
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
        $middle_name = $applicantData['middle_name'];
        $suffix_name = $applicantData['suffix_name'];
        $control_number = $applicantData['control_number'];
        $status = $applicantData['status'];
    } else {
        // No applicant found
        header("Location: index.php"); // Redirect to the login page
        exit(); // Stop execution as there is no data to display
    }

    // Prepare the second query
    $tempAccountSqlTable = "
        SELECT DISTINCT p.last_name, p.first_name, p.control_number, t.status, t.reason, t.status_updated_at, t.interview_date, e.employee_username AS updated_by
        FROM ceap_reg_form p
        JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
        LEFT JOIN applicant_status_logs l ON p.ceap_reg_form_id = l.ceap_reg_form_id
        LEFT JOIN employee_logs e ON l.employee_logs_id = e.employee_logs_id
        WHERE p.control_number = '$control_number'
        ORDER BY t.status_updated_at DESC";

    $stmtTable = mysqli_prepare($conn, $tempAccountSqlTable);
    mysqli_stmt_execute($stmtTable);
    $tempAccountResultTable = mysqli_stmt_get_result($stmtTable);
}
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>iSKOLAROSA | Status</title>
      <link rel="icon" href="../admin-side/system-images/iskolarosa-logo.png" type="image/png">
      <link href="./css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
      <link rel="stylesheet" href="../admin-side/css/remixicon.css">
      <link rel="stylesheet" href="./css/tempAcc_nav.css">
      <link rel="stylesheet" href="./css/tempAcc_status.css">
   </head>
   <body>
      <?php
         include 'tempAcc_nav.php';
         ?>
      <div class="content-bg">
         <div class="content-header">
            <div class="applicant-name">
                <span class="name-uppercase">
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
         <div class="content-in">
            <div class="main-progress">
               <ul class="ul-progress">
                  <li class="progress-step">
                     <i class="icon uil"></i>
                     <div class="progress-bar-custom one">
                        <p>1</p>
                        <i class="uil ri-check-fill"></i>
                     </div>
                     <p class="text">IN PROGRESS</p>
                    
                  </li>
                  <li class="progress-step">
                     <i class="icon uil"></i>
                     <div class="progress-bar-custom two">
                        <p>2</p>
                        <i class="uil ri-check-fill"></i>
                     </div>
                        <p class="text"><?php
                                // Update the text content based on the fetched status
                            switch ($status) {
                                case 'Disqualified':
                                    echo 'DISQUALIFIED';
                                    break;
                                case 'Verified':
                                    echo 'VERIFIED';
                                    break;
                                default:
                                    echo 'VERIFIED';
                                    break;
                            }
                    ?></p>
                  </li>
                  <li class="progress-step">
                     <i class="icon uil"></i>
                     <div class="progress-bar-custom three">
                        <p>3</p>
                        <i class="uil ri-check-fill"></i>
                     </div>
                        <p class="text">TO INTERVIEW</p>
                  </li>
                  <li class="progress-step">
                     <i class="icon uil"></i>
                     <div class="progress-bar-custom four">
                        <p>4</p>
                        <i class="uil ri-check-fill"></i>
                     </div>
                     <p class="text"><?php
                                // Update the text content based on the fetched status
                            switch ($status) {
                                case 'Fail':
                                    echo 'FAIL';
                                    break;
                                case 'Grantee':
                                    echo 'GRANTEE';
                                    break;
                                default:
                                    echo '';
                            }
                    ?></p>
                  </li>
               </ul>
            </div>
         </div>
         <div class="table-status">
    <table>
        <thead>
            <tr>
                <th>Updated Date</th>
                <th>Status</th>
                <th>Description</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $interviewDisplayed = false; // Initialize the variable to track 'interview' status

                // Loop through the fetched data and populate the table
                while ($tempAccountRow = mysqli_fetch_assoc($tempAccountResultTable)) {
                    $status = $tempAccountRow['status'];
                    $interview_date = $tempAccountRow['interview_date'];
                    $interview_dateFormatted = date('F d, Y', strtotime($interview_date));
                    $date = $tempAccountRow['status_updated_at'];
                    $dateFormatted = date('F d, Y', strtotime($date));
                    $updatedBy = $tempAccountRow['updated_by']; // You need to fetch and populate this value
                    $reason = $tempAccountRow['reason'];

                    switch ($status) {
                        case 'In Progress':
                            $description = 'Your application is currently under review.';
                            break;
                        case 'Disqualified':
                            $description = 'Your application did not meet the requirements. Reason: ' . $reason;
                            break;
                        case 'Deleted':
                            $description = 'Your account has been deleted.';
                            break;
                        case 'Fail':
                            $description = 'Your application did not meet the requirements. Reason:' . $reason;
                            break;
                        case 'Verified':
                            $description = 'Your documents have been verified.';
                            break;
                        case 'interview':
                            if (!$interviewDisplayed) {
                                $description = 'You have been scheduled for an interview on ' . $interview_dateFormatted;
                                $interviewDisplayed = true; // Mark 'INTERVIEW' status as displayed
                            } else {
                                $description = ''; // Skip displaying 'INTERVIEW' status again
                            }
                            break;
                        case 'Grantee':
                            $description = 'Congratulations! You have been approved as a grantee.';
                            break;
                        default:
                            $description = ''; // Handle other cases as needed
                            break;
                    }
            
                    echo '<tr>';
                    echo '<td>' . $dateFormatted . '</td>';
                    echo '<td>' . strtoupper($status) . '</td>';
                    echo '<td>' . $description . '</td>'; // Use the variable here
                    echo '<td>' . $tempAccountRow['updated_by'] . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</div>


      </div>

      <script src="./js/bootstrap.min.js"></script>


<!-- Add this JavaScript code after your PHP code and HTML -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fetch the status and text from PHP
        var status = '<?php echo $status; ?>';
        var text = '';

        // Select all progress bars
        var progressBars = document.querySelectorAll('.progress-bar-custom');

        // Remove 'active' class from all progress bars
        progressBars.forEach(function (bar) {
            bar.classList.remove('active');
        });

        // Determine the text and visibility based on the status
        switch (status) {
            case 'In Progress':
                progressBars[0].classList.add('active');
                text = 'IN PROGRESS';
                break;
            case 'Disqualified':
                progressBars[0].classList.add('active');
                if (progressBars[1]) {
                    progressBars[1].classList.add('active');
                }
                text = 'DISQUALIFIED';
                break;
            case 'Verified':
                progressBars[0].classList.add('active');
                if (progressBars[1]) {
                    progressBars[1].classList.add('active');
                }
                text = 'QUALIFIED';
                break;
            case 'interview':
                progressBars[0].classList.add('active');
                if (progressBars[1]) {
                    progressBars[1].classList.add('active');
                }
                if (progressBars[2]) {
                    progressBars[2].classList.add('active');
                }
                break;
            case 'Fail':
                progressBars[0].classList.add('active');
                if (progressBars[1]) {
                    progressBars[1].classList.add('active');
                }
                if (progressBars[2]) {
                    progressBars[2].classList.add('active');
                }
                if (progressBars[3]) {
                    progressBars[3].classList.add('active');
                }
                text = 'FAILED';
                break;
            case 'Grantee':
                progressBars[0].classList.add('active');
                if (progressBars[1]) {
                    progressBars[1].classList.add('active');
                }
                if (progressBars[2]) {
                    progressBars[2].classList.add('active');
                }
                if (progressBars[3]) {
                    progressBars[3].classList.add('active');
                }
                text = 'GRANTEE';
                break;
            default:
                // Handle other cases or set a default behavior
                break;
        }

        // Update the text content and visibility of the relevant <p> element
        var activeProgressBar = document.querySelector('.progress-bar-custom.active .text');
        if (activeProgressBar) {
            activeProgressBar.textContent = text;
        }

        // Hide the interview text if the status is 'Verified' or 'Disqualified'
        if (status === 'Verified' || status === 'Disqualified') {
            var interviewText = document.querySelector('.progress-bar-custom.two .text');
            if (interviewText) {
                interviewText.style.display = 'none';
            }
        }
    });
</script>




   </body>
</html>