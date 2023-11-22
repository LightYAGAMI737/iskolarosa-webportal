
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>iSKOLAROSA | Log in</title>
      <link rel="stylesheet" href="css/index.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="../admin-side/css/remixicon.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
      <script src="js/app.js" defer></script>
      <style>
      </style>
   </head>
   <body>
      <section class="module">
         <div class="module__wrapper">
            <div class="module__wrapper__seal">
               <div class="module__wrapper__seal__fixed">
                  <img src="../admin-side/system-images/iskolarosa-logo.png" alt="">
               </div>
            </div>
            <div class="module__wrapper__form">
               <div class="module__wrapper__form__header">
                  <div class="module__wrapper__form__header__border">
                     <img src="../admin-side/system-images/santarosa-logo.png" class="logo" alt="">
                  </div>
               </div>
               <div class="module__wrapper__form__content">
                  <div class="module__wrapper__form__content__title">
                     <h1><span>iSKOLAROSA</span></h1>
                     <h6> SCHOLARS IN SANTA ROSA</h6>
                  </div>
                  <form method="post"  action ="./php/login_process.php" class="module__wrapper__form__content__login">
                     <div class="separator"></div>
                     <div class="module__wrapper__form__content__login__field-input js-username">
                        <input type="text" name="username" autocomplete="off">
                        <label for="username">Control Number (2023-1234-APLA)</label>
                     </div>
                     <div class="module__wrapper__form__content__login__field-input js-password">
                        <input type="password" name="password" id="password">
                        <label for="password">Password</label>
                        <span class="password-toggle" id="password-toggle" onclick="togglePassword()"><i class="ri-eye-off-fill"></i></span>
                     </div>
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
      
                     <!-- <div class="module__wrapper__form__content__login__forgot-pass">
                        <a href="../ceap_reg_form/forgot_password.php">Forgot password?</a>
                     </div> -->
                             <div class="module__wrapper__form__content__login__field-btn">
                        <button><input type='submit' value='Login' style="background-color: transparent; color:white;"></button>
                     </div>
                     <!-- <div class="module__wrapper__form__content__login__forgot-pass">
                        <a href="../home_page/home_page.php">Register</a>
                     </div> -->
                     <br>
                     <div class="module__wrapper__form__content__login__terms">
                        <p>By using this service, you understood and agree to the iSKOLAROSA Online Services <a href="#">Terms of Use</a> and <a href="#">Privacy Statement</a>.
                        </p>
                     </div>
                     <div class="module__wrapper__form__content__login__copyright">
                        <p>Copyright 2023 &copy iSKOLAROSA</p>
                     </div>
  
                  </form>
               </div>
            </div>
         </div>
      </section>
      <script>
 function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordToggle.innerHTML = '<i class="ri-eye-fill"></i>'; // Change to a crossed-out eye
        } else {
            passwordInput.type = "password";
            passwordToggle.innerHTML = '<i class="ri-eye-off-fill"></i>'; // Change back to a regular eye
        }
    }
      </script>
   </body>
</html>