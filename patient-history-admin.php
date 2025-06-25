<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$patient_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($patient_id <= 0) {
    echo "Invalid patient ID.";
    exit();
}

// Merr të dhënat e pacientit
$stmt = $conn->prepare("SELECT p.*, u.username AS doctor_username FROM patients p JOIN users u ON p.doctor_id = u.id WHERE p.id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No patient found.";
    exit();
}

$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient History</title>
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background-color: #f5f7fa; font-family: sans-serif; }
        .container {
            background: white;
            margin: 50px auto;
            padding: 30px;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; }
        .btn-back {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .info { margin-bottom: 15px; font-size: 18px; }
    </style>
</head>
<body>
<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="container">
    <h2>Patient History: <?= htmlspecialchars($patient['name'] . ' ' . $patient['lastname']) ?></h2>

    <div class="info"><strong>Doctor:</strong> <?= htmlspecialchars($patient['doctor_username']) ?></div>
    <div class="info"><strong>Phone:</strong> <?= htmlspecialchars($patient['phone']) ?></div>
    <div class="info"><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?></div>
    <div class="info"><strong>Date of Birth:</strong> <?= htmlspecialchars($patient['date_of_birth']) ?></div>
    <div class="info"><strong>Diagnosis:</strong> <?= htmlspecialchars($patient['diagnosis']) ?></div>
    <!-- MUND të shtosh më shumë detaje ose një tabelë me histori klinike nëse ka -->

    <a href="manage-patients.php" class="btn-back">← Back to Patients</a>
</div>

</body>
</html>
