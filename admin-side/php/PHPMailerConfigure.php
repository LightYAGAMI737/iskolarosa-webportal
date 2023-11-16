<?php
// Create a new PHPMailer instance
$mail = new PHPMailer\PHPMailer\PHPMailer(); // Create a new PHPMailer instance
    
// SMTP settings (replace these with your SMTP server details)
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Replace this with your SMTP server address
$mail->SMTPAuth = true;
$mail->Username = 'lancelirabackupfiles@gmail.com'; // Replace this with your SMTP username
$mail->Password = 'volmabjwfsqslffo'; // Replace this with your SMTP password
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
$mail->Port = 465; // Use port 465 for SMTPS

?>