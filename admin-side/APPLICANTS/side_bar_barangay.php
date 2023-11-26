<?php 
   // Check if the session is not set (user is not logged in)
   if (session_status() == PHP_SESSION_NONE) {
      session_start();
   }
   
   include '../../../php/logoutpopup.php';  
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
            <img src="../../../system-images/iskolarosa-logo.png" style="width: 45px; height: 45px;" alt="sidebar background" />
            </div>
            <h5>iSKOLAROSA</h5>
         </div>
      </div>
      <div class="sidebar-content">
         <nav class="menu open-current-submenu">
            <ul>
               <li class="menu-item">
                  <a href="../../../dashboard_charts.php" <?php if ($currentPage === 'dashboard') echo 'class="active"'; ?>>
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
                  <a href="../../../ceap_configuration.php" <?php if ($currentPage === 'configuration') echo 'class="active"'; ?>>
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
                           <a href="../../../create_post.php" <?php if ($currentSubPage === 'create post') echo 'class="active"'; ?>>
                           <span class="menu-title">Create Post</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../../manage_post.php" <?php if ($currentSubPage === 'manage post') echo 'class="active"'; ?>>
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
                           <a href="./ceap_list.php" <?php if ($currentSubPage === 'new applicant') echo 'class="active"'; ?>>
                           <span class="menu-title">New Applicant</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="./old_ceap_list.php" <?php if ($currentSubPage === 'old applicant') echo 'class="active"'; ?>>
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
                           <a href="../../LPPP/GRADE-LEVEL/lppp_list.php" <?php if ($currentSubPage === 'GRADE 7') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 7</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../LPPP/GRADE-LEVEL/grade_8_list.php" <?php if ($currentSubPage === 'GRADE 8') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 8</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../LPPP/GRADE-LEVEL/grade_9_list.php" <?php if ($currentSubPage === 'GRADE 9') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 9</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../LPPP/GRADE-LEVEL/grade_10_list.php" <?php if ($currentSubPage === 'GRADE 10') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 10</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../LPPP/GRADE-LEVEL/grade_11_list.php" <?php if ($currentSubPage === 'GRADE 11') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 11</span>
                           </a>
                        </li>
                        <li class="menu-item">
                           <a href="../../LPPP/GRADE-LEVEL/grade_12_list.php" <?php if ($currentSubPage === 'GRADE 12') echo 'class="active"'; ?>>
                           <span class="menu-title">Grade 12</span>
                           </a>
                        </li>
                     </ul>
                  </div>
                  <?php } else if ($_SESSION['role'] !== 2) {
                     // Only show the 'Configuration' menu item for roles other than 'Staff'
                     ?>
               <li class="menu-item">
                  <a href="../../LPPP/GRADE-LEVEL/lppp_list.php" <?php if ($currentPage === 'lppp_list') echo 'class="active"'; ?>>
                  <span class="menu-icon">
                  <i class="ri-user-search-fill"></i>
                  </span>
                  <span class="menu-title">LPPP</span>
                  </a>
               </li>
               <?php
                  }
                  ?>
               <li class="menu-item <?php if ($_SESSION['role'] == 1) echo 'logout-staff'; elseif ($_SESSION['role'] == 2 || $_SESSION['role'] == 3) echo 'logout-btn'; ?>">
                  <a href="#" onclick="openApplicantLogoutPopup()">
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
                  </a>
               </li>
            </ul>
         </nav>
      </div>
   </div>
</aside>
<div id="overlay" class="overlay"></div>
<div class="layout">
<main class="content">
   
<div class="inside-main-content">
<div class="header-label post">
<H1>College Educational Assistance Program (CEAP)</H1>
<button class="dropdown-btn" onclick="toggleMenu()">
        Left Column Dropdown
    </button>
</div>


<!-- Left Column -->
<div class="left-column">


