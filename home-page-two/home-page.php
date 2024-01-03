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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="https://iskolarosa.com/iskolarosa/home-page-two/">
    <title>HOME | iSKOLAROSA </title>
    <link rel="icon" href="../admin-side/system-images/iskolarosa-logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Secular+One">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
    <link rel="stylesheet" href="./css/home-page.css">
    <link rel="stylesheet" href="./css/homepage_modal.css">
    <link rel='stylesheet' href='../admin-side/css/remixicon.css'>
    <link rel='stylesheet' href='./css/homepagereminderpopup.css'>
    
 
</head>
<body>
<?php 
    include 'homepage_modal.php';
?>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
    <a class="navbar-brand">
            <img src="../admin-side/system-images/iskolarosa-logo.png" alt="My Logo" class="img-logo">
        </a>
        <!-- <a href="#" class="brand">iSKOLAROSA</a> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item" style="background-color: #A5040A; border-radius: 15px; ">
                    <a class="nav-link active" href="home-page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home-page-contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../student-side/index.php">Log in</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

      <div class="container mt-4">
          <div class="row">
              <div class="col-lg-5 col-md-12">
                  <div class="flex-container">
                  <div class="top-left-row">
                    <h1 class="custom-text" style="color: #FEC021" >WELCOME</h1>
                    <h1 class="custom-text" style="color: #A5040A">iSKOLARS!</h1>
                </div>
                <div class="bottom-left-row">
                    <div class="left-card">
                        <p>Application Qualification & Requirements</p>
                        <button type="button" class="btn ceap" onclick="openHomePageModalCEAP()">
                          <i class="ri-graduation-cap-fill" style="color: #A5040A"></i> 
                          <span>College Educational Assistance Program</span>
                        </button>
                        <button class="btn lppp mt-2" onclick="openHomePageModalLPPP()">
                          <i class="ri-graduation-cap-fill" style="color: #FEC021" ></i> 
                          Libreng Pagpapaaral sa Pribadong Paaralan
                        </button>
                    </div>
                </div>

                  </div>
              </div>
              <div class="col-lg-7 col-md-12">
                <div class="right-column">
                    <div class="announcement-header">
                        <p>Announcement</p>
                    </div>
                    <div class="announcement-outer-card" >
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
                     $postTag = $row['tag'];
                     $imageURL = "../uploaded-img/" . basename($row['post_image_path']);
                     $postCreatedAt = date('F d, Y', strtotime($row['post_created_at']));
               ?>
                        <div class="announcement-inner-card">

                            <div class="posted-title">
                                <p class="#"><?php echo trim($postTitle); ?></p>
                            </div>

                            <div class="posted-tag <?php echo ($postTag == 'lppp') ? 'lppp-tag' : ''; ?>">
                                <span><?php echo $postTag; ?></span>
                            </div>
                            <div class="posted-description">
                                <p class="text-break">
                                    <?php echo trim($postDescription); ?>
                                </p>
                            </div>


                            <?php if (!empty($row['post_image_path'])) { ?>
                                <div class="posted-image">
                                    <img src="<?php echo $imageURL; ?>" alt="Post Image" class="img-fluid expandable-image" data-image-id="<?php echo $postID; ?>">
                                </div>
                            <?php } ?>

                            <div class="posted-date">
                                <p>Posted on <?php echo $postCreatedAt; ?></p>
                            </div>
                        </div>
                        
                        <?php
                        
                  }
               }
               ?>
                    </div>
                </div>
            </div>
          </div>
      </div>
      <script src="../student-side/js/bootstrap.min.js"></script>
    <script src="./js/homepage_modal.js"></script>
    <script  src="../admin-side/js/updateApplicationStartCEAP.js"></script>
    <script  src="../admin-side/js/updateApplicationStartLPPP.js"></script>


      </body>
      </html>