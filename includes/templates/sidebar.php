<?php 
// Get the current page filename (e.g., "index.php")
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<div class="sidebar">
    <button class="menu-toggle">☰</button>
    <div class="sidebar-header">
        <div class="user-icon">
            <img src="images/user.png" alt="User">
        </div>
        <div class="user-name">
            <?php echo isset($username) ? htmlspecialchars($username) : 'Guest'; ?>
        </div>
    </div>
    <div class="menu">
        <a href="index.php" class="menu-item <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
        <a href="about-us.php" class="menu-item <?= ($current_page == 'about-us.php') ? 'active' : '' ?>">About us</a>
        <a href="doctors.php" class="menu-item <?= ($current_page == 'doctors.php') ? 'active' : '' ?>">Doctors</a>
        <a href="gallery.php" class="menu-item <?= ($current_page == 'gallery.php') ? 'active' : '' ?>">Gallery</a>
        <a href="feedback.php" class="menu-item <?= ($current_page == 'feedback.php') ? 'active' : '' ?>">Feedback</a>
        <a href="contact.php" class="menu-item <?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a>
        <a href="appointments.php" class="menu-item <?= ($current_page == 'appointments.php') ? 'active' : '' ?>">Appointments</a>
         <?php if (isset($username)): ?>
            <a href="profile.php" class="menu-item <?= ($current_page == 'profile.php') ? 'active' : '' ?>">My Profile</a>
        <?php endif; ?>
    </div>
    <div class="logout">
        <?php if (isset($username)): ?>
            <a href="logout.php" class="menu-item <?= ($current_page == 'logout.php') ? 'active' : '' ?>">Log out</a>
        <?php else: ?>
            <a href="login.php" class="menu-item <?= ($current_page == 'login.php') ? 'active' : '' ?>">Log in</a>
        <?php endif; ?>
    </div>
</div>