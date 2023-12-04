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
</div>
<!-- Left Column -->
<div class="left-column">
<ul>
         <span class="menu-icon">
         <i class="ri-building-2-fill"></i></span>
         <span class="menu-title h2">BARANGAY</span>


      <li class="menu-item">
         <a href="../APLAYA/old_ceap_list.php">
         <?php if ($currentBarangay === 'aplaya') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <span class="menu-title">APLAYA</span>
         </a>
      </li>
      <li class="menu-item">
         <a href="../BALIBAGO/old_ceap_list.php">
         <?php if ($currentBarangay === 'BALIBAGO') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <span class="menu-title">BALIBAGO</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'caingin') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../CAINGIN/old_ceap_list.php">
         <span class="menu-title">CAINGIN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'dila') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DILA/old_ceap_list.php">
         <span class="menu-title">DILA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'dita') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DITA/old_ceap_list.php">
         <span class="menu-title">DITA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'don jose') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../DONJOSE/old_ceap_list.php">
         <span class="menu-title">DON JOSE</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'ibaba') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../IBABA/old_ceap_list.php">
         <span class="menu-title">IBABA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'kanluran') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../KANLURAN/old_ceap_list.php">
         <span class="menu-title">KANLURAN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'labas') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../LABAS/old_ceap_list.php">
         <span class="menu-title">LABAS</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'macabling') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MACABLING/old_ceap_list.php">
         <span class="menu-title">MACABLING</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'malitlit') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MALITLIT/old_ceap_list.php">
         <span class="menu-title">MALITLIT</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'malusak') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MALUSAK/old_ceap_list.php">
         <span class="menu-title">MALUSAK</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'market area') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../MARKETAREA/old_ceap_list.php">
         <span class="menu-title">MARKET AREA</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'pooc') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../POOC/old_ceap_list.php">
         <span class="menu-title">POOC</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'pulong santa cruz') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../PULONGSANTACRUZ/old_ceap_list.php">
         <span class="menu-title">PULONG SANTA CRUZ</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'santo domingo') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../SANTODOMINGO/old_ceap_list.php">
         <span class="menu-title">SANTO DOMINGO</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'sinalhan') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../SINALHAN/old_ceap_list.php">
         <span class="menu-title">SINALHAN</span>
         </a>
      </li>
      <li class="menu-item">
      <?php if ($currentBarangay === 'tagapo') : ?>
               <i class="ri-arrow-right-s-fill" style="color: #A5040A;"></i> <!-- Display the icon when the menu item is active -->
            <?php endif; ?>
         <a href="../TAGAPO/old_ceap_list.php">
         <span class="menu-title">TAGAPO</span>
         </a>
      </li>
   </ul>
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
            <a class="nav-link status <?php if ($currentStatus === 'in progress')  echo 'active'; ?>" href="old_ceap_list.php">IN PROGRESS</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'disqualified') echo 'active'; ?>" href="old_ceap_list_disqualify.php">DISQUALIFIED</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'verified') echo 'active'; ?>" href="old_ceap_list_verified.php">VERIFIED</a>
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
            <a class="nav-link status <?php if ($currentStatus === 'interview') echo 'active'; ?>" href="old_ceap_list_interview.php">TO INTERVIEW</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'grantee') echo 'active'; ?>" href="old_ceap_list_grantee.php">GRANTEE</a>
            </strong>
            </button>
         </li>
         <li class="nav-item">
            <button>
            <strong>
            <a class="nav-link status <?php if ($currentStatus === 'fail') echo 'active'; ?>" href="old_ceap_list_fail.php">NOT GRANTEE</a>
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
            fetch('../../../php/update_last_activity.php', {
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