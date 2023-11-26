<?php


// Include PHPMailer dependencies
require_once '../../PHPMailer/PHPMailer.php';
require_once '../../PHPMailer/SMTP.php';
require_once '../../PHPMailer/Exception.php';

// Function to send email using PHPMailer
function sendEmail($email, $control_number, $status)
{
    require_once '../php/PHPMailerConfigure.php';
    // Email content format
    $mail->IsHTML(true); // Set email format to HTML

    $mail->setFrom('iskolarosa@gmail.com', 'City Scholarship Office'); // Replace this with the sender's email and name
    $mail->addAddress($email); // Recipient email from the form
    $mail->Subject = 'Update on Your Application Status';

    
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
        <div class="greeting">Greetings, ' . $control_number . '</div>

        <div>As part of our scholarship application review process, we are pleased to inform you that your application status has been updated to '.  $status .'. This indicates that the registration form you submitted has been carefully reviewed and verified by the Scholarship Office.</strong></div>
        <br>
        <div>We kindly request that you stay tuned for future updates on your application status. You can conveniently track your progress by logging in to your temporary account.</div>
        <br>
        <div>
        Thank you for your cooperation, and we look forward to assisting you further in your scholarship journey.
        </div>
        <br>
        <div>Warm Regards,<br>
        City Scholarship Office</div>
        <br>
        <div>[iSKOLAROSA LOGO]</div>
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