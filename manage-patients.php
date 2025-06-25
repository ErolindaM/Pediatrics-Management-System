<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'];

// Get input from search and doctor filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$doctor_filter = isset($_GET['doctor']) ? trim($_GET['doctor']) : '';

// Build the query
$query = "SELECT p.*, u.username as doctor_username 
          FROM patients p 
          JOIN users u ON p.doctor_id = u.id";

$conditions = [];
$params = [];
$types = "";

// Add conditions based on requirements
if (!empty($search)) {
    $conditions[] = "(p.name LIKE ? OR p.lastname LIKE ? OR u.username LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($doctor_filter)) {
    $conditions[] = "u.username = ?";
    $params[] = $doctor_filter;
    $types .= "s";
}

// If there are any conditions, add to WHERE
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY p.id DESC";

// Prepare the statement
$stmt = $conn->prepare($query);

// If there are parameters, bind them with bind_param
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$patients = $stmt->get_result();

// Get all doctors for dropdown
$doctors = $conn->query("SELECT id, username FROM users WHERE role_id = 2 ORDER BY username");
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
        body {
            background: #f5f7fa;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            margin-top: 50px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
            max-width: 1150px;
            margin: 30px auto;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }

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
        .btn-search { background-color: #673AB7;width:100px;height:41px; }
        .btn-search:hover { background-color: #5E35B1; }
        .btn-edit-history { background-color: #673AB7; }

        .btn-cancel:hover { background-color:rgb(222, 137, 10);color:white; }
        .btn-add:hover, .btn-edit:hover, .btn-delete:hover{
            color:white;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }
        
        .add-patient-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px 80px 0 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size:16px;
        }

        th {
            background: #2196F3;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
        
        .btn-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px 60px 0 0;
        }
        
        form.filter-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 60px;
        }

        form input[type="text"], select {
            padding: 10px;
            font-size: 14px;
            border:#ccc 1px solid;
            width:560px;
            height:40px;
        }

        form button {
            padding: 10px 20px;
            font-size: 16px;
        }
        
        .medical-history {
            display: none;
            margin-top: 10px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        
        .history-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        /* Mobile Card Styles */
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
        .patients-paragraph {
            color:#666;
            padding:0 20px;
            font-size:16px;
            margin-bottom:20px;
            text-align:center;
        }
        label{
            font-size:16px;
            display:inline;
        }
        #doctor{
            width:200px;
        }
        .footer {
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
        @media (max-width: 768px) {
            .card {
                margin: 20px;
                padding: 15px;
            }
            
            form.filter-form {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                margin: 20px;
            }
            
            form.filter-form div {
                width: 100%;
            }
            
            form input[type="text"] {
                width: calc(100% - 120px);
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
            
            .patients-paragraph {
                font-size: 14px;
                padding: 0 15px;
            }
            
            .card {
                margin: 15px;
                padding: 15px;
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
            
            .btn-container, .add-patient-container {
                justify-content: center;
                margin: 20px 15px 0 15px;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 13px;
            }
            
            .footer {
                position: relative;
                font-size: 10px;
                height: 30px;
                padding-top: 22px;
                padding-left: 70px;
            }
            
            form input[type="text"] {
                width: calc(100% - 110px);
                font-size: 14px;
            }
            form input[type="text"] {
                height: 30px;
                font-size: 12px;
            }
            
            .btn-search{
                font-size:12px;
                height:30px;
                padding:0px;
                margin-bottom:0px;
            }
            
            select {
                width: 100%;
                font-size: 14px;
            }
            label{
                font-size:14px;
            }
            #doctor{
                height:40px;
                width:160px;
                margin:0;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Manage Patients</h1>
    <p class="patients-paragraph">Here you can add new patients, edit their information, or remove patients from the system. This dashboard gives you full control over patient management.</p>

    <!-- Search Form and Filter -->
    <form method="GET" class="filter-form">
        <div>
            <input type="text" name="search" placeholder="Search by name, lastname or doctor..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-delete clear-search">Clear Search</a>
            <?php endif; ?>
        </div>

        <div>
            <label for="doctor" id="doctor">Filter by Doctor:</label>
            <select name="doctor" id="doctor" onchange="this.form.submit()">
                <option value="">All Doctors</option>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($doc['username']) ?>" <?= ($doctor_filter == $doc['username']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($doc['username']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <div class="btn-container">
        <a href="add-patient-admin.php" class="btn btn-add">Add New Patient</a>
    </div>

    <!-- Patients List -->
    <div class="card">
        <h3>Existing Patients</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
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
                    <td><?= htmlspecialchars($patient['doctor_username']) ?></td>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['lastname']) ?></td>
                    <td><?= htmlspecialchars($patient['phone']) ?></td>
                    <td><?= htmlspecialchars($patient['gender']) ?></td>
                    <td><?= htmlspecialchars($patient['date_of_birth']) ?></td>
                    <td>
                        <button class="btn btn-cancel" onclick="toggleHistory(<?= $patient['id'] ?>, this)">Show History</button>
                        <div id="history-<?= $patient['id'] ?>" class="medical-history">
                            <?php
                                $patient_id = $patient['id'];
                                $stmt2 = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY visit_date DESC");
                                $stmt2->bind_param("i", $patient_id);
                                $stmt2->execute();
                                $history_result = $stmt2->get_result();
                                if ($history_result->num_rows > 0) {
                                    while ($history = $history_result->fetch_assoc()):
                            ?>
                                        <div class="history-item">
                                            <div><strong>Visit Date:</strong> <?= htmlspecialchars($history['visit_date']) ?></div>
                                            <div><strong>Time:</strong> <?= htmlspecialchars($history['visit_time']) ?></div>
                                            <div><strong>Diagnosis:</strong> <?= htmlspecialchars($history['diagnosis']) ?></div>
                                            <div><strong>Medications:</strong> <?= htmlspecialchars($history['medications']) ?></div>
                                            <div><strong>Notes:</strong> <?= htmlspecialchars($history['notes']) ?></div>
                                            <form method="POST" action="delete-medical-history.php" onsubmit="return confirm('Are you sure you want to delete this history?')" style="margin-top: 10px;">
                                                <input type="hidden" name="history_id" value="<?= $history['id'] ?>">
                                                <button type="submit" class="btn btn-delete">Delete</button>
                                            </form>
                                        </div>
                            <?php
                                    endwhile;
                                } else {
                                    echo "There is no medical history for this patient.";
                                }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="add-medical-history-admin.php?patient_id=<?= $patient['id'] ?>" class="btn btn-add">Add History</a>
                            <a href="edit-patient-admin.php?id=<?= $patient['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete-patient-admin.php?id=<?= $patient['id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-delete">Delete</a>
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
                        <span class="field-label">Doctor:</span>
                        <span class="field-value"><?= htmlspecialchars($patient['doctor_username']) ?></span>
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
                        <button class="btn btn-cancel" onclick="toggleHistoryMobile(<?= $patient['id'] ?>, this)">Show History</button>
                        <div id="history-mobile-<?= $patient['id'] ?>" class="medical-history-mobile">
                            <?php
                                $patient_id = $patient['id'];
                                $stmt2 = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY visit_date DESC");
                                $stmt2->bind_param("i", $patient_id);
                                $stmt2->execute();
                                $history_result = $stmt2->get_result();
                                if ($history_result->num_rows > 0) {
                                    while ($history = $history_result->fetch_assoc()):
                            ?>
                                        <div class="history-item">
                                            <div><strong>Visit Date:</strong> <?= htmlspecialchars($history['visit_date']) ?></div>
                                            <div><strong>Time:</strong> <?= htmlspecialchars($history['visit_time']) ?></div>
                                            <div><strong>Diagnosis:</strong> <?= htmlspecialchars($history['diagnosis']) ?></div>
                                            <div><strong>Medications:</strong> <?= htmlspecialchars($history['medications']) ?></div>
                                            <div><strong>Notes:</strong> <?= htmlspecialchars($history['notes']) ?></div>
                                            <form method="POST" action="delete-medical-history.php" onsubmit="return confirm('Are you sure you want to delete this history?')" style="margin-top: 10px;">
                                                <input type="hidden" name="history_id" value="<?= $history['id'] ?>">
                                                <button type="submit" class="btn btn-delete">Delete</button>
                                            </form>
                                        </div>
                            <?php
                                    endwhile;
                                } else {
                                    echo "There is no medical history for this patient.";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="mobile-actions">
                        <a href="add-medical-history-admin.php?patient_id=<?= $patient['id'] ?>" class="btn btn-add">Add History</a>
                        <a href="edit-patient-admin.php?id=<?= $patient['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete-patient-admin.php?id=<?= $patient['id'] ?>" onclick="return confirm('Are you sure you want to delete this patient?')" class="btn btn-delete">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include "includes/templates/footer.php"; ?>
</div>

<script>
function toggleHistory(id, button) {
    var element = document.getElementById('history-' + id);
    if (element.style.display === "none") {
        element.style.display = "block";
        button.textContent = "Hide History";
    } else {
        element.style.display = "none";
        button.textContent = "Show History";
    }
}

function toggleHistoryMobile(id, button) {
    var element = document.getElementById('history-mobile-' + id);
    if (element.style.display === "none") {
        element.style.display = "block";
        button.textContent = "Hide History";
    } else {
        element.style.display = "none";
        button.textContent = "Show History";
    }
}
</script>
<script src="main.js"></script>
</body>
</html>