<div class="dropdown-content" id="menuItems">
   <ul>
         <span class="menu-icon">
         <i class="ri-building-2-fill"></i></span>
         <span class="menu-title h2">BARANGAY</span>


      <li class="menu-item">
         <a href="../APLAYA/ceap_list.php">
         <?php if ($currentBarangay === 'APLAYA') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <span class="menu-title">APLAYA</span>
         </a>
      </li>
      <li class="menu-item">
         <a href="../BALIBAGO/ceap_list.php">
         <?php if ($currentBarangay === 'BALIBAGO') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <span class="menu-title">BALIBAGO</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'CAINGIN') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../CAINGIN/ceap_list.php">
         <span class="menu-title">CAINGIN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'DILA') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DILA/ceap_list.php">
         <span class="menu-title">DILA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'DITA') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DITA/ceap_list.php">
         <span class="menu-title">DITA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'DON JOSE') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DONJOSE/ceap_list.php">
         <span class="menu-title">DON JOSE</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'IBABA') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../IBABA/ceap_list.php">
         <span class="menu-title">IBABA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'KANLURAN') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../KANLURAN/ceap_list.php">
         <span class="menu-title">KANLURAN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'LABAS') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../LABAS/ceap_list.php">
         <span class="menu-title">LABAS</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'MACABLING') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MACABLING/ceap_list.php">
         <span class="menu-title">MACABLING</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'MALITLIT') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MALITLIT/ceap_list.php">
         <span class="menu-title">MALITLIT</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'MALUSAK') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MALUSAK/ceap_list.php">
         <span class="menu-title">MALUSAK</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'MARKET AREA') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MARKETAREA/ceap_list.php">
         <span class="menu-title">MARKET AREA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'POOC') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../POOC/ceap_list.php">
         <span class="menu-title">POOC</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'PULONG SANTA CRUZ') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../PULONGSANTACRUZ/ceap_list.php">
         <span class="menu-title">PULONG SANTA CRUZ</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'SANTO DOMINGO') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../SANTODOMINGO/ceap_list.php">
         <span class="menu-title">SANTO DOMINGO</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'SINALHAN') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../SINALHAN/ceap_list.php">
         <span class="menu-title">SINALHAN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'TAGAPO') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../TAGAPO/ceap_list.php">
         <span class="menu-title">TAGAPO</span>
         </a>
      </li>
   </ul>
</div>
</div>
<a id="btn-toggle" href="#" class="sidebar-toggler break-point-sm">
<i class="ri-menu-line ri-xl"></i>
</a>
<div class="right-column">
<nav id="main-navigation" <?php if ($_SESSION['role'] === 1 ) echo 'class="staff-navigation"'; ?>>
   <div>
      <ul>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'In Progress')  echo 'active'; ?>" href="ceap_list.php">IN PROGRESS</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'Disqualified') echo 'active'; ?>" href="ceap_list_disqualify.php">DISQUALIFIED</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'Verified') echo 'active'; ?>" href="ceap_list_verified.php">VERIFIED</a>
            </strong>
            </button>
         </li>
         <?php
            if ($_SESSION['role'] !== 1) {
                // Only show these navigation items for roles other than 'Staff'
                ?>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'interview') echo 'active'; ?>" href="ceap_list_interview.php">TO INTERVIEW</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'Grantee') echo 'active'; ?>" href="ceap_list_grantee.php">GRANTEE</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'Fail') echo 'active'; ?>" href="ceap_list_fail.php">NOT GRANTEE</a>
            </strong>
            </button>
         </li>
         <?php
            }
            ?>
      </ul>
   </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // JavaScript function to toggle the visibility of menu items
    function toggleMenu(event) {
        var menu = document.querySelector(".left-column .dropdown-content"); // Updated selector
        var button = document.querySelector(".left-column .dropdown-btn"); // Updated selector
        
        if (menu.style.display === "block") {
            menu.style.display = "none";
            button.classList.remove("active");
        } else {
            menu.style.display = "block";
            button.classList.add("active");
        }
        
        // Prevent the default behavior of the button (e.g., following a link)
        event.preventDefault();
    }

    // Attach the click event handler to your button
    var button = document.querySelector(".left-column .dropdown-btn"); // Updated selector
    button.addEventListener("click", toggleMenu);
});

   </script>
