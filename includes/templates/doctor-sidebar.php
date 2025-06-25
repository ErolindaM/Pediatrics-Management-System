<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
$patient_pages = ['patients.php', 'edit-patient.php', 'add-patient.php','add-medical-history.php'];
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
        <a href="doctor-dashboard.php" class="menu-item <?= ($current_page == 'doctor-dashboard.php') ? 'active' : '' ?>">Home</a>
        <a href="doctor-appointments.php" class="menu-item <?= ($current_page == 'doctor-appointments.php') ? 'active' : '' ?>">Appointments</a>
        <a href="patients.php" class="menu-item <?= in_array($current_page, $patient_pages) ? 'active' : '' ?>">Patients</a>
        <a href="profile.php" class="menu-item <?= ($current_page == 'profile.php') ? 'active' : '' ?>">My Profile</a>
    </div>
    <div class="logout">
        <?php if (isset($username)): ?>
            <a href="logout.php" class="menu-item <?= ($current_page == 'logout.php') ? 'active' : '' ?>">Log out</a>
        <?php else: ?>
            <a href="login.php" class="menu-item <?= ($current_page == 'login.php') ? 'active' : '' ?>">Log in</a>
        <?php endif; ?>
    </div>
</div>