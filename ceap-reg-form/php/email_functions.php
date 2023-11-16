 <?php 
   require_once '../../PHPMailer/PHPMailer.php';
   require_once '../../PHPMailer/SMTP.php';
   require_once '../../PHPMailer/Exception.php';
   
   // Function to send email using PHPMailer
   function sendEmail($email, $username, $password)
   {
   require_once '../../admin-side/php/PHPMailerConfigure.php';
       // Email content format
       $mail->IsHTML(true); // Set email format to HTML
   
       // Set up email content with inline CSS styles
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
       <div class="greeting">Greetings,</div>
       <div>As you begin your journey towards a bright academic future, we hope this email finds you in good spirits. On behalf of the City Scholarship Office, congratulations on successfully registering as an iSKOLAR under the<strong> College Educational Assistance Program!</strong></div>
       <br>
       <div>To help you get started, we are pleased to provide you with your Temporary Control Number and Password, which will grant you access to your personalized iSKOLAR account on our platform. Take note that these credentials are crucial for verifying your eligibility and confidentiality.</div>
       <br>
       <div class="login-details">
           Please find your login details below:<br>
           <strong>Control Number:</strong> ' . $username . '<br>
           <strong>Password:</strong> ' . $password . '<br>
       </div>
       <div>
           Once the verification process is complete, we will notify you promptly, and you will receive a formal confirmation of your Iskolar status or you can also check the website alongside your credentials to view the status page and check your progress. At that time, you can fully access the benefits and opportunities offered under the City Scholarship Office website\'s iSKOLAROSA.
       </div>
       <br>
       <div>In the meantime, we encourage you to stay engaged with our updates and announcements. Thank you iSKOLARS!</div>
       <br>
       <div>Warm Regards,<br>
       City Scholarship Office</div>
       <br>
       <div>[iSKOLAROSA LOGO]</div>
       <br>
       <div class="disclaimer">
       <center>
           <strong>DISCLAIMER AND CONFIDENTIALITY NOTICE:</strong><br><br>
          <strong> THIS IS A CITY SCHOLARSHIP OFFICE AND ISKOLAROSA EMAIL ACCOUNT</strong><br><br>
           The information contained in this e-mail, including those in its attachments, is confidential and intended only for the person(s) or entity(ies) to which it is addressed. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you are not an intended recipient, you must not read, copy, store, disclose, distribute this message, or act in reliance upon the information contained in it. If you received this e-mail in error, please contact the sender, delete the material from any computer or system and do not forward it or any part of it to anyone else. Thank you for your cooperation and understanding.
           </center>
       
           </div>
   </body>
   </html>';
   
       $mail->setFrom('iskolarosa@gmail.com', 'City Scholarship Office'); // Replace this with the sender's email and name
       $mail->addAddress($email); // Recipient email from the form
       $mail->Subject = 'New User Registration';
   
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