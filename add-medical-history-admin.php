<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'];
$patient_id = $_GET['patient_id'] ?? null;

if (!$patient_id) {
    echo "Patient ID is missing.";
    exit();
}

// Get patient info
$stmt_patient = $conn->prepare("SELECT name, lastname FROM patients WHERE id = ?");
$stmt_patient->bind_param("i", $patient_id);
$stmt_patient->execute();
$patient_result = $stmt_patient->get_result();
$patient = $patient_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $diagnosis = $_POST['diagnosis'];
    $medications = $_POST['medications'];
    $notes = $_POST['notes'];

    // Save to medical_history table
    $stmt = $conn->prepare("INSERT INTO medical_history (patient_id, visit_date, visit_time, diagnosis, medications, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $patient_id, $visit_date, $visit_time, $diagnosis, $medications, $notes);

    if ($stmt->execute()) {
        header("Location: manage-patients.php");
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
            font-size: 16px;
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
        .btn-save {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
        }
        .btn-save:hover {
            background-color: #0b7dda;
        }
        form {
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
        .btn-cancel:hover {
            background-color:rgb(217, 40, 27);
        }
        .patient-info {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .footer {
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
        .add-medical-history{
            color: #666;
            padding:0 20px;
            font-size:16px;
            padding-bottom: 20px;
            text-align:center;
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
            .add-medical-history{
                font-size:14px;
            }
            .card{
                width:300px;
                margin-bottom:40px;
            }
            label{
                font-size:14px;
            }
            .btn{
                height:30px;
                padding:0;
                 display:flex;
                justify-content:center;
                align-items:center;
                font-size:12px;
            }
            input, select{
                height:30px;
            }
            .patient-info{
                font-size:14px;
            }
            .container{
                padding-left:0px;
                padding-right:0px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Add Medical History</h1>
    <p class="add-medical-history">Add medical history for patient visits and keep track of their records.</p>
    
    <div class="container">
        <div class="patient-info">
            <strong>Patient:</strong> <?= htmlspecialchars($patient['name'] . ' ' . $patient['lastname']) ?> (ID: <?= htmlspecialchars($patient_id) ?>)
        </div>

        <form method="POST">
            <label for="visit_date">Visit Date:</label>
            <input type="date" name="visit_date" required>

            <label for="visit_time">Visit Time:</label>
            <input type="time" name="visit_time">

            <label for="diagnosis">Diagnosis:</label>
            <input type="text" name="diagnosis" required>

            <label for="medications">Medications:</label>
            <textarea name="medications"></textarea>

            <label for="notes">Additional Notes:</label>
            <textarea name="notes"></textarea>

            <button class="btn btn-save" type="submit">Save History</button>
            <a href="manage-patients.php" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
    
    <?php include "includes/templates/footer.php"; ?>
</div>

<script src="main.js"></script>
</body>
</html>