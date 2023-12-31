<?php 
// Check if the session is not set (user is not logged in)
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
include 'logoutpopup.php';  
?>

<!-- partial:index.partial.html -->
<div class="layout has-sidebar fixed-sidebar fixed-header ">
<aside id="sidebar" class="sidebar">
   <a id="btn-collapse" class="sidebar-collapser"><i class="ri-arrow-left-s-line"></i></a>
   <!-- <div class="image-wrapper">
      <img src="assets/images/sidebar-bg.jpg" alt="sidebar background" />
   </div> -->
   <div class="sidebar-layout">

      <div class="sidebar-header">
      
      <div class="pro-sidebar-logo">
            <div>
            <img src="./system-images/iskolarosa-logo.png" style="width: 45px; height: 45px;" alt="sidebar background" />
            </div>
            <h5>iSKOLAROSA</h5>
         </div>
      </div>
      <div class="sidebar-content">
         <nav class="menu open-current-submenu">
            <ul>
            <li class="menu-item">
                <a href="dashboard_charts.php" <?php if ($currentPage === 'dashboard') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-bar-chart-2-fill"></i>
                </span>
                <span class="menu-title">Dashboard</span>
                </a>
             </li>

               <li class="menu-item sub-menu">
                  <a href="#" <?php if ($currentPage === 'configuration') echo 'class="active"'; ?>>
                  <span class="menu-icon ">
                  <i class="ri-list-settings-fill"></i>
                  </span>
                  <span class="menu-title ">Configuration</span>
                  </a>
                  <div class="sub-menu-list">
                     <ul>
                        <li class="menu-item">
                           <a href="ceap_configuration.php" <?php if ($currentSubPage === 'application') echo 'class="active"'; ?>>
                        <i class="ri-layout-6-fill" style="margin-right: 5px;"></i>
                           <span class="menu-title">Application Configuration</span>

                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="employee_configuration.php" <?php if ($currentSubPage === 'employee') echo 'class="active"'; ?>>
                           <i class="ri-pass-pending-fill" style="margin-right: 5px;"></i>
                           <span class="menu-title">Employee Configuration</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>

           
               <li class="menu-item sub-menu">
                  <a href="#" <?php if ($currentPage === 'post') echo 'class="active"'; ?>>
                  <span class="menu-icon">
                  <i class="ri-article-fill"></i>
                  </span>
                  <span class="menu-title">Post</span>
                  </a>
                  <div class="sub-menu-list">
                     <ul>
                        <li class="menu-item">
                           <a href="create_post.php" <?php if ($currentSubPage === 'create post') echo 'class="active"'; ?>>
                           <i class="ri-edit-2-fill" style="margin-right: 5px;"></i>
                           <span class="menu-title">Create Post</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="manage_post.php" <?php if ($currentSubPage === 'manage post') echo 'class="active"'; ?>>
                           <i class="ri-chat-settings-fill" style="margin-right: 5px;"></i>
                           <span class="menu-title">Manage Post</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
                
           <li class="menu-item sub-menu">
                  <a href="#" <?php if ($currentPage === 'ceap_list') echo 'class="active"'; ?>>
                  <span class="menu-icon">
                  <i class="ri-graduation-cap-fill" style="color: #FF0006;"></i>
                  </span>
                  <span class="menu-title">CEAP</span>
                  </a>
                  <div class="sub-menu-list">
                     <ul>
                        <li class="menu-item">
                           <a href="ceap_list.php" <?php if ($currentSubPage === 'new applicant') echo 'class="active"'; ?>>
                           <i class="ri-award-fill" style="margin-right: 5px;"></i>
                           <span class="menu-title">New Applicant</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="old_ceap_list.php" <?php if ($currentSubPage === 'old applicant') echo 'class="active"'; ?>>
                           <i class="ri-award-line" style="margin-right: 5px;"></i>
                           <span class="menu-title">Old Applicant</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
             <li class="menu-item">
                <a href="lppp_list.php" <?php if ($currentPage === 'lppp_list') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-graduation-cap-fill" style="color: #FEC021;"></i>
                </span>
                <span class="menu-title">LPPP</span>
                </a>
             </li>
             <li class="menu-item">
                <a href="archive_list.php" <?php if ($currentPage === 'archive') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-inbox-archive-fill"></i>
                </span>
                <span class="menu-title">Archive</span>
                </a>
             </li>
             <li class="menu-item">
                <a href="logs.php" <?php if ($currentPage === 'logs') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-history-fill"></i>
                </span>
                <span class="menu-title">Logs</span>
                </a>
             </li>
               <li class="menu-item" style="margin-top: 100px !important;">
               <a href="#" onclick="openMainLogoutPopup()">
        <span class="menu-icon">
            <i class="ri-logout-box-fill"></i>
        </span>
    <span class="username">
        <?php
        if (isset($_SESSION['username'])) {
            echo  $_SESSION['username'];
        }
        ?>
    </span>
</li>
            </ul>
         </nav>
      </div>
   </div>
</aside>
<div id="overlay" class="overlay"></div>
<div class="layout">
<main class="content">
<div>
<a id="btn-toggle" href="#" class="sidebar-toggler break-point-sm">
<i class="ri-menu-line ri-xl"></i>
</a>


<script>
        // Set the session timeout duration in seconds
        const sessionTimeoutSeconds = 300;

        // Function to check session timeout
        function checkSessionTimeout() {
            const lastActivityTime = localStorage.getItem('lastActivityTime');

            if (lastActivityTime) {
                const currentTime = new Date().getTime();
                const elapsedTime = currentTime - parseInt(lastActivityTime, 10);
                const timeoutMilliseconds = sessionTimeoutSeconds * 1000;

                if (elapsedTime > timeoutMilliseconds) {
                    // Redirect to logout.php when the session times out
                    window.location.href = 'logout.php';
                }
            }
        }

        // Function to update last activity time in local storage
        function updateLastActivityTime() {
            localStorage.setItem('lastActivityTime', new Date().getTime().toString());
        }

        // Add event listeners for various user interactions
        document.addEventListener('mousemove', updateLastActivityTime);
        document.addEventListener('keydown', updateLastActivityTime);

        // Check session timeout on an interval
        setInterval(checkSessionTimeout, 300000); // Check every second (adjust as needed)
    </script>

<script>
        // Function to update last activity time in the database
        function AJXupdateLastActivityTime() {
            // Use AJAX or fetch to send a request to the server to update last_activity
            // Example using fetch:
            fetch('./php/update_last_activity.php', {
               method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to update last activity time. Status: ${response.status}`);
                }
                return response.json(); // If the server sends JSON in the response
            })
            .catch(error => {
                console.error(error);
            });
        }

        // Add event listeners for various user interactions
        document.addEventListener('mousemove', AJXupdateLastActivityTime);
        document.addEventListener('keydown', AJXupdateLastActivityTime);
    </script>