<?php
    require_once '../../PHPMailer/PHPMailer.php';
    require_once '../../PHPMailer/SMTP.php';
    require_once '../../PHPMailer/Exception.php';

    include '../../admin-side/php/config_iskolarosa_db.php';

    function sendEmailCEAPAPPLICANT($email, $resetLink) {

        require_once '../../admin-side/php/PHPMailerConfigure.php';
        
        // Email content format
        $mail->IsHTML(true); // Set email format to HTML

        // Set up email content with inline CSS styles
        $emailContent = '
            <html>
            <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                }
                .greeting {
                    font-size: 18px;
                    font-weight: bold;
                    color: #007bff;
                    margin-bottom: 10px;
                }
                .reset-link {
                    font-size: 16px;
                    margin-bottom: 15px;
                }
                .disclaimer {
                    font-size: 12px;
                    color: #999;
                }
            </style>
            </head>
            <body>
                <div class="greeting">Greetings, iSKOLAR</div>
                <div>Thank you for initiating the password reset process for your iSKOLAROSA account. Please click the link below to reset your password:</div>
                <br>
                <div class="reset-link"><a href="' . $resetLink . '">' . $resetLink . '</a></div>
                <br>
                <div>If you did not request this password reset, you can safely ignore this email, and your password will remain unchanged.</div>
                <br>
                <div>Warm Regards,<br>
                City Scholarship Office</div>
            </body>
            </html>';

        $mail->setFrom('iskolarosa@gmail.com', 'City Scholarship Office'); // Replace this with the sender's email and name
        $mail->addAddress($email); // Recipient email from the form
        $mail->Subject = 'Reset Password for iSKOLAROSA Account';
        $mail->Body = $emailContent;

        if ($mail->send()) {
            return true; // Email sent successfully
        } else {
            $error = "Email sending failed: " . $mail->ErrorInfo;
            echo json_encode(["error" => $error]);
            return false; // Failed to send email
        }
    }

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the email from the form
    $email = $_POST['email'];

// Check if the email exists in the database and generate a reset link
$sqlEmployee = "SELECT crf.active_email_address, ta.username
FROM ceap_reg_form crf 
LEFT JOIN temporary_account ta ON ta.ceap_reg_form_id = crf.ceap_reg_form_id
WHERE active_email_address = ?";
$stmtEmployee = mysqli_prepare($conn, $sqlEmployee);
mysqli_stmt_bind_param($stmtEmployee, "s", $email);
mysqli_stmt_execute($stmtEmployee);
$resultEmployee = mysqli_stmt_get_result($stmtEmployee);

if (mysqli_num_rows($resultEmployee) === 1) {
    $row = mysqli_fetch_assoc($resultEmployee);
    $resetLink = "http://localhost/iskolarosa-7.0/student-side/php/updatePasswordApplicant.php?username=" . urlencode($row['username']);
} else {
    header("Location: ../index.php?error=EmailNotFound");
    exit();
}


    // Send the email with the reset link
    if (sendEmailCEAPAPPLICANT($email, $resetLink)) {
        echo 'success'; // Email sent successfully
    } else {
        echo 'error'; // Email sending failed
    }

    // Free the result sets and close the statements
    mysqli_free_result($resultEmployee);
    mysqli_stmt_close($stmtEmployee);
}
?>