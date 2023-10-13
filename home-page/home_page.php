<?php
   include '../admin-side/php/config_iskolarosa_db.php';
   date_default_timezone_set('Asia/Manila'); // Replace 'Your_Timezone' with the desired timezone, e.g., 'Asia/Manila'
   
   $currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time in 24-hour format
 //  echo "Current Date and Time: $currentDateTime (FOR TESTING LANG PO ITO)"; // Echo the current date and time for debugging
   
   // Fetch posts from the database where the scheduled time is in the past or equal to the current date and time
   $sql = "SELECT * FROM create_post WHERE post_schedule_at <= '$currentDateTime' ORDER BY post_created_at DESC";
   $result = mysqli_query($conn, $sql);
   
   ?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>HOME</title>
      <link rel="icon" href="../admin-side/system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/Navbar-Right-Links-icons.css">
      <link rel="stylesheet" href="assets/css/styles.css">
      <link rel="stylesheet" href="assets/css/popup-home.css">
      <link rel="stylesheet" href="assets/css/home_page.css">
  <link rel="stylesheet" href="assets/css/temporary_account_nav.css">

      <style>
             .btn-custom {
        width: 100%;
        text-align: left;
        position: relative;
        padding-left: 40px;
        height: 60px;
        margin-bottom: 10px;
        border-radius: 15px;
        font-weight: bold;

    }

    .btn-custom::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 24px;
        height: 24px;
        mask: url(your-gradient-icon.svg) center / contain no-repeat; /* Replace 'your-gradient-icon.svg' with your icon image */
    }
    .btn-custom:hover {
        background-color: #ECECEC;
        color: black;
        font-weight: bold;
    }
        /* Modal styles */
        .modal-content {
            border-radius: 10px;
        }
        .box-container {
            text-align: left !important;
        }

      </style>
   </head>
   <body class="text-md-center">
   <div class="apply-containerPopup" id="apply-containerPopup">
                         <div class="apppopup" id="apppopup"><br>
                                 <i class="ri-error-warning-line"style="font-size: 6em; color: #F54021;"></i>
                                 <h2>NOTICE</h2>
                                 <center><p>1. Answer this application form correctly and completely.</p></center>
                                 <center><p style="margin-left: 9px;">2. Reproduction of this form is strictly prohibited.</p></center>
                                 <div>
                                <button type="button" onclick="closeapp()" style="margin-right: 15px; background-color: #C0C0C0;"><p style="margin: -3px">Cancel</p></button>
                                 <button type="submit" onclick="redirect()"><p style="margin: -3px;">Next</p></button>
                                 </div>
                             </div>
                         </div>
   <?php 
   include 'temporary_account_nav.php';
   ?>
      <div class="container">
         <div class="row">
            <div class="col-12 col-md-6 order-1 order-md-1 left-column">
               <div class="upper-left-content" style="margin-top: 50px;">
                  <h1 class="display-1 text-center text-md-center welcome" style="padding-top: 0px;margin-top: -20px;">WELCOME<br><span style="color: #FEC021">iSKOLARS!</span></h1>
               </div>
            </div>

            <div class="col-12 col-md-6 order-3 order-md-2 right-column">
   <div class="right-content">
      <div class="card" >
         <h4 class="text-start card-titles">ANNOUNCEMENT</h4>
         <div class="card-body" >
            <div class="card scrollable">
               <?php
               // Check if there's no data fetched
               if (mysqli_num_rows($result) == 0) {
               ?>
               <!-- Empty state image and message -->
               <div class="empty-state">
                  <img src="../empty-state-img/home_announcement.png" alt="No Posts" class="empty-state-image">
                  <p class="empty-state-text">
                     <strong>No Posts</strong><br>
                     It looks like there are no announcements at the moment.<br>
                     Check back later for updates and important information
                  </p>
               </div>
               <?php
               } else {
                  while ($row = mysqli_fetch_assoc($result)) {
                     $postTitle = $row['post_title'];
                     $postDescription = $row['post_description'];
                     $imageURL = "../uploaded-img/" . basename($row['post_image_path']);
                     $postCreatedAt = date('F d, Y', strtotime($row['post_created_at']));
               ?>
               <div class="card mb-3">
                  <div class="card-body dynamic-height" style="background-color: #ECECEC; box-shadow: 0 0 8px rgba(0, 0, 0, 0.3); margin: 15px; border-radius: 15px; margin-top: 10px;">
                     <h6 class=" text-muted card-subtitle mb-2">Posted on <?php echo $postCreatedAt; ?></h6>
                     <h3 class="text-center card-title"><?php echo $postTitle; ?></h3>
                     <p class="card-text description">
                        <?php echo $postDescription; ?>
                     </p>
                     <?php if (!empty($row['post_image_path'])) { ?>
                     <div class="card-image">
                        <img src="<?php echo $imageURL; ?>" alt="Post Image" class="img-fluid expandable-image" data-image-id="<?php echo $postID; ?>">
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <?php
                  }
               }
               ?>
            </div>
         </div>
      </div>
      <div id="imageOverlay" class="image-overlay" style="overflow-y: auto;">
         <div class="image-content">
            <span class="close-btn" id="closeOverlay">&times;</span>
            <img id="overlayImage" src="" alt="Expanded Image">
         </div>
      </div>
   </div>
</div>



            
<div class="col-12 col-md-6 order-2 left-column">
    <div class="lower-left-content">
        <div class="card">
          <h4 class="text-start card-titles">Qualifications & Requirements</h4>
            <div class="card-body">
            <button type="button" id="ceapmodalbutton" class="btn  btn-custom" data-bs-toggle="modal" data-bs-target="#ceapModal">
            <span class="bi bi-graduation-cap me-2"></span>       
            COLLEGE EDUCATIONAL ASSISTANCE PROGRAM
                </button>

                <!-- CEAP Modal -->
                <div class="modal fade" id="ceapModal" tabindex="-1" aria-labelledby="ceapModalLabel" aria-hidden="true">
                    <!-- Add a class to the modal for styling -->
                    <div class="modal-dialog modal-content-with-backdrop">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        <div class="modal-heading text-center">
                                            <h2>Serbisyong Makatao Lungsod na Makabago</h2>
                                            <h3>COLLEGE EDUCATIONAL ASSISTANCE PROGRAM(CEAP)</h3>
                                        </div>
                                        <?php
                                        // Fetch the last inserted ID from the ceap_configuration table
                                        $sql = "SELECT MAX(id) AS last_id FROM ceap_configuration";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result);
                                        $lastId = $row['last_id'];

                                        // Fetch data based on the last ID
                                        if (!empty($lastId)) {
                                            $sql = "SELECT * FROM ceap_configuration WHERE id = $lastId";
                                            $result = mysqli_query($conn, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $qualifications = $row['qualifications'];
                                                $requirements = $row['requirements'];

                                                // Display the fetched data inside the modal
                                                echo '<div class="box-container">';
                                                echo '<div class="box qualifications">';
                                                echo '<h3>Qualifications:</h3>';
                                                echo '<p class="output">' . $qualifications . '</p>';
                                                echo '</div>';
                                                echo '<div class="box requirements">';
                                                echo '<h3>Requirements:</h3>';
                                                echo '<p class="output">' . $requirements . '</p>';
                                                echo '</div>';
                                                echo '</div>';
                                            } else {
                                                // Set default values when no data is found
                                                $qualifications = "No data yet";
                                                $requirements = "No data yet";

                                                // Display the default message inside the modal
                                                echo '<div class="box-container">';
                                                echo '<div class="box qualifications">';
                                                echo '<h3>Qualifications:</h3>';
                                                echo '<p class="output">' . $qualifications . '</p>';
                                                echo '</div>';
                                                echo '<div class="box requirements">';
                                                echo '<h3>Requirements:</h3>';
                                                echo '<p class="output">' . $requirements . '</p>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            // Set default values when no data is found
                                            $qualifications = "No data yet";
                                            $requirements = "No data yet";

                                            // Display the default message inside the modal
                                            echo '<div class="box-container">';
                                            echo '<div class="box qualifications">';
                                            echo '<h3>Qualifications:</h3>';
                                            echo '<p class="output">' . $qualifications . '</p>';
                                            echo '</div>';
                                            echo '<div class="box requirements">';
                                            echo '<h3>Requirements:</h3>';
                                            echo '<p class="output">' . $requirements . '</p>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        ?>


                                    </div>
                                    <?php
                                    // Query to retrieve the last ID and toggle_value from the ceap_configuration table
                                    $query = "SELECT id, toggle_value FROM ceap_configuration ORDER BY id DESC LIMIT 1";

                                    // Execute the query
                                    $result = $conn->query($query);

                                    if ($result) {
                                        // Check if any rows were returned
                                        if ($result->num_rows > 0) {
                                            // Fetch the row as an associative array
                                            $row = $result->fetch_assoc();

                                            // Retrieve the toggle_value and last ID
                                            $toggleValue = $row['toggle_value'];
                                            $lastId = $row['id'];

                                            // Determine the state of the button based on the toggle value
                                            $isDisabled = ($toggleValue == 0) ? true : false;
                                        } else {
                                            // Set default values if no rows are found
                                            $isDisabled = true;
                                        }

                                    } else {
                                        // Handle the case when the query fails
                                        echo "Error retrieving toggle value: " . $conn->error;
                                    }
                                    ?>


                                        <div class="modal-footer">
                                            <?php if ($isDisabled) { ?>
                                                <a href="javascript:void(0);" class="btn btn-disabled" onclick="showMessage(); return false;">Apply Now</a>
                                            <?php } else { ?>
                                                <a href="#" class="btn btn-primary"  onclick="openApp()">Apply Now</a>
                                            <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        
<!-- Add this div for the modal backdrop -->
<div class="modal-backdrop" id="modalBackdrop"></div>

                <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#lpppModal">
                    LIBRENG PAGPAPAARAL SA PRIBADONG PAARALAN
                </button>

                <!-- LPPP Modal -->
                <div class="modal fade" id="lpppModal" tabindex="-1" aria-labelledby="lpppModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        <div class="modal-heading text-center">
                                            <h2>Serbisyong Makatao Lungsod na Makabago</h2>
                                            <h3>Libreng Pagpapaaral sa Pribadong Paaralan (LPPP)</h3>
                                        </div>
                                        <?php
                                        // Fetch the last inserted ID from the lppp_configuration table
                                        $sql = "SELECT MAX(id) AS last_id FROM lppp_configuration";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result);
                                        $lastId = $row['last_id'];

                                        // Fetch data based on the last ID
                                        if (!empty($lastId)) {
                                            $sql = "SELECT * FROM lppp_configuration WHERE id = $lastId";
                                            $result = mysqli_query($conn, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $qualifications = $row['qualifications'];
                                                $requirements = $row['requirements'];

                                                // Display the fetched data inside the modal
                                                echo '<div class="box-container">';
                                                echo '<div class="box qualifications">';
                                                echo '<h3>Qualifications:</h3>';
                                                echo '<p class="output">' . $qualifications . '</p>';
                                                echo '</div>';
                                                echo '<div class="box requirements">';
                                                echo '<h3>Requirements:</h3>';
                                                echo '<p class="output">' . $requirements . '</p>';
                                                echo '</div>';
                                                echo '</div>';
                                            } else {
                                                // Set default values when no data is found
                                                $qualifications = "No data yet";
                                                $requirements = "No data yet";

                                                // Display the default message inside the modal
                                                echo '<div class="box-container">';
                                                echo '<div class="box qualifications">';
                                                echo '<h3>Qualifications:</h3>';
                                                echo '<p class="output">' . $qualifications . '</p>';
                                                echo '</div>';
                                                echo '<div class="box requirements">';
                                                echo '<h3>Requirements:</h3>';
                                                echo '<p class="output">' . $requirements . '</p>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            // Set default values when no data is found
                                            $qualifications = "No data yet";
                                            $requirements = "No data yet";

                                            // Display the default message inside the modal
                                            echo '<div class="box-container">';
                                            echo '<div class="box qualifications">';
                                            echo '<h3>Qualifications:</h3>';
                                            echo '<p class="output">' . $qualifications . '</p>';
                                            echo '</div>';
                                            echo '<div class="box requirements">';
                                            echo '<h3>Requirements:</h3>';
                                            echo '<p class="output">' . $requirements . '</p>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        ?>


                                    </div>
                                    <?php
                                    // Query to retrieve the last ID and toggle_value from the lppp_configuration table
                                    $query = "SELECT id, toggle_value FROM lppp_configuration ORDER BY id DESC LIMIT 1";

                                    // Execute the query
                                    $result = $conn->query($query);

                                    if ($result) {
                                        // Check if any rows were returned
                                        if ($result->num_rows > 0) {
                                            // Fetch the row as an associative array
                                            $row = $result->fetch_assoc();

                                            // Retrieve the toggle_value and last ID
                                            $toggleValue = $row['toggle_value'];
                                            $lastId = $row['id'];

                                            // Determine the state of the button based on the toggle value
                                            $isDisabled = ($toggleValue == 0) ? true : false;
                                        } else {
                                            // Set default values if no rows are found
                                            $isDisabled = true;
                                        }

                                    } else {
                                        // Handle the case when the query fails
                                        echo "Error retrieving toggle value: " . $conn->error;
                                    }
                                    ?>


                                    <div class="modal-footer">
                                        <?php if ($isDisabled) { ?>
                                            <a href="javascript:void(0);" class="btn btn-disabled" onclick="showMessage(); return false;">Apply Now</a>
                                        <?php } else { ?>
                                            <a href="../lppp-reg-form/lppp-reg-form.php" class="btn btn-primary">Apply Now</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

            </div>
        </div>
    </div>
</div>
</div>
       <div class="footer-position">
         <footer class="text-center py-1">
            <div class="container">
               <div class="row row-cols-1 row-cols-lg-3">
                  <div class="col">
                     <p class="text-center text-md-center text-muted my-2"><strong><span style="color: rgba(255, 255, 255, 0.75);">Copyright&nbsp;© 2023</span></strong></p>
                  </div>
                  <div class="col">
                     
                  </div>
                  <div class="col">
                     <ul class="list-inline my-2">
                        <li class="list-inline-item"><a class="link-secondary" href="#"><strong><span style="color: rgb(255, 255, 255);">Privacy Policy</span></strong></a></li>
                        <li class="list-inline-item"><a class="link-secondary" href="#"><strong><span style="color: rgb(255, 255, 255);">Terms of Use</span></strong></a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </footer>
      </div> 
      

      <script src="assets/bootstrap/js/bootstrap.min.js"></script>
      <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <script>
    let apppopup = document.getElementById("apply-containerPopup");

    function openApp(){
        apppopup.classList.add("open-apppopup")
    }
    function closeapp(){
        apppopup.classList.remove("open-apppopup")
    } 
    function redirect(){
       window.location.href="../ceap-reg-form/ceap-reg-form.php";
    }
  
</script>

      <script>
         $(document).ready(function() {
             $('.expandable-image').click(function() {
                 var imageId = $(this).data('image-id');
                 var imageUrl = $(this).attr('src');
         
                 $('#overlayImage').attr('src', imageUrl);
                 $('#imageOverlay').addClass('active');
             });
         
             $('#closeOverlay').click(function() {
                 $('#imageOverlay').removeClass('active');
                 $('#overlayImage').attr('src', ''); // Clear the image source
             });
         });
      </script>
      <script>
         $(document).ready(function() {
             $('.description').each(function() {
                 var fullDescription = $(this).text().trim();
                 var shortenedDescription = fullDescription.substring(0, 200) ;
                 $(this).attr('data-full-description', fullDescription);
                 $(this).attr('data-shortened-description', shortenedDescription);
         
                 var seeMoreSpan = $('<span class="see-more">...See more</span>').css({
                     cursor: 'pointer',
                     color: '#4o4o4o',
                     fontWeight: 'bold'
                 });
         
                 seeMoreSpan.click(function() {
                     toggleDescription($(this));
                 });
         
                 $(this).text(shortenedDescription);
                 
                 if (fullDescription.length > 100) {
                     $(this).append(seeMoreSpan);
                 }
                 
                 if (fullDescription.length <= 100) {
                     seeMoreSpan.hide(); // Itago ang "See more" kung naka-full description na
                 }
             });
         });
         
         function toggleDescription(element) {
             var descriptionElement = element.parent();
             var fullDescription = descriptionElement.attr('data-full-description');
             var currentText = descriptionElement.text();
         
             if (currentText === fullDescription) {
                 descriptionElement.text(currentText.substring(0, 100) + '...');
             } else {
                 descriptionElement.text(fullDescription);
             }
         }
         

        function showMessage() {
            alert("Application is not yet open.");
        }
      </script>

<script>
  // JavaScript to toggle the modal backdrop
  const modal = document.getElementById('ceapModal');
  const modalBackdrop = document.getElementById('modalBackdrop');

  // Function to show the modal and backdrop
  function openModal() {
    modal.style.display = 'block';
    modalBackdrop.style.display = 'block';
  }

  // Function to close the modal and backdrop
  function closeModal() {
    modal.style.display = 'none';
    modalBackdrop.style.display = 'none';
  }

  // Attach event listeners to open and close the modal
  document.getElementById('#ceapmodalbutton').addEventListener('click', openModal);
  document.getElementById('#yourCloseButtonId').addEventListener('click', closeModal);
</script>
   </body>
</html>