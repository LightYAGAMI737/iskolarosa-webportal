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
  <title>iSKOLAROSA | Quick Guide</title>
      <link rel="icon" href="../../admin-side/system-images/iskolarosa-logo.png" type="image/png">
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
      <link rel="stylesheet" href="../../admin-side/css/remixicon.css">
      <link rel="stylesheet" href="../css/tempAcc_nav.css">
      <link rel="stylesheet" href="../css/tempAcc_quickguide.css">
</head>
<body>
<?php
include 'personalAcc_nav.php'; 
?>
<div class="content-side">
    <div class="main">
        <h2>Quick Guide for iSkolars</h2>

        <div class="card">
                <h5>Step 1:</h5>
                <h6>To submit your application, kindly visit the official website (iskolarosa.com).
                    Once the announcement has been made regarding the availability of applications for CEAP or LPPP, please proceed to the 
                    "Application Qualification and Requirements" section and read through the details presented before clicking on "Apply Now" to begin the application process. </h6><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.com). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
         
         
            <div class="card">
                <h5>Step 2:</h5>
                <h6>LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM  </h6><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.com). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
           
            
            <div class="card">
                <h5>Step 3:</h5>
                <h6>LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM LOREM IPSUM IPSUM  </h6><br>
                <p>Upang maipasa ang iyong aplikasyon, pakibisita ang opisyal na website (iskolarosa.com). 
                    Kapag mayroon nang anunsyo tungkol sa pagkakaroon ng aplikasyon para sa CEAP o LPPP, mangyaring pumunta sa seksyon ng 
                    "Kwalipikasyon at mga Kinakailangan sa Aplikasyon" at basahin ang mga detalye na ipinresenta bago i-klik ang "Mag-Apply Ngayon" upang simulan ang proseso ng aplikasyon.</p>
            </div>
         
    </div>
</div>

<script src="../js/bootstrap.min.js"></script>

</body>
</html>
