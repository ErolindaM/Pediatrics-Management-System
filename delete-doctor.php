<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$doctor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($doctor_id > 0) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role_id = 2");
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Doctor deleted successfully";
        } else {
            $_SESSION['error_message'] = "No doctor found or you don't have permission";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting doctor: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "Invalid doctor ID";
}

header("Location: manage-doctors.php");
exit();
?>