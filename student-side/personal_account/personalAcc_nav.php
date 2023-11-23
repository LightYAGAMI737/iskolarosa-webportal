<?php
// Check if a session is not already active
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// include '../../admin-side/php/config_iskolarosa_db.php';

// // Check if the session is not set (user is not logged in)
// if (!isset($_SESSION['control_number'])) {
//     // Redirect to the home page for non-logged-in users
//     header("Location: ../home-page-two/home-page.php");
//     exit();
// }

// If logged in, redirect to otherhome page
$homeLink = "personalAcc_home.php";

$currentFile = basename($_SERVER['PHP_SELF']);

// Set 'active' class based on the current file
$homeClass = ($currentFile == 'personalAcc_home.php') ? 'active' : '';
$statusClass = ($currentFile == 'personalAcc_status.php') ? 'active' : '';
$guideClass = ($currentFile == 'personalAcc_quickguide.php') ? 'active' : '';
$contactClass = ($currentFile == 'contact_us.php') ? 'active' : '';
$profileClass = ($currentFile == 'personalAcc_profile.php') ? 'active' : '';
$changepassClass = ($currentFile == 'personalAcc_changepass.php') ? 'active' : '';
$logoutClass = ($currentFile == 'logout.php') ? 'active' : '';
?>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../../admin-side/system-images/iskolarosa-logo.png" alt="My Logo" class="img-logo">
        </a>
        <a href="#" class="brand">iSKOLAROSA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo $homeClass; ?>" href="<?php echo $homeLink; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $statusClass; ?>" href="personalAcc_status.php">Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $guideClass; ?>" href="personalAcc_quickguide.php">Quick Guide</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $contactClass; ?>" href="#">Contact Us</a>
                </li>
                <li class="nav-item"> <!--for logout and other options-->
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-graduation-cap-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?php echo $profileClass; ?>" href="personalAcc_profile.php">
                                <i class="ri-graduation-cap-fill"></i>Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $changepassClass; ?>" href="personalAcc_changepass.php">
                                <i class="ri-lock-password-fill"></i>Change Password</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="../php/logout.php">
                                <i class="ri-logout-box-fill"></i>Log Out</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>