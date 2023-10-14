<!DOCTYPE html>
<html>
<head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | Log In</title>
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="css/admin_login.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<!-- <img class="wave" src="./system-imges/wave.png"> -->
	<div class="container">
		<div class="img">
			<img src="./system-images/bg.svg">
		</div>
		<div class="login-content">
			<form action="./php/admin_process.php" method="post">
				<img src="./system-images/iskolarosa-logo.png">
				<h2 class="title">EMPLOYEE | LOG IN</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Username</h5>
           		   		<input type="text" name="username" class="input">
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
                      <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input" id="passwordField">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
            	</div>
            	<a href="#">Forgot Password?</a>
            	<input type="submit" class="btn" value="Login">

                <?php
// Check for an error parameter in the URL
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    echo "<p style='color: red; text-align: center;'>$error</p>";

    // JavaScript to remove the error parameter from the URL
    echo "<script> 
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.pathname);
        }
    </script>";
}
?>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/admin-login.js"></script>
</body>
</html>
