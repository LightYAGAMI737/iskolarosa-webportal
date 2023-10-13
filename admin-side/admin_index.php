<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | Log In</title>
      <link rel="icon" href="./system-images/iskolarosa-logo.png" type="image/png">

      <link rel="icon" href="images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='css/remixicon.css'>
      <link rel='stylesheet' href='css/unpkg-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <link rel="stylesheet" href="css/index.css"> 
      <script src="js/app.js" defer></script>

   
    </head>
<body>

    <section class="module">
        <div class="module__wrapper">
        
                <div class="module__wrapper__form__content">
                    <div class="module__wrapper__form__content__title">
                        <center><h1><span>iSKOLAROSA</span></h1><h6> EMPLOYEE | LOG IN</h6></center>
                    </div>
                    <form method="post" action="./php/admin_process.php" module__wrapper__form__content__login">

                       
                        <div class="separator"></div>
                        <div class="module__wrapper__form__content__login__field-input js-username">
                            <input type="text" name="username" autocomplete="off">
                            <label for="username">Username</label>
                        </div>

                        <div class="module__wrapper__form__content__login__field-input js-password">
                            <input type="password" name="password" id="password">
                            <label for="password">Password</label>
                            <span class="password-toggle" id="password-toggle" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                        </div>
                        <!-- <div class="module__wrapper__form__content__login__forgot-pass">
                            <a href="#">Forgot password?</a>
                        </div> -->

                        <div class="module__wrapper__form__content__login__field-btn">
                            <button type='submit' name='submit'>Log in</button>
                        </div>
                 
                    </form>
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

                </div>
            </div>
        </div>
    </section>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.add('show');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('show');
            }
        }
    </script>
        <script>
        // Use pushState to change the URL when the page loads
        window.onload = function() {
            history.pushState(null, '', 'login');
        };
    </script>
</body>
</html>

