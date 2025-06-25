<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['history_id'])) {
    $history_id = intval($_POST['history_id']);

    $stmt = $conn->prepare("DELETE FROM medical_history WHERE id = ?");
    $stmt->bind_param("i", $history_id);

    if ($stmt->execute()) {
        header("Location: manage-patients.php");
        exit();
    } else {
        echo "Failed to delete medical history.";
    }
} else {
    header("Location: manage-patients.php");
    exit();
}
?>