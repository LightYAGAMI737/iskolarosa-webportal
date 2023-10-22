<?php
   // Include configuration and functions
   session_start();
   include '../php/config_iskolarosa_db.php';
   include '../php/functions.php';
    
   // Description: This script handles permission checks and retrieves applicant information.
   
   // Check if the session is not set (user is not logged in)
   if (!isset($_SESSION['username'])) {
    echo 'You need to log in to access this page.';
    exit();
}

// Define the required permission
$requiredPermission = 'view_ceap_applicants';

// Define an array of required permissions for different pages
$requiredPermissions = [
    'view_ceap_applicants' => 'You do not have permission to view CEAP applicants.',
    'edit_users' => 'You do not have permission to edit applicant.',
    'delete_applicant' => 'You do not have permission to delete applicant.',
];

// Check if the required permission exists in the array
if (!isset($requiredPermissions[$requiredPermission])) {
    echo 'Invalid permission specified.';
    exit();
}

// Call the hasPermission function to check the user's permission
if (!hasPermission($_SESSION['role'], $requiredPermission)) {
    echo $requiredPermissions[$requiredPermission];
    exit();
}

date_default_timezone_set('Asia/Manila'); // Replace 'Your_Timezone' with the desired timezone, e.g., 'Asia/Manila'

$currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time in 24-hour format
//    echo "Current Date and Time: $currentDateTime (FOR TESTING LANG PO ITO)"; // Echo the current date and time for debugging

// Fetch posts from the database where the scheduled time is in the past or equal to the current date and time
$sql = "SELECT * FROM create_post WHERE post_schedule_at <= '$currentDateTime' ORDER BY post_created_at DESC";
$result = mysqli_query($conn, $sql);

// Set variables
$currentPage = "post";
$currentSubPage = 'manage post';

  // Fetch posts from the database where the scheduled time is in the past or equal to the current date and time
  $sql = "SELECT * FROM create_post WHERE post_schedule_at <= '$currentDateTime' ORDER BY post_created_at DESC";
  $result = mysqli_query($conn, $sql);
  


// Get the selected tag from the query parameter
$selectedTag = $_GET['tag'] ?? '';

// Construct SQL query based on the selected tag
if ($selectedTag === 'all') {
    // Display posts with both "ceap" and "lppp" tags
    $sql = "SELECT * FROM create_post WHERE tag IN ('ceap', 'lppp') ORDER BY post_created_at DESC";
} elseif (!empty($selectedTag)) {
    // Display posts with the selected tag
    $sql = "SELECT * FROM create_post WHERE tag = '$selectedTag' ORDER BY post_created_at DESC";
} else {
    // If no tag is selected, display all posts
    $sql = "SELECT * FROM create_post ORDER BY post_created_at DESC";
}

$result = mysqli_query($conn, $sql);

$result = mysqli_query($conn, $sql);

// Your HTML and PHP code to display the filtered posts
// Iterate through the $result to display the posts
?>


<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="UTF-8">
      <title>iSKOLAROSA | <?php echo strtoupper($currentSubPage); ?></title>
      <link rel="icon" href="../system-images/iskolarosa-logo.png" type="image/png">
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
      <link rel='stylesheet' href='https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css'>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
      <link rel="stylesheet" href="../css/side_bar.css">
      <link rel="stylesheet" href="../css/manage_post.css">
      <link rel="stylesheet" href="../css/create_post.css">
      <link rel="stylesheet" type="text/css" href="../css/popup.css">



      <style>
         .card {
         width: 100%;
         margin-bottom: 20px;
         }
         .card-body {
         padding: 0;
         }
         .dynamic-height {
         display: flex;
         align-items: center;
         padding: 20px;
         }
         .post-left-column,
         .post-right-column {
         flex: 50%;
         padding: 10px; /* Add padding for spacing */
         }
         .post-left-column {
         margin-right: 50px; /* Add margin between columns */
         }
  
         .post-info-label {
         font-weight: bold;
         }
         .post-info {
         display: flex;
         align-items: center;
         margin-bottom: 10px;
         }
         .post-icon {
         margin-right: 10px;
         }
         .post-icon i {
         color: red;
         }
         .post-date {
         margin-left: auto;
         margin-right: 20px;
         }
         .post-checkbox {
         margin-left: auto;
         }
         .post-title,
         .post-tag,
         .card-title,
         .description,
         .post-info-label {
         text-transform: uppercase;
         }
         /* Add this CSS to your existing styles */
.post-checkbox {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin-top: 10px;
}

.delete-button {
  background-color: #A5040A;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
  margin-right: 30px;
}

.delete-button:hover {
  background-color: #c82333;
}

/* Style the "Select All" checkbox label */
#select-all-label {
  margin-right: 10px;
}
/* Style for checkboxes */
.post-checkbox input[type="checkbox"] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 16px;
  height: 16px;
  border: 1px solid #ccc;
  border-radius: 3px;
  margin-right: 6px;
  position: relative;
  top: 2px;
  border: 1px solid #A5040A;
  cursor: pointer;
}

