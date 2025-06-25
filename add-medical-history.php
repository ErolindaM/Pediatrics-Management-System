<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'];
$patient_id = $_GET['patient_id'] ?? null;

// Merr të dhënat e pacientit nga databaza
$stmt = $conn->prepare("SELECT name, lastname FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Patient not found.";
    exit();
}

$patient = $result->fetch_assoc();


if (!$patient_id) {
    echo "Patient ID is missing.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $diagnosis = $_POST['diagnosis'];
    $medications = $_POST['medications'];
    $notes = $_POST['notes'];

    // Ruaj në tabelën medical_history
    $stmt = $conn->prepare("INSERT INTO medical_history (patient_id, visit_date, visit_time, diagnosis, medications, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $patient_id, $visit_date, $visit_time, $diagnosis, $medications, $notes);

    if ($stmt->execute()) {
        header("Location: patients.php");
        exit();
    } else {
        echo "Error while saving: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
        }
        .container {
            max-width: 800px;
            justify-content:center;
            margin: 80px auto;
            margin-top:20px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        h1 { 
            font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        textarea {
            height: 100px;
        }
        button {
            background-color: #2196F3;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 17px;
        }
        button:hover {
            background-color: #0b7dda;
        }
        form{
            width:80%
        }
        .btn-cancel {
            background-color: #f44336;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-save{
             padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
         .btn-cancel:hover{
            background-color:rgb(217, 40, 27);
         }
         label{
            font-size:16px;
         }
        .patient-info {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .footer{
            height:63px;
            padding-top:20px;
        }
        .medical-paragraph{
            color: #666;
            padding:0 20px;
            font-size:16px;
            padding-bottom: 20px;
            text-align:center;
        }
        .footer{
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
         @media (max-width: 580px) {
            .content {
                left: 0%;
            }
            .footer{
                position: relative;
                font-size:10px;
                height:30px;
                padding-top:22px;
                padding-left:70px;
            }
            h1{
                font-size:24px;
            }
            .medical-paragraph{
                font-size:14px;
            }
            label{
                font-size:14px;
            }
            .patient-info{
                font-size:14px;
            }
            .btn-cancel{
                width:90px;
                font-size:12px;
                margin:0;
            }
            .btn-save{
                width:120px;
                font-size:12px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/doctor-sidebar.php"; ?>

<div class="content">

<h1>Add Medical History</h1>
<p class="medical-paragraph">Here you can add a patient's medical history for each visit and also keep track of their previous records.</p>
<div class="container">
    <div class="patient-info">
        <strong>Patient:</strong> <?= htmlspecialchars($patient['name'] . ' ' . $patient['lastname']) ?> (ID: <?= htmlspecialchars($patient_id) ?>)
    </div>
    <form method="POST">
        <label for="visit_date">Visit Date:</label>
        <input type="date" name="visit_date" required>

        <label for="visit_time">Visit Hour:</label>
        <input type="time" name="visit_time">

        <label for="diagnosis">Diagnosis:</label>
        <input type="text" name="diagnosis" required>

        <label for="medications">Medicines:</label>
        <textarea name="medications"></textarea>

        <label for="notes">Additional Notes:</label>
        <textarea name="notes"></textarea>

        <button class="btn-save" type="submit">Save History</button>
        <a href="patients.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>
    <?php include "includes/templates/footer.php"; ?>
</div>

<script src="main.js"></script>
</body>
</html>
