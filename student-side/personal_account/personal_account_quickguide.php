<?php
include '../../admin-side/php/config_iskolarosa_db.php';
session_start();
// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['control_number'])) {
    // You can either show a message or redirect to the login page
    echo 'You need to log in to access this page.';
    // OR
     header("Location: index.php"); // Redirect to the login page
    exit();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/temporary_account_nav.css">
  <link rel="stylesheet" href="../css/temporary_account_quickguide.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  <script src="../js/temporary_account_nav.js"></script>
  <title>iSKOLAROSA | Personal Account</title>
  <style>


    </style>
</head>
<body>
<?php
include 'personal_account_nav.php'; 
?>
<div class="content-side">
    <div class="main">
        <h1>QUICK GUIDE</h1>

        <div class="card">
                <h2>Step 1:</h2>
                <h4>To submit your application, kindly visit the official website (iskolarosa.laguna.ph).
                    Once the announcement has been made regarding the availability of applications for CEAP or LPPP, please proceed to the 
                    "Application Qualification and Requirements" section and read through the details presented before clicking on "Apply Now" to begin the application process. </h4><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.laguna.ph). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
         
         
            <div class="card">
                <h2>Step 2:</h2>
                <h4>LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM  </h4><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.laguna.ph). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
           
            
            <div class="card">
                <h2>Step 3:</h2>
                <h4>LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM  </h4><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.laguna.ph). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
         
    </div>
</div>

<footer class="text-center py-4" style="display: flex; justify-content: space-between; align-items: center; height: 70px; position:relative; top: 39px;">
  <p>&copy; Copyright 2023</p>
  <div>
    <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
    <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="" style="width: 70px; height: 70px;">
  </div>
</footer>

</body>
</html>
