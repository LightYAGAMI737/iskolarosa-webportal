<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iSKOLAROSA | Update Password</title>
    <link rel="icon" href="../../admin-side/system-images/iskolarosa-logo.png" type="image/png">
	<!-- Bootstrap 5 CDN Link -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<!-- Custom CSS Link -->
	<link rel="stylesheet" href="../../admin-side/css/forgotpassword.css">
    <style>
    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-label {
        margin-left: 10px; /* Adjust the margin as needed to control the spacing */
    }
    .form-check-input:checked {
    background-color: #FEC021;
    border-color: #FEC021;
    }
    .form-design {
        padding: 3rem 3rem 1rem!important;
    }
    #tooltipsID {
        position: absolute;
    background-color: #333;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
    z-index: 1000;
    width: 300px;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
    bottom: 51%;
    left: 50%;
    transform: translateX(-50%);
    text-align: left;

}

.tooltips.active {
    visibility: visible;
    opacity: 1;
}
</style>
</head>
<body> 
    <section class="wrapper">
		<div class="container" style="margin-top: 100px;">
			<div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
				<div class="logo">
					<img decoding="async" src="../../admin-side/system-images/iskolarosa-logo.png" class="img-fluid" alt="logo" >
				</div>
                <form class="rounded bg-white shadow form-design" action="first_time_login_process.php" method="post">
                    <h3 class="text-dark fw-bolder fs-4 mb-2">Update Password</h3>
                    <p class="text-dark">(for first time log in)</p>
                        <div class="fw-normal text-muted mb-4">
                        </div>  
                        <div id="tooltipsID">
                <div class="tooltips"></div>
                </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" style="margin-bottom: 10px;" id="new_password" name="new_password" placeholder="New Password">
                                <label for="new_password">New Password</label>
                                <span id="new_password_error"></span>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" style="margin-bottom: 10px;" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
                                <label for="confirm_password">Confirm New Password</label>
                                <span id="confirm_password_error"></span>
                            </div>

                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
    <button type="submit" class="btn btn-primary submit_btn my-4" id="submitForgotPassword" disabled>Submit</button>
    <button type="button" class="btn btn-secondary submit_btn my-4 ms-3" id="cancelForgotPassword">Cancel</button>
</form>
			</div>
		</div>
	</section>
    <script>
  // Get the input elements by their IDs
  var new_password = document.getElementById('new_password');
    var confirm_password = document.getElementById('confirm_password');

    // Add an event listener for the 'input' event on the username input
    new_password.addEventListener('input', function() {
        // Remove any spaces from the input value
        this.value = this.value.replace(/\s/g, '');
    });

    // Add an event listener for the 'input' event on the password input
    confirm_password.addEventListener('input', function() {
        // Remove any spaces from the input value
        this.value = this.value.replace(/\s/g, '');
    });
</script>
	<script>
    var cancelresetBtn = document.getElementById("cancelForgotPassword");
        cancelresetBtn.addEventListener("click", function () {
            window.location.href = "../index.php";
        });
</script>
    <script src="../../admin-side/js/validatepassword.js"></script>
</body>
</html>