<?php
session_start();

if (!isset($_SESSION['username'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: login.php?redirect=$current_page");
    exit;
}

$username = $_SESSION['username'] ?? null;
include 'includes/functions/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user = null;
$error = null;

try {
    // Get user data
    $sql = "SELECT u.username, u.email, r.name as role 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Query failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("User not found");
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Profile Error: " . $e->getMessage());
    $error = "Error loading profile data";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f4f6f9;
        }
        .content h1{
            font-size:32px;
        }
        .profile-card {
            width:400px;
            margin: 30px auto;
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-card >div {
            font-size:16px;
        }
        .btn-primary{
            font-size:16px;
            height:40px;
            padding:0;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: block;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            background: #eee;
        }
        div{
            font-size:18px;
        }
        h1{
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        h3{
            font-size:20px;
        }
        .profile-paragraph{
                color:#666;
                padding-bottom:10px;
                font-size:16px;
                margin-top:0px;
                text-align:center;
            }
        .footer{
            height:55px;
            padding-top:30px;
            position:fixed;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
        .btn-primary{
            padding:10px
        }
        .btn-secondary{
            padding:10px;
        }
        .modal-title{
            font-size:16px;
        }
        .form-label{
            font-size:16px;
        }
        @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:30px;
                padding-left:70px;
                padding-top:20px;
            }
            .content h1{
                font-size:24px;
            }
            .profile-paragraph{
                font-size:14px;
            }
            .profile-card{
                width:250px;
                margin:0;
            }
            .profile-card h3{
                font-size:16px;
            }
            .profile-card div{
                font-size:16px;
            }
            .btn-primary{
                height:30px;
                font-size:12px;
                padding:0px;
            }
            .profile-img {
                width: 100px;
                height: 100px;
            }
            
        }
    </style>
</head>
<body>
    <?php
        $role = strtolower($user['role']);

        switch ($role) {
            case 'admin':
                include "includes/templates/admin-sidebar.php";
                break;
            case 'doctor':
                include "includes/templates/doctor-sidebar.php";
                break;
            default:
                include "includes/templates/sidebar.php";
                break;
        }
        ?>

    <div class="content">
            <div class="container" style="flex-direction:column;justify-content:center;align-items:center">
                <h1 class="ml-0 text-center">Your profile</h1>
                <p class="profile-paragraph">Here you can view your email and username, and change your password.</p>

                <div class="profile-card">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <img src="https://www.pngall.com/wp-content/uploads/5/User-Profile-PNG-Image.png" class="profile-img" alt="Profile Picture">
                    
                    <?php if ($user): ?>
                        <h3 class="text-center mb-4"><?= htmlspecialchars($user['username']) ?></h3>
                        <div class="mb-3">
                            <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                        </div>
                        <div class="mb-4">
                            <strong>Role:</strong> <?= htmlspecialchars($user['role']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        Change Password
                    </button>
                </div>
            </div>

            <!-- Password Change Modal -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="passwordForm" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Change Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required 
                                        autocomplete="current-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" required 
                                        autocomplete="new-password" id="newPassword">
                                    <div class="password-strength" id="passwordStrength"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required 
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <div class="footer">
            <p>&copy; Copyright 2025 All rights reserved by KIDS CARE</p>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("passwordForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const newPassword = formData.get('new_password');
        const confirmPassword = formData.get('confirm_password');
        
        // Client-side validation
        if (newPassword !== confirmPassword) {
            Swal.fire('Error', 'New passwords do not match', 'error');
            return;
        }
        
        if (newPassword.length < 8) {
            Swal.fire('Error', 'Password must be at least 8 characters', 'error');
            return;
        }
        
        try {
            const response = await fetch('update-password.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire('Success', result.message, 'success').then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                    modal.hide();
                    this.reset();
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'An error occurred while updating password', 'error');
        }
    });

    // Password strength indicator
    document.getElementById('newPassword').addEventListener('input', function() {
        const strengthBar = document.getElementById('passwordStrength');
        const strength = calculateStrength(this.value);
        
        strengthBar.style.width = strength + '%';
        strengthBar.style.backgroundColor = 
            strength < 40 ? '#ff4d4d' : 
            strength < 70 ? '#ffa64d' : '#4CAF50';
    });
    
    function calculateStrength(password) {
        let strength = 0;
        if (password.length > 7) strength += 30;
        if (password.match(/[A-Z]/)) strength += 20;
        if (password.match(/[0-9]/)) strength += 20;
        if (password.match(/[^A-Za-z0-9]/)) strength += 30;
        return Math.min(strength, 100);
    }
</script>
<script src="main.js"></script>

</body>
</html>