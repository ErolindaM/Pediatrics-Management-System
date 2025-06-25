<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;
$doctor_id = $_SESSION['user_id'];
$patient_id = intval($_GET['id']);

// Get patient data
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ? AND doctor_id = ?");
$stmt->bind_param("ii", $patient_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    header("Location: patients.php");
    exit();
}

// Handle patient update
if (isset($_POST['edit_patient'])) {
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];

    $stmt = $conn->prepare("UPDATE patients SET name = ?, lastname = ?, phone = ?, gender = ?, date_of_birth = ? WHERE id = ? AND doctor_id = ?");
    $stmt->bind_param("sssssii", $name, $lastname, $phone, $gender, $dob, $patient_id, $doctor_id);
    $stmt->execute();
    $stmt->close();

    header("Location: patients.php");
    exit();
}

// Handle medical history update
if (isset($_POST['update_history'])) {
    $history_id = intval($_POST['history_id']);
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $diagnosis = trim($_POST['diagnosis']);
    $medications = trim($_POST['medications']);
    $notes = trim($_POST['notes']);

    $stmt = $conn->prepare("UPDATE medical_history SET visit_date = ?, visit_time = ?, diagnosis = ?, medications = ?, notes = ? WHERE id = ? AND patient_id = ?");
    $stmt->bind_param("sssssii", $visit_date, $visit_time, $diagnosis, $medications, $notes, $history_id, $patient_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: edit-patient.php?id=".$patient_id);
    exit();
}

// Get medical history for the patient
$history_stmt = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY visit_date DESC");
$history_stmt->bind_param("i", $patient_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background: #f5f7fa; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 50px;
            margin: 40px auto;
            max-width: 800px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-save {
            background-color: #4CAF50;
        }
        .btn-cancel {
            background-color: #f44336;
        }
        h1 { 
            font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        .history-container {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        .history-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .history-item h4 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            font-size:18px;
        }
        .history-actions {
            margin-top: 15px;
        }
        .edit-paragraph{
            color:#666;
            padding:20px;
            font-size:16px;
            margin-top:0px;
            text-align:center;
        }
        .footer{
            height:55px;
            padding-top:20px;
        }
        .btn-cancel:hover{
                background-color: rgb(207, 12, 12);
        }
        .btn-save:hover {
                background-color:rgb(50, 130, 53);
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
            .edit-paragraph{
                font-size:14px;
            }
            .card{
                max-width:342px;
            }

            input, select, textarea{
                height:35px;
                font-size:12px;
                padding:5px;
            }
            .btn{
                height:30px;
                font-size:12px;
                padding:0px;
                display:flex;
                justify-content:center;
                align-items:center;
            }
            .history-item h4{
                font-size:16px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/doctor-sidebar.php"; ?>

<div class="content">
    <h1>Edit Patient</h1>
    <p class="edit-paragraph">Use the form below to update the patient's information. You can modify details such as name, lastname, contact information, and medical history.</p>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($patient['lastname']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($patient['phone']) ?>">
            </div>
            
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                    <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?= $patient['date_of_birth'] ?>">
            </div>
            
            <div class="btn-container">
                <button type="submit" name="edit_patient" class="btn btn-save">Save Changes</button>
                <a href="patients.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
    
    <h3 style="text-align:center">Medical History</h3>
    <div class="card history-container">
        <?php if ($history_result->num_rows > 0): ?>
            <?php while ($history = $history_result->fetch_assoc()): ?>
                <div class="history-item">
                    <h4>Visit on <?= htmlspecialchars($history['visit_date']) ?> at <?= htmlspecialchars($history['visit_time']) ?></h4>
                    <form method="POST">
                        <input type="hidden" name="history_id" value="<?= $history['id'] ?>">
                        
                        <div class="form-group">
                            <label>Visit Date:</label>
                            <input type="date" name="visit_date" value="<?= htmlspecialchars($history['visit_date']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Visit Time:</label>
                            <input type="time" name="visit_time" value="<?= htmlspecialchars($history['visit_time']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Diagnosis:</label>
                            <input type="text" name="diagnosis" value="<?= htmlspecialchars($history['diagnosis']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Medications:</label>
                            <textarea name="medications"><?= htmlspecialchars($history['medications']) ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Notes:</label>
                            <textarea name="notes"><?= htmlspecialchars($history['notes']) ?></textarea>
                        </div>
                        
                        <div class="btn-container">
                            <button type="submit" name="update_history" class="btn btn-save">Save Changes</button>
                            <a href="patients.php" class="btn btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No medical history found for this patient.</p>
        <?php endif; ?>
    </div>
    
    <?php include "includes/templates/footer.php"; ?>
</div>

<script src="main.js"></script>
</body>
</html>