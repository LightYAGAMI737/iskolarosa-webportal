<?php
// Include PHPMailer dependencies
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

// Function to send email using PHPMailer
function sendEmail($email, $Fullname, $username, $password,)
{
    require_once './php/PHPMailerConfigure.php';
    // Email content format
    $mail->IsHTML(true); // Set email format to HTML

    $mail->setFrom('iskolarosa@gmail.com', 'City Scholarship Office'); // Replace this with the sender's email and name
    $mail->addAddress($email); // Recipient email from the form
    $mail->Subject = 'Welcome to City Scholarhip Office - Your Account Details';

    
    $emailContent = '<html>
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
        .login-details {
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
        <div class="greeting">Dear, ' . $Fullname . '</div>

        <div>Your account has been successfully created, and we are excited to have you on board.</div>
        <br>
        <div>Below are your account details:</div>
        <div>Username: ' . $username . '</div>
        <div>Password: ' . $password . '</div>
        <br>
        <div>
        For security reasons, we recommend changing your password after your first login. To do so, simply log in, and follow the instructions for updating your password.
        </div>
        <br>
        <div>Warm Regards,<br>
        City Scholarship Office</div>
        <br>
        <br>
        <div class="disclaimer">
        <center>
            <strong>DISCLAIMER AND CONFIDENTIALITY NOTICE:</strong><br><br>
            <strong>THIS IS A CITY SCHOLARSHIP OFFICE AND ISKOLAROSA EMAIL ACCOUNT</strong><br><br>
            The information contained in this e-mail, including those in its attachments, is confidential and intended only for the person(s) or entity(ies) to which it is addressed. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you are not an intended recipient, you must not read, copy, store, disclose, distribute this message, or act in reliance upon the information contained in it. If you received this e-mail in error, please contact the sender, delete the material from any computer or system and do not forward it or any part of it to anyone else. Thank you for your cooperation and understanding.
        </center>
        </div>
    </body>
    </html>';

    $mail->Body = $emailContent;
    
    // Send the email
    if ($mail->send()) {
        return true; // Email sent successfully
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false; // Failed to send email
    }
}

?>