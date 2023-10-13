<?php
// Check if the session is not set (user is not logged in)
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

include '../../admin-side/php/config_iskolarosa_db.php';

$query = "SELECT toggle_value FROM ceap_configuration ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $toggleValue = $row['toggle_value'];
}
?>

<nav class="topnav" id="myTopnav">
<a href="#" class="logo">
  <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="Logo">
</a>
<a href="#" class="brand">iSKOLAROSA</a>
<div class="right-links">
  <a href="home_page_log_in.php" class="<?php if ($currentPage === 'home_page_log_in') echo 'actives'; ?>">HOME</a>
  <a href="personal_account_status.php" class="<?php if ($currentPage === 'personal_account_status') echo 'actives'; ?>">STATUS</a>
  <a href="personal_account_quickguide.php" class="<?php if ($currentPage === 'personal_account_quickguide') echo 'actives'; ?>">QUICK GUIDE</a>
  <?php if ($toggleValue == 0): ?>
  <div class="dropdown-content" id="userDropdownContent">
        <a href="personal_account_change_password.php">Password</a>
        <a href="../php/logout.php">Logout</a>
      </div>
      <a><i class="fas fa-user-graduate graduation-icon" id="userDropdownToggle"></i></a>
  <?php elseif ($toggleValue == 1): ?>
      <div class="dropdown-content" id="userDropdownContent">
        <a href="personal_account_profile.php" class="<?php if ($currentPage === 'personal_account_profile') echo 'actives'; ?>">Profile</a>
        <a href="personal_account_change_password.php">Password</a>
        <a href="../php/logout.php">Logout</a>

      </div>
      <a> <i class="fas fa-user-graduate graduation-icon" id="userDropdownToggle"></i></a>
  <?php endif; ?>
</div>
<a href="javascript:void(0);" class="icon" id="hamburger" onclick="toggleMenu()">
  <i class="ri-menu-line"></i>
</a>
</nav>

<div id="menu" class="menu">
  <a href="home_page_log_in.php" class="<?php if ($currentPage === 'home_page_log_in') echo 'actives'; ?>">HOME</a>
  <a href="personal_account_status.php" class="<?php if ($currentPage === 'personal_account_status') echo 'actives'; ?>">STATUS</a>
  <a href="personal_account_quickguide.php" class="<?php if ($currentPage === 'personal_account_quickguide') echo 'actives'; ?>">QUICK GUIDE</a>
  <?php if ($toggleValue == 0): ?>
    <div class="dropdown-content" id="userDropdownContent">
        <a href="personal_account_change_password.php">Password</a>
        <a href="logout.php">Logout</a>
      </div>
      <a> <i class="fas fa-user-graduate graduation-icon" id="userDropdownToggle"></i></a>
  <?php elseif ($toggleValue == 1): ?>
      <div class="dropdown-content" id="userDropdownContent">
        <a href="personal_account_profile.php">Profile</a>
        <a href="personal_account_change_password.php">Password</a>
        <a href="logout.php">Logout</a>
      </div>
      <a> <i class="fas fa-user-graduate graduation-icon" id="userDropdownToggle"></i></a>
    <?php endif; ?>
</div>
<script>
    // Get the elements
    const userDropdownToggle = document.getElementById("userDropdownToggle");
    const userDropdownContent = document.getElementById("userDropdownContent");
    
    // Toggle the dropdown content on click
    userDropdownToggle.addEventListener("click", function () {
        userDropdownContent.classList.toggle("show");
    });
    
    // Close the dropdown if the user clicks outside of it
    window.addEventListener("click", function (event) {
        if (!event.target.matches("#userDropdownToggle")) {
            if (userDropdownContent.classList.contains("show")) {
                userDropdownContent.classList.remove("show");
            }
        }
    });
</script>