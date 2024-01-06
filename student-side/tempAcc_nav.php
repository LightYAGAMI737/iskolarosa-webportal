<?php
// Check if a session is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../admin-side/php/config_iskolarosa_db.php';

// Check if the session is not set (user is not logged in)
if (!isset($_SESSION['control_number'])) {
    // Redirect to the home page for non-logged-in users
    header("Location: ../home-page-two/home-page.php");
    exit();
}

// If logged in, redirect to otherhome page
$homeLink = "home-page-loggedin.php";

$currentFile = basename($_SERVER['PHP_SELF']);

// Set 'active' class based on the current file
$homeClass = ($currentFile == 'home-page-loggedin.php') ? 'active' : '';
$statusClass = ($currentFile == 'tempAcc_status.php') ? 'active' : '';
$guideClass = ($currentFile == 'tempAcc_quickguide.php') ? 'active' : '';
$contactClass = ($currentFile == 'home-page-contact.php') ? 'active' : '';
$logoutClass = ($currentFile == 'logout.php') ? 'active' : '';
?>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../admin-side/system-images/iskolarosa-logo.png" alt="My Logo" class="img-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $homeClass; ?>">
                    <a class="nav-link" href="<?php echo $homeLink; ?>">Home</a>
                </li>
                <li class="nav-item <?php echo $statusClass; ?>">
                    <a class="nav-link" href="tempAcc_status.php">Status</a>
                </li>
                <li class="nav-item <?php echo $contactClass; ?>">
                    <a class="nav-link" href="home-page-contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $logoutClass; ?>" href="./php/logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>