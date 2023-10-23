<?php 
	include './php/checkemailForgotpassword.php';
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
				<form class="rounded bg-white shadow p-5" >
					<h3 class="text-dark fw-bolder fs-4 mb-2">Forget Password ?</h3>

					<div class="fw-normal text-muted mb-4">
						Enter your email to reset your password.
					</div>  

					<div class="form-floating mb-3">
					<input type="email" class="form-control" style="margin-bottom: 10px;" id="floatingInput" name="active_email_address" placeholder="name@example.com">
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

