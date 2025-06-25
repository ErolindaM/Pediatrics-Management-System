<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;
$role_id = $_SESSION['role_id'];
$doctor_id = $_SESSION['user_id']; 

// Get patients added by this doctor
$stmt = $conn->prepare("SELECT * FROM patients WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$patients = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background: #f5f7fa; }
        h1 { 
            font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 30px 50px;
            max-width: 1400px;
        }
        table {
            width: 100%; border-collapse: collapse;
        }
        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        th { background: #2196F3; color: white; }
        tr:hover { background-color: #f1f1f1; }

        .btn { 
            padding: 10px 18px; 
            border: none; 
            border-radius: 4px; 
            color: white; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-add { background-color: #2196F3; }
        .btn-edit { background-color: #4CAF50; }
        .btn-delete { background-color: #f44336; }
        .btn-cancel { background-color: #ff9800; }
        
        .btn-cancel:hover { background-color:rgb(222, 137, 10);color:white; }
        .btn-add:hover, .btn-edit:hover, .btn-delete:hover{
            color:white;
        }
        .action-btns {
            display: flex;
            gap: 10px;
        }
         .footer{
            height:55px;
            padding-top:30px;
            position:fixed;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
        .add-patient-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px 80px 0 0;
        }
        
        /* Card styles for mobile */
        .patients-list {
            display: none; /* Hidden by default */
        }
        .patient-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .patient-field {
            display: flex;
            margin-bottom: 8px;
            font-size: 15px;
            flex-wrap: wrap;
        }
        .field-label {
            font-weight: bold;
            min-width: 120px;
            color: #555;
        }
        .field-value {
            color: #333;
            flex: 1;
        }
        .mobile-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .mobile-actions .btn {
            padding: 6px 10px;
            font-size: 14px;
        }
        .medical-history-mobile {
            display: none;
            margin-top: 10px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .patients-paragraph{
            color:#666;
            padding:0 20px;
            font-size:16px;
            margin-bottom:20px;
            text-align:center;
        }
         .card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            font-size: 20px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
                margin-top: 30px;
            }
            .paragraph-patients {
                font-size: 16px;
                padding: 0 15px 15px;
            }
            .card {
                margin: 20px 15px;
                padding: 15px;
            }
            .add-patient-container {
                margin-right: 15px;
            }
        }
        
        @media (max-width: 580px) {
            .content {
                left: 0%;
            }
            
            h1 {
                font-size: 24px;
                margin-top: 40px;
            }
            
            .paragraph-patients {
                font-size: 14px;
                padding: 0 10px 15px;
            }
            
            /* Hide table on mobile */
            table {
                display: none;
            }
            
            /* Show cards on mobile */
            .patients-list {
                display: block;
            }
            
            .patient-card {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .patient-field {
                font-size: 14px;
            }
            
            .field-label {
                min-width: 100px;
            }

            
            .add-patient-container {
                justify-content: center;
                margin: 20px 0 0 0;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 13px;
                width:300px;
            }
            .patients-paragraph{
                 font-size:14px;
            }
            .footer{
                position: relative;
                font-size:10px;
                height:30px;
                padding-top:22px;
                padding-left:70px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/doctor-sidebar.php"; ?>

<div class="content">
    <h1>Manage Your Patients</h1>
    <p class="patients-paragraph">Here you can add new patients, edit the information, or remove them from the system. This dashboard gives you full control over patient management.</p>

    <div class="add-patient-container">
        <a href="add-patient.php" class="btn btn-add">Add New Patient</a>
    </div>

    <div class="card">
        <h3>Your Patients</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Lastname</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Medical History</th>
                <th>Actions</th>
            </tr>
            <?php while ($patient = $patients->fetch_assoc()): ?>
                <tr>
                    <td><?= $patient['id'] ?></td>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['lastname']) ?></td>
                    <td><?= htmlspecialchars($patient['phone']) ?></td>
                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                    <td><?= htmlspecialchars($patient['date_of_birth']) ?></td>
                   <td>
                        <button class="btn btn-cancel" onclick="toggleHistory(<?= $patient['id'] ?>)">Show History</button>
                        <div id="history-<?= $patient['id'] ?>" class="medical-history" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border-radius:5px;">
                            <?php
                                $patient_id = $patient['id'];
                                $stmt2 = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY visit_date DESC");
                                $stmt2->bind_param("i", $patient_id);
                                $stmt2->execute();
                                $history_result = $stmt2->get_result();
                                if ($history_result->num_rows > 0) {
                                    while ($history = $history_result->fetch_assoc()) {
                                        echo "<div style='margin-bottom:15px;'>";
                                        echo "<strong>Visit date:</strong> " . htmlspecialchars($history['visit_date']) . "<br>";
                                        echo "<strong>Time:</strong> " . htmlspecialchars($history['visit_time']) . "<br>";
                                        echo "<strong>Diagnosis:</strong> " . htmlspecialchars($history['diagnosis']) . "<br>";
                                        echo "<strong>Medications:</strong> " . htmlspecialchars($history['medications']) . "<br>";
                                        echo "<strong>Notes:</strong> " . htmlspecialchars($history['notes']) . "<br>";
                                        echo "<hr>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "There is no medical history for this patient.";
                                }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="add-medical-history.php?patient_id=<?= $patient['id'] ?>" class="btn btn-add">Add History</a>
                            <a href="edit-patient.php?id=<?= $patient['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete-patient.php?id=<?= $patient['id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-delete">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <!-- Mobile Cards -->
        <div class="patients-list">
            <?php 
            // Reset pointer to loop through results again
            $patients->data_seek(0); 
            while ($patient = $patients->fetch_assoc()): 
            ?>
                <div class="patient-card">
                    <div class="patient-field">
                        <span class="field-label">ID:</span>
                        <span class="field-value"><?= $patient['id'] ?></span>
                    </div>
                    <div class="patient-field">
                        <span class="field-label">Name:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['name']) ?></span>
                    </div>
                    <div class="patient-field">
                        <span class="field-label">Lastname:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['lastname']) ?></span>
                    </div>
                    <div class="patient-field">
                        <span class="field-label">Phone:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['phone']) ?></span>
                    </div>
                    <div class="patient-field">
                        <span class="field-label">Gender:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['gender']) ?></span>
                    </div>
                    <div class="patient-field">
                        <span class="field-label">Date of Birth:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['date_of_birth']) ?></span>
                    </div>
                    <div class="patient-field">
                        <button class="btn btn-cancel" onclick="toggleHistoryMobile(<?= $patient['id'] ?>)">Show History</button>
                        <div id="history-mobile-<?= $patient['id'] ?>" class="medical-history-mobile">
                            <?php
                                $patient_id = $patient['id'];
                                $stmt2 = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY visit_date DESC");
                                $stmt2->bind_param("i", $patient_id);
                                $stmt2->execute();
                                $history_result = $stmt2->get_result();
                                if ($history_result->num_rows > 0) {
                                    while ($history = $history_result->fetch_assoc()) {
                                        echo "<div style='margin-bottom:10px;'>";
                                        echo "<strong>Visit date:</strong> " . htmlspecialchars($history['visit_date']) . "<br>";
                                        echo "<strong>Time:</strong> " . htmlspecialchars($history['visit_time']) . "<br>";
                                        echo "<strong>Diagnosis:</strong> " . htmlspecialchars($history['diagnosis']) . "<br>";
                                        echo "<strong>Medications:</strong> " . htmlspecialchars($history['medications']) . "<br>";
                                        echo "<strong>Notes:</strong> " . htmlspecialchars($history['notes']) . "<br>";
                                        echo "<hr>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "There is no medical history for this patient.";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="mobile-actions">
                        <a href="add-medical-history.php?patient_id=<?= $patient['id'] ?>" class="btn btn-add">Add History</a>
                        <a href="edit-patient.php?id=<?= $patient['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete-patient.php?id=<?= $patient['id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-delete">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; Copyright 2025 All rights reserved by KIDS CARE</p>
    </div>
</div>

<script>
function toggleHistory(id) {
    var element = document.getElementById('history-' + id);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function toggleHistoryMobile(id) {
    var element = document.getElementById('history-mobile-' + id);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}
</script>
<script src="main.js"></script>
</body>
</html>