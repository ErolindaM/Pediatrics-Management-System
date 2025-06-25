<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$doctor_id = $_SESSION['user_id'];
$patient_id = intval($_GET['id']);

// Delete patient
$stmt = $conn->prepare("DELETE FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->bind_param("ii", $patient_id, $doctor_id);
$stmt->execute();
$stmt->close();

header("Location: patients.php");
exit();
?>