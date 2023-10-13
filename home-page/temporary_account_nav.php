<?php
// Check if the session is not set (user is not logged in)
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

?>
<nav class="topnav" id="myTopnav">
  <a href="#" class="logo">
    <img src="../admin-side/system-images/iskolarosa-logo.png" alt="Logo">
  </a>
  <a href="#" class="brand">iSKOLAROSA</a>
  <div class="right-links">
    <a href="home_page.php" class="<?php if ($currentPage === 'home_page') echo 'actives'; ?>">HOME</a>
    <a href="quickguide.php" class =" <?php if ($currentPage === 'quickguide') echo 'actives'; ?>">QUICK GUIDE</a>
    <a href="../student-side/index.php" class="logout">LOG IN</a>
  </div>
  <a href="javascript:void(0);" class="icon" id="hamburger" onclick="toggleMenu()">
    <i class="ri-menu-line"></i>
  </a>
</nav>
<div id="menu" class="menu">
  <a href="home_page.php" class="<?php if ($currentPage === 'home_page') echo 'actives'; ?>">HOME</a>
  <a href="quickguide.php" class ="<?php if ($currentPage === 'quickguide') echo 'actives'; ?>">QUICK GUIDE</a>
  <a href="../student-side/index.php" class="logout">LOG IN</a>
</div>
