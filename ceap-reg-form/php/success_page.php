<?php 
session_start();
        // Include necessary files and display success page
        require_once 'ceapregformdatabaseinsert.php'; // Include other necessary files
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
      <title>iSKOLAROSA | CEAP REG FORM</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="../css/confirmpopup.css">
      <link rel='stylesheet' href='../../admin-side/css/remixicon.css'>



    <!-- Include your CSS and JavaScript here -->
</head>
<body>
<body>
<?php
// Check if registration was successful
if (isset($_SESSION['duplicateApplicantFlag']) && $_SESSION['duplicateApplicantFlag'] === true) {
    // Display the success message popup
    echo '
    <div class="blur-background">
        <div class="subconfirmcontainerpopup session_alerts" id="subconfirmcontainerpopup">
            <div class="subconfirmpopup" id="subconfirmpopup"><br>
                <i class="ri-checkbox-circle-line" style="color: #0BA350; font-size: 5em;"></i>
                <strong><h2>Application Submitted</h2></strong>
                <p>Thank you for your application. We\'ve sent you an email with your temporary account credentials for checking your application status. You can now log in.</p>
                <div style="padding: 10px;">
                    <button type="button" id="okButton" onclick="closePopup()" class="cooldown" style="margin-right: 15px;" disabled>Ok</button>
                </div>
            </div>
        </div>
    </div>';
} else {
    echo 'yo';
}
    ?>


    <!-- display the inserted value here-->
    <?php
  //  include '../../admin-side/php/config_iskolarosa_db.php';

    // Query to fetch data from the ceap_reg_form table
//$sql = "SELECT * FROM ceap_reg_form ORDER BY ceap_reg_form_id DESC LIMIT 1";
//$result = mysqli_query($conn, $sql);

// Check if there are any rows in the result
//if (mysqli_num_rows($result) > 0) {
    // Loop through the rows and display the data
    //while ($row = mysqli_fetch_assoc($result)) {
      //  echo "<h1>Name: " . $row["first_name"] . "</h1>";
    //    echo "<h1>Email: " . $row["active_email_address"] . "</h1>";
        // You can display more data as needed
  //  }
//} else {
//    echo "No data found in the ceap_reg_form table.";
//}

// Close the database connection
//mysqli_close($conn);
?>

<script>
    function closePopup() {
        var popup = document.getElementById("subconfirmcontainerpopup");
        var blurBackground = document.querySelector(".blur-background"); // Select the blurred background element
        popup.style.display = "none";
        blurBackground.style.display = "none"; // Hide the blurred background
    }

    // Enable the "Ok" button after 10 seconds (10000 milliseconds)
    setTimeout(enableOkButton, 10000);

    function enableOkButton() {
        var okButton = document.getElementById("okButton");
        okButton.disabled = false; // Enable the button
    }
</script>

</body>
</html>
