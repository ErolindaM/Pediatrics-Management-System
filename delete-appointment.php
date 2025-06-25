<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

// Get the appointment ID to delete
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id > 0) {
    try {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Appointment deleted successfully";
        } else {
            $_SESSION['error_message'] = "No appointment found with that ID";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting appointment: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "Invalid appointment ID";
}

// Redirect back to appointments page
header("Location: manage-appointments.php");
exit();
?>