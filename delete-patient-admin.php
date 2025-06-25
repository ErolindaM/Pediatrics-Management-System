<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($patient_id > 0) {
    try {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Patient deleted successfully";
        } else {
            $_SESSION['error_message'] = "No patient found with that ID";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting patient: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "Invalid patient ID";
}

// Redirect back to patients page
header("Location: manage-patients.php");
exit();
?>