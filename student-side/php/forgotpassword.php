    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>iSKOLAROSA | Forgot Password</title>
        <link rel="icon" href="../../admin-side/system-images/iskolarosa-logo.png" type="image/png">
        <!-- Bootstrap 5 CDN Link -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="../../admin-side/css/remixicon.css">
        <link rel="stylesheet" href="../../admin-side/css/forgotpassword.css">
        <link rel="stylesheet" href="../../admin-side/css/status_popup.css">
    </head>
    <body> 
        <?php 
                include '../../admin-side/php/fortgotemailsentconfirm.php';
        ?>
        <section class="wrapper">
            <div class="container">
                <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                    <div class="logo">
                        <img decoding="async" src="../../admin-side/system-images/iskolarosa-logo.png" class="img-fluid" alt="logo">
                    </div>
                    <form class="rounded bg-white shadow p-5" action="#" method="post">
                        <h3 class="text-dark fw-bolder fs-4 mb-2">Forget Password ?</h3>

                        <div class="fw-normal text-muted mb-4">
                            Enter your email to reset your password.
                        </div>  

                        <div class="form-floating mb-3" style="height:90px;">
                        <input type="email" class="form-control" style="margin-bottom: 10px;" id="floatingInput" name="email" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                            <span class="emailCheckMsg" style="color: #A5040A"></span>
                        </div>  
                        <button type="submit" class="btn btn-primary submit_btn  confirm-button" id="submitForgotPassword" disabled>Submit</button>
                        <button type="button" class="btn btn-secondary submit_btn  ms-3 cancel-button" id="cancelForgotPassword">Cancel</button> 
                    </form>
                </div>
            </div>
        </section>
        <script src="../js/forgotpasswordCheckemailApplicant.js"></script>
        <script src="../js/forgotpasswordpopupApplicant.js"></script>
        <script src="../../admin-side/js/status_popup.js"></script>
    </body>
    </html>