/* Style for selected checkboxes */
.post-checkbox input[type="checkbox"]:checked {
  background-color: red;
  border: 1px solid #A5040A;
  
}

/* Style for "Select All" label */
#select-all-label {
  margin-right: 10px;
  cursor: pointer;
}
.header-delete-checkbox {
    margin-bottom: 20px;
    margin-right: 20px;
}
#tag-filter {
    border-radius: 15px;
    border: 2px solid #400000;
    background: #400000;
    width: 120px;
    height: 40px;
    flex-shrink: 0;
    color: #fff;
    font-size: 16px;
    padding: 10px;
}

#tag-filter option {
    background-color: #400000;
    color: #fff;
    font-size: 16px;
}

      </style>
   </head>
   <body>
      <?php
         include 'head_admin_side_bar.php';
         ?>
      <!-- home content -->
      <div class="header-label post">
<h1>Manage Posts</h1>
</div>
      <form action="delete_post.php" method="post" id="post-form">
      <div class="card">
      <div class="header-delete-checkbox">
        <!--dropdown filtering--->
        <select id="tag-filter" onchange="applyFilter()">
        <option value="" hidden>FILTER</option>
    <option value="all">All Tags</option>
        <option value="ceap">CEAP</option>
    <option value="lppp">LPPP</option>
</select>

      <div class="post-checkbox">
      <button type="button" onclick="openconfirmDelete()" class="delete-button">Delete</button>
      <div class="popupdel" id="popupdel"><br>
            <i class="ri-delete-bin-fill" style="font-size: 10em; color: #A5040A;"></i>
            <strong><h2>Delete Post?</h2></strong>
            <p>Deleting the selected post(s) will permanently remove. This action cannot be undo. Are you sure you want to delete these posts?</p>
            <div style="padding: 10px;">
            <button type="button" onclick="closeDelete()" style="margin-right: 15px; background-color: #C0C0C0;"><i class="ri-close-fill"></i>Cancel</button>
            <button type="button" onclick="closeDelete(), confirmDelete()"><i class="ri-check-line"></i>Confirm</button>
            </div>
        </div>
        
        <label for="select-all" id="select-all-label">Select All</label>
        <input type="checkbox" id="select-all" onchange="selectAllCheckboxes()">
      </div>
      </div>
         <div class="card-body">
            <?php
             // Check if there are any records fetched
             if (mysqli_num_rows($result) === 0) {
              echo '<div class="empty-state">';
              echo '<img src="../../empty-state-img/manage_post.png" alt="No records found" class="empty-state-image">';
              echo "<p class='empty-state-message'>It seems you haven't created any posts yet. You can get started by clicking the 'Create Post' button below.</p>";
              echo '<a href="create_post.php" class="createpostbtn btn-primary">';
              echo '<i class="ri-edit-2-fill"></i> Create Post'; // Include the icon here
              echo '</a>';
          } else {
               while ($row = mysqli_fetch_assoc($result)) {
                 $postTitle = $row['post_title'];
                 $postDescription = $row['post_description'];
                 $tag = $row['tag'];
                 $postCreatedAt = date('F d, Y', strtotime($row['post_created_at']));
               ?>
            <div class="card mb-3">
               <div class="card-body dynamic-height" style="background-color: #ECECEC; box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);border-radius: 15px; display: flex;">
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
      <!-- <footer class="footer" style="background: green;">
         </footer> -->
      </main>
      <div class="overlay"></div>
      </div>
      <!-- partial -->
      <script src='https://unpkg.com/@popperjs/core@2'></script><script  src="../js/side_bar.js"></script>
   

  <!-- delete popup start -->
  <script>
    let popupdel = document.getElementById("popupdel");

    function openconfirmDelete(){
        popupdel.classList.add("open-confirmDelete")
    }
    function closeDelete(){
        popupdel.classList.remove("open-confirmDelete")
    }
</script>
<!-- delete popup ending -->
  

     
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
 

  // Get references to the checkboxes and the "Select All" checkbox
const selectAllCheckbox = document.getElementById('select-all');
const checkboxes = document.querySelectorAll('input[name="selected_posts[]"]');

// Add event listener to the "Select All" checkbox
selectAllCheckbox.addEventListener('change', function () {
  checkboxes.forEach((checkbox) => {
    checkbox.checked = selectAllCheckbox.checked;
  });
});

// Add event listener to other checkboxes
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', function () {
    // Check if any checkbox is unchecked
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
             const maxLength = 30; // Set the maximum length for truncation
         
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

    // Get the tag value from the query parameter
const urlParams = new URLSearchParams(window.location.search);
const selectedTagParam = urlParams.get('tag');

// Set the selected tag in the dropdown
if (selectedTagParam) {
    document.getElementById('tag-filter').value = selectedTagParam;
} 

}

</script>
      
   </body>
</html>