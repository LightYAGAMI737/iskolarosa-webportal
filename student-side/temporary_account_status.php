<?php
    include './php/config_iskolarosa_db.php';
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
        <link rel="stylesheet" href="./css/temporary_account_nav.css">
        <link rel="stylesheet" href="./css/temporary_account.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
        <script src="./js/temporary_account_nav.js"></script>
        <title>iSKOLAROSA | Temporary Account</title>
    </head>
    <body>
        <?php
            include 'temporary_account_nav.php'; 
            ?>
        <div class="content-side">
            <div class="main">
                <div class="container">
                    <div class="head">
                        <?php
                            $status='';
                            
                            if (isset($_SESSION['control_number'])) {
                            $control_number = $_SESSION['control_number'];
                            
                            // Retrieve data from the ceap_reg_form table based on control_number
                            $tempAccountSql = "SELECT p.last_name, p.first_name, p.control_number, t.status 
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
                            $control_number = $applicantData['control_number'];
                            $status = $applicantData['status'];
                            
                                    echo '<table class="personal_info">';
                                    echo '<tr>';
                                    echo '<td>' . strtoupper($last_name) . ', ' . strtoupper($first_name) . ' (' . strtoupper($control_number) . ')</td>';
                                    echo '</tr>';
                                    echo '</table>';
                                    echo '</div>';
                                    echo '<div class="border_line">'; echo '</div>';
                            
                                    if ($status == 'Deleted') {
                                        echo "<div style='padding: 20px; margin: 20px; text-align: center;'> Your account has been deleted. If this is a mistake, you may visit the Scholarship office. </div>";
                                    } elseif ($status != '') { 
                            // status_bar start
                            echo '<div class="status_bar">';
                            echo '
                            <ul class="ul">
                            <li>
                                <i class="icon"></i>
                                <div class="progress one';
                                echo ' active shaded';
                            // Add class "active" if the user's status is "IN PROGRESS"
                            if ($status == 'In Progress') {
                            echo ' active shaded';
                            }
                            
                            echo '">
                                <p>1</p>
                                <i class="uil uil-check"></i>
                                </div>
                                <p>In Progress</p>
                            </li>
                            <li>
                                <i class="icon"></i>
                                <div class="progress two';
                                // Add class "active" if the user's status is one of the specified values
                                if ($status == 'Verified' || $status == 'Disqualified' || $status == 'interview' || $status == 'Grantee'  || $status == 'Fail') {
                                    echo ' active shaded';
                                }
                            ?>">
                        <p>2</p>
                        <i class="uil uil-check"></i>
                    </div>
                    <?php if ($status == 'Verified' || $status == 'interview' || $status == 'Grantee' || $status == 'Fail') : ?>
                    <p>Verified</p>
                    <?php elseif ($status == 'Disqualified') : ?>
                    <p>Disqualified</p>
                    <?php endif; ?>
                    </li>
                    <?php 
                        echo '
                        <li>
                            
                        <i class="icon"></i>
                        <div class="progress three'; 
                        // Add class "active" if the user's status is one of the specified values
                        if ($status == 'interview' || $status == 'Grantee' || $status == 'Fail') {
                            echo ' active shaded';
                        }
                        ?>">
                    <p>3</p>
                    <i class="uil uil-check"></i>
                </div>
                <?php if ($status == 'interview' || $status == 'Grantee'|| $status == 'Fail') : ?>
                <p>For Interview</p>
                <?php endif; ?>
                </li>
                <?php echo '
                    <li>
                        <i class="icon"></i>
                        <div class="progress four';
                    // Add class "active" if the user's status is one of the specified values
                    if ($status == 'Grantee' || $status == 'Fail') {
                        echo ' active shaded';
                    }
                    ?>">
                <p>4</p>
                <i class="uil uil-check"></i>
            </div>
            <?php if ($status == 'Grantee') : ?>
            <p>Grantee</p>
            <?php elseif ($status == 'Fail' ) : ?>
            <p>Fail</p>
            <?php endif; ?>
            </li>
            <?php echo'
                </ul>';
                echo '</div>';
                }
                }else {
                    echo "Congratulations! You have been approved as a grantee. Please try logging in again.";
                }
                }
                //status bar end
                
                
                if (isset($_SESSION['control_number'])) {            
                  $control_number = $_SESSION['control_number'];
                  // Retrieve data from the ceap_reg_form table based on control_number
                  // Retrieve all status updates for the applicant
                  $tempAccountSql = "
                  SELECT p.last_name, p.first_name, p.control_number, t.status, t.reason, t.status_updated_at, t.interview_date, e.employee_username AS updated_by
                  FROM ceap_reg_form p
                  JOIN temporary_account t ON p.ceap_reg_form_id = t.ceap_reg_form_id
                  LEFT JOIN applicant_status_logs l ON p.ceap_reg_form_id = l.ceap_reg_form_id
                  LEFT JOIN employee_logs e ON l.employee_logs_id = e.employee_logs_id
                  WHERE p.control_number = '$control_number'
                  ORDER BY t.status_updated_at DESC";
                // Order by status_updated_at in descending order
                
                $tempAccountResult = mysqli_query($conn, $tempAccountSql);
                
                if (mysqli_num_rows($tempAccountResult) > 0) {
                    echo '<div class="table-info">';
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>Date</th>';
                    echo '<th>Status</th>';
                    echo '<th>Description</th>';
                    echo '<th>Approved By</th>';
                    echo '</tr>';
                
                    // Initialize a variable to keep track of whether 'INTERVIEW' status has been displayed
                    $interviewDisplayed = false;
                
                    while ($tempAccountRow = mysqli_fetch_assoc($tempAccountResult)) {
                        $status = $tempAccountRow['status'];
                        $interview_date = $tempAccountRow['interview_date'];
                        $date = $tempAccountRow['status_updated_at'];
                        $date = date('F d, Y h:i A', strtotime($date));
                        $updatedBy = $tempAccountRow['updated_by']; // You need to fetch and populate this value
                        $reason = $tempAccountRow['reason'];
                
                        // Use a switch statement for status descriptions
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
                                    $description = 'You have been scheduled for an interview on ' . $interview_date;
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
                
                        // Check if the description is empty before adding a row
                        if ($description !== '') {
                            echo '<tr>';
                            echo '<td>' . $date . '</td>';
                            echo '<td>' . strtoupper($status) . '</td>';
                            echo '<td>' . $description . '</td>';
                            echo '<td>' . $updatedBy . '</td>';
                            echo '</tr>';
                        }
                    }
                
                    echo '</table>';
                    echo '</div>';
                }
            }
                
                            ?>
        </div>
        </div> 
        </div>
        </div>
        <footer class="text-center py-4" style="display: flex; justify-content: space-between; align-items: center; height: 70px; position:relative; top: 39px;">
            <p>&copy; Copyright 2023</p>
            <div>
                <img src="../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
                <img src="../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
            </div>
        </footer>
    </body>
</html>