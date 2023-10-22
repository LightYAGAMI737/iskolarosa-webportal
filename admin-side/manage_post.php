<?php
   session_start();
   include './php/config_iskolarosa_db.php';
   include './php/functions.php';
   
   if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
   }
   
   $requiredPermission = 'view_ceap_applicants';
   
   $requiredPermissions = [
    'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
    'edit_users' => 'You do not have permission to edit applicant.',
    'delete_applicant' => 'You do not have permission to delete applicant.',
   ];
   
   if (!isset($requiredPermissions[$requiredPermission])) {
    echo 'Invalid permission specified.';
    exit();
   }
   
   if (!hasPermission($_SESSION['role'], $requiredPermission)) {
    echo $requiredPermissions[$requiredPermission];
    exit();
   }
   
   date_default_timezone_set('Asia/Manila'); 
   
   $currentDateTime = date('Y-m-d H:i:s'); 
   
   $sql = "SELECT * FROM create_post WHERE post_schedule_at <= '$currentDateTime' ORDER BY post_created_at DESC";
   $result = mysqli_query($conn, $sql);
   
   $currentPage = "post";
   $currentSubPage = 'manage post';
   
   $sql = "SELECT * FROM create_post WHERE post_schedule_at <= '$currentDateTime' ORDER BY post_created_at DESC";
   $result = mysqli_query($conn, $sql);
   
   $selectedTag = $_GET['tag'] ?? '';
   if ($selectedTag === 'all') {
    $sql = "SELECT * FROM create_post WHERE tag IN ('ceap', 'lppp') ORDER BY post_created_at DESC";
   } elseif (!empty($selectedTag)) {
    $sql = "SELECT * FROM create_post WHERE tag = '$selectedTag' ORDER BY post_created_at DESC";
   } else {
    $sql = "SELECT * FROM create_post ORDER BY post_created_at DESC";
   }
   
   $result = mysqli_query($conn, $sql);
   
   $result = mysqli_query($conn, $sql);
   
   ?>

<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='css/remixicon.css'>
      <link rel='stylesheet' href='css/unpkg-layout.css'>
      <link rel="stylesheet" href="css/side_bar.css">
      <link rel="stylesheet" href="css/status_popup.css">
      <link rel="stylesheet" href="./css/manage_post.css">
   </head>
   <body>
      <?php
         include './php/deletepostPopup.php';
         include './php/side_bar_main.php';
         ?>
      <!-- home content -->
      <div class="header-label post">
         <h1 >Manage Post</h1>
      </div>
      <form action="#" method="post" id="post-form">
      <div class="card">
         <div class="header-delete-checkbox">
            <!--dropdown filtering--->
            <div class="custom-select">
               <select id="tag-filter" onchange="applyFilter()">
                  <option value="" hidden>Tags</option>
                  <option value="all">All</option>
                  <option value="ceap">CEAP</option>
                  <option value="lppp">LPPP</option>
               </select>
               </div>
            <div class="post-checkbox">
               <button type="button" onclick="opendeletepostpopup()" class="delete-button">
               <i class="ri-delete-bin-fill" style="margin-right: 7px;"></i>
               <span>Delete</span>
            </button>
               <label for="select-all" id="select-all-label">Select All</label>
               <input type="checkbox" id="select-all" onchange="selectAllCheckboxes()">
            </div>
         </div>
         <div class="outer-card-body">
            <?php
               if (mysqli_num_rows($result) === 0) {
                 echo '<div class="empty-state">';
                 echo '<img src="../empty-state-img/manage_post.png" alt="No records found" class="empty-state-image">';
                 echo "<p class='empty-state-message'>It seems you haven't created any posts yet. You can get started by clicking the 'Create Post' button below.</p>";
                 echo '<a href="create_post.php" class="createpostbtn btn-primary">';
                 echo '</a>';
               } else {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $postTitle = $row['post_title'];
                    $postDescription = $row['post_description'];
                    $tag = $row['tag'];
                    $postCreatedAt = date('F d, Y', strtotime($row['post_created_at']));
                  ?>
            <div class="card mb-3">
               <div class="card-body dynamic-height">
                  <div class="post-left-column">
                     <div class="post-title">
                        <div class="post-info-label">Title:</div>
                        <h3 class="text-center card-title truncate" style="font-weight: normal;"><?php echo $postTitle; ?></h3>
                     </div>
                     <div class="post-description">
                        <div class="post-info-label">Description:</div>
                        <p class="card-text description truncate">
                           <?php echo $postDescription; ?>
                        </p>
                     </div>
                  </div>
                  <div class="post-right-column">
                     <div class="post-date">
                        <div class="post-info-label">Posted on:</div>
                        <p><?php echo $postCreatedAt; ?></p>
                     </div>
                     <div class="post-tag">
                        <div class="post-info-label">Tag:</div>
                        <p class="card-text description">
                        <p><?php echo $tag; ?></p>
                        </p>
                     </div>
                  </div>
                  <div class="post-checkbox">
                     <input type="checkbox" name="selected_posts[]" value="<?php echo $row['create_post_id']; ?>">
                  </div>
               </div>
            </div>
            <?php
               }
               }
               ?>
         </div>
      </div>
      <!-- End display post -->
      </main>
      <div class="overlay"></div>
      </div>
      <script src='js/unpkg-layout.js'></script>
      <script  src="./js/side_bar.js"></script>
      <script  src="./js/status_popup.js"></script>
      <script  src="./js/deletepostPopup.js"></script>

      <script>
         function selectAllCheckboxes() {
         const selectAllCheckbox = document.getElementById('select-all');
         const checkboxes = document.querySelectorAll('input[name="selected_posts[]"]');
         
         checkboxes.forEach((checkbox) => {
         checkbox.checked = selectAllCheckbox.checked;
         });
         }
         
         function confirmDelete() {
         const selectedCheckboxes = document.querySelectorAll('input[name="selected_posts[]"]:checked');
         
           document.getElementById("post-form").submit();
         }
         
         
         const selectAllCheckbox = document.getElementById('select-all');
         const checkboxes = document.querySelectorAll('input[name="selected_posts[]"]');
         
         selectAllCheckbox.addEventListener('change', function () {
         checkboxes.forEach((checkbox) => {
         checkbox.checked = selectAllCheckbox.checked;
         });
         });
         
         checkboxes.forEach((checkbox) => {
         checkbox.addEventListener('change', function () {
         const isAnyUnchecked = Array.from(checkboxes).some((cb) => !cb.checked);
         selectAllCheckbox.checked = !isAnyUnchecked;
         });
         });
         
      </script>
      <script>
         document.addEventListener("DOMContentLoaded", function () {
           const truncatableElements = document.querySelectorAll(".truncate");
         
           truncatableElements.forEach((element) => {
             const originalText = element.textContent;
             const maxLength = 50;
         
             if (originalText.length > maxLength) {
               const truncatedText = originalText.slice(0, maxLength) + "...";
               element.textContent = truncatedText;
             }
           });
         });
      </script>
      <script>
         function applyFilter() {
           const selectedTag = document.getElementById('tag-filter').value;
           window.location.href = `manage_post.php?tag=${selectedTag}`;
         
         const urlParams = new URLSearchParams(window.location.search);
         const selectedTagParam = urlParams.get('tag');
         
         if (selectedTagParam) {
           document.getElementById('tag-filter').value = selectedTagParam;
         } 
         
         }
         
      </script>
   </body>
</html>