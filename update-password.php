<?php
session_start();
header('Content-Type: application/json');
include 'includes/functions/connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$current = $_POST['current_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

// Validate password requirements (same as registration)
if (strlen($new) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit;
}

if ($new !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'The passwords do not match']);
    exit;
}

// Get current password from database
$sql = "SELECT password FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Verify current password
if (md5($current) !== $row['password']) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

// Update password (using MD5 to match your system)
$new_hashed = md5($new);
$update_sql = "UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'";

if (mysqli_query($conn, $update_sql)) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating password: ' . mysqli_error($conn)]);
}
?>