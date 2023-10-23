<?php
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

include './php/config_iskolarosa_db.php';

function sendEmail($email, $resetLink) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer(); // Create a new PHPMailer instance
   
    // SMTP settings (replace these with your SMTP server details)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace this with your SMTP server address
    $mail->SMTPAuth = true;
    $mail->Username = 'lancelirabackupfiles@gmail.com'; // Replace this with your SMTP username
    $mail->Password = 'ccrvxxdtujkbqanw'; // Replace this with your SMTP password
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS for port 465
    $mail->Port = 465; // Use port 465 for SMTPS
    
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
function forgotPasswordHandler($conn, $email) {
    $sqlEmployee = "SELECT email, username FROM employee_list WHERE email = ?";
    $stmtEmployee = mysqli_prepare($conn, $sqlEmployee);
    mysqli_stmt_bind_param($stmtEmployee, "s", $email);
    mysqli_stmt_execute($stmtEmployee);
    $resultEmployee = mysqli_stmt_get_result($stmtEmployee);
    if (mysqli_num_rows($resultEmployee) === 1) {
        $row = mysqli_fetch_assoc($resultEmployee);
        $resetLink = "http://localhost/iskolarosa-7.0/admin-side/resetpasswordemployee.php?username=" . urlencode($row['username']);
    } else {
        error_log("Email not found in the database.", 0);
        header("Location: forgotpassword.php?error=EmailNotFound");
        exit();
    }

    // Free the result sets and close the statements
    mysqli_free_result($resultEmployee);
    mysqli_stmt_close($stmtEmployee);

    // Send the email with the reset link
    if (sendEmail($email, $resetLink)) {
        // Email sent successfully, show an alert message and then redirect the user to the forgotpassword page
        echo '<script>alert("Check your email for the password reset link."); window.location.href = "../student_side/index.php";</script>';
        exit();
    } else {
        // Email sending failed, handle the error accordingly
        header("Location: forgotpassword.php?error=EmailSendingError");
        exit();
    } 
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the email from the form
    $email = $_POST['email'];
    forgotPasswordHandler($conn, $email);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Forget Password Form Using Bootstrap 5</title>
	<!-- Bootstrap 5 CDN Link -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Custom CSS Link -->
	<link rel="stylesheet" href="./css/forgotpassword.css">
</head>
<body> 
    <section class="wrapper">
		<div class="container">
			<div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
				<div class="logo">
					<img decoding="async" src="./system-images/iskolarosa-logo.png" class="img-fluid" alt="logo">
				</div>
				<form class="rounded bg-white shadow p-5" action="forgotpassword.php" method="post">
					<h3 class="text-dark fw-bolder fs-4 mb-2">Forget Password ?</h3>

					<div class="fw-normal text-muted mb-4">
						Enter your email to reset your password.
					</div>  

					<div class="form-floating mb-3">
					<input type="email" class="form-control" style="margin-bottom: 10px;" id="floatingInput" name="email" placeholder="name@example.com">
						<label for="floatingInput">Email address</label>
						<span class="emailCheckMsg" style="color: #A5040A"></span>
					</div>  

					<button type="submit" class="btn btn-primary submit_btn my-4" id="submitForgotPassword" disabled>Submit</button>
                    <button type="button" class="btn btn-secondary submit_btn my-4 ms-3" id="cancelForgotPassword">Cancel</button> 
				</form>
			</div>
		</div>
	</section>
	<script src="./js/forgotpasswordCheckemail.js"></script>
</body>
</html>

