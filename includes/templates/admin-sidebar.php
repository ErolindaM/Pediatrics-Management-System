<?php
$current_page = basename($_SERVER['PHP_SELF']); 
$manage_users_pages = ['manage-users.php', 'edit-user.php', 'add-user.php'];
$manage_doctors_pages = ['manage-doctors.php', 'edit-doctor.php', 'add-doctor.php'];
$manage_patients_pages = ['manage-patients.php', 'edit-patient-admin.php', 'add-patient-admin.php','add-medical-history-admin.php'];
$manage_appointments_pages = ['manage-appointments.php', 'edit-appointment.php', 'add-appointment.php'];

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
        <a href="admin-dashboard.php" class="menu-item <?= ($current_page == 'admin-dashboard.php') ? 'active' : '' ?>">Home</a>
        <a href="manage-users.php" class="menu-item <?= in_array($current_page, $manage_users_pages) ? 'active' : '' ?>">Users</a>
        <a href="manage-doctors.php" class="menu-item <?= in_array($current_page, $manage_doctors_pages) ? 'active' : '' ?>">Doctors</a>
        <a href="manage-messages.php" class="menu-item <?= ($current_page == 'manage-messages.php') ? 'active' : '' ?>">Messages</a>
        <a href="manage-feedbacks.php" class="menu-item <?= ($current_page == 'manage-feedbacks.php') ? 'active' : '' ?>">Feedbacks</a>
        <a href="manage-appointments.php" class="menu-item <?= in_array($current_page, $manage_appointments_pages) ? 'active' : '' ?>">Appointments</a>
        <a href="manage-patients.php" class="menu-item <?= in_array($current_page, $manage_patients_pages) ? 'active' : '' ?>">Patients</a>
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