<?php 
// Check if the session is not set (user is not logged in)
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
include 'logoutpopup.php';  
?>

<!-- partial:index.partial.html -->
<div class="layout has-sidebar fixed-sidebar fixed-header ">
<aside id="sidebar" class="sidebar break-point-sm has-bg-image">
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
                  <i class="ri-settings-3-fill"></i>

                  </span>
                  <span class="menu-title ">Configuration</span>
                  </a>
                  <div class="sub-menu-list">
                     <ul>
                        <li class="menu-item">
                           <a href="ceap_configuration.php" <?php if ($currentSubPage === 'application') echo 'class="active"'; ?>>
                           <span class="menu-title">Application Configuration</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="employee_configuration.php" <?php if ($currentSubPage === 'employee') echo 'class="active"'; ?>>
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
                           <span class="menu-title">Create Post</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="manage_post.php" <?php if ($currentSubPage === 'manage post') echo 'class="active"'; ?>>
                           <span class="menu-title">Manage Post</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
                
             <li class="menu-item">
                <a href="ceap_list.php" <?php if ($currentPage === 'ceap_list') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-user-search-fill"></i>
                </span>
                <span class="menu-title">CEAP</span>
                </a>
             </li>
             <li class="menu-item">
                <a href="lppp_list.php" <?php if ($currentPage === 'lppp_list') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-user-search-fill"></i>
                </span>
                <span class="menu-title">LPPP</span>
                </a>
             </li>
             <li class="menu-item">
                <a href="archive_list.php" <?php if ($currentPage === 'archive') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-inbox-archive-fill"></i>
                </span>
                <span class="menu-title">ARCHIVE</span>
                </a>
             </li>
             <li class="menu-item">
                <a href="logs.php" <?php if ($currentPage === 'logs') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-file-list-2-fill"></i>
                </span>
                <span class="menu-title">LOGS</span>
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