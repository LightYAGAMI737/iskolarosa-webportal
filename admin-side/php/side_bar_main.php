<?php 
// Check if the session is not set (user is not logged in)
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

?>
<!-- partial:index.partial.html -->

<div class="layout has-sidebar fixed-sidebar fixed-header ">
<aside id="sidebar" class="sidebar break-point-sm has-bg-image">
   <a id="btn-collapse" class="sidebar-collapser"><i class="ri-arrow-left-s-line"></i></a>
   <div class="image-wrapper">
      <img src="assets/images/sidebar-bg.jpg" alt="sidebar background" />
   </div>
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
               <?php
         if ($_SESSION['role'] !== 1) {
             // Only show the 'Configuration' menu item for roles other than 'Staff'
             ?>
             <li class="menu-item">
                <a href="./ceap_configuration.php" <?php if ($currentPage === 'configuration') echo 'class="active"'; ?>>
                <span class="menu-icon">
                <i class="ri-settings-3-fill"></i>
                </span>
                <span class="menu-title">Configuration</span>
                </a>
             </li>
             <?php
         }
         ?>
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
               <li class="menu-item sub-menu">
                  <a href="#" <?php if ($currentPage === 'ceap_list') echo 'class="active"'; ?>>
                  <span class="menu-icon">
                  <i class="ri-graduation-cap-fill"></i>
                  </span>
                  <span class="menu-title">CEAP</span>
                  </a>
                  <div class="sub-menu-list">
                     <ul>
                        <li class="menu-item">
                           <a href="../admin-side/APPLICANTS/BARANGAY/APLAYA/ceap_list.php" <?php if ($currentSubPage === 'new applicant') echo 'class="active"'; ?>>
                           <span class="menu-title">New Applicant</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../admin-side/APPLICANTS/BARANGAY/APLAYA/old_ceap_list.php" <?php if ($currentSubPage === 'old applicant') echo 'class="active"'; ?>>
                           <span class="menu-title">Old Applicant</span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
               <?php if ($_SESSION['role'] !== 1) { ?> 
               <li class="menu-item sub-menu">
    <a href="#" <?php if ($currentPage === 'lppp_list') echo 'class="active"'; ?>>
        <span class="menu-icon">
        <i class="ri-graduation-cap-fill"></i>

        </span>
        <span class="menu-title">LPPP</span>
    </a>
        <div class="sub-menu-list">
            <ul>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/lppp_list.php" <?php if ($currentSubPage === 'GRADE 7') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 7</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/grade_8_list.php" <?php if ($currentSubPage === 'GRADE 8') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 8</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/grade_9_list.php" <?php if ($currentSubPage === 'GRADE 9') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 9</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/grade_10_list.php" <?php if ($currentSubPage === 'GRADE 10') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 10</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/grade_11_list.php" <?php if ($currentSubPage === 'GRADE 11') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 11</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../admin-side/APPLICANTS/LPPP/grade_12_list.php" <?php if ($currentSubPage === 'GRADE 12') echo 'class="active"'; ?>>
                        <span class="menu-title">Grade 12</span>
                    </a>
                </li>
              
            </ul>
        </div>
    <?php } else if ($_SESSION['role'] !== 2) {
        // Only show the 'Configuration' menu item for roles other than 'Staff'
        ?>
        <li class="menu-item">
           <a href="../admin-side/APPLICANTS/LPPP/lppp_list.php" <?php if ($currentPage === 'lppp_list') echo 'class="active"'; ?>>
           <span class="menu-icon">
           <i class="ri-user-search-fill"></i>

           </span>
           <span class="menu-title">LPPP</span>
           </a>
        </li>
        <?php
    }
    ?>
</li>


<li class="menu-item <?php if ($_SESSION['role'] == 1) echo 'logout-staff'; elseif ($_SESSION['role'] == 2 || $_SESSION['role'] == 3) echo 'logout-btn'; ?>">
    <a href="./php/logout.php">
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