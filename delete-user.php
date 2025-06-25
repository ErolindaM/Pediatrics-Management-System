<?php
session_start();

// Kontrollo nëse përdoruesi është i loguar dhe është admin
if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

// Merr ID-në e përdoruesit për të fshirë
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kontrollo nëse ID është e vlefshme
if ($user_id > 0) {
    try {
        // Fshi përdoruesin vetëm nëse nuk është admin (p.sh., role_id = 3 për pacientë)
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role_id = 3");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Kontrollo nëse është fshirë ndonjë rekord
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Përdoruesi u fshi me sukses";
        } else {
            $_SESSION['error_message'] = "Përdoruesi nuk u gjet ose nuk ka të drejta";
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Gabim gjatë fshirjes: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "ID e pavlefshme";
}

// Ridrejto pas përfundimit
header("Location: manage-users.php");
exit();
?>