<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'];

if (!$username || $role_id != 1) {
    header("Location: login.php");
    exit();
}

// Get parameters from URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$doctor_filter = isset($_GET['doctor']) ? mysqli_real_escape_string($conn, $_GET['doctor']) : '';

// Create query with filters
$query = "SELECT a.*, u.username as doctor_name FROM appointments a 
          LEFT JOIN users u ON a.doctor_id = u.id WHERE 1";

if (!empty($search)) {
    $query .= " AND (a.name LIKE '%$search%' OR a.lastname LIKE '%$search%')";
}

if (!empty($doctor_filter)) {
    $query .= " AND u.username = '$doctor_filter'";
}

$query .= " ORDER BY a.appointment_date DESC";
$appointments = $conn->query($query);

// Get doctor list for dropdown
$doctors = $conn->query("SELECT DISTINCT u.username FROM users u 
                         INNER JOIN appointments a ON u.id = a.doctor_id");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body {
            background: #f5f7fa;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-top: 50px;
            text-align: center;
        }

        p.subtitle {
            color: #666;
            font-size: 16px;
            margin-top: 0px;
            text-align: center;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
            max-width: 1150px;
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
            transition: background 0.3s ease;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add { 
            background-color: #2196F3;
            margin-bottom: 20px;
            padding:10px 25px;
        }
        .btn-add:hover { background-color: #1976D2; }

        .btn-edit { background-color: #4CAF50; }
        .btn-edit:hover { background-color: #388E3C; }

        .btn-delete { background-color: #f44336; }
        .btn-delete:hover { background-color: #d32f2f; }
        
        .btn-search { background-color: #673AB7;width:100px;height:41px; }
        .btn-search:hover { background-color: #5E35B1; }

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

        .action-btns {
            display: flex;
            gap: 10px;
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
        
        /* Mobile Card Styles */
        .appointments-list {
            display: none; /* Hidden by default */
        }
        .appointment-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .appointment-field {
            display: flex;
            margin-bottom: 8px;
            font-size: 15px;
            flex-wrap: wrap;
        }
        .field-label {
            font-weight: bold;
            min-width: 80px;
            color: #555;
        }
        .field-value {
            color: #333;
            flex: 1;
        }
        .appointment-content {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 14px;
        }
        .footer {
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
        }
        .doctor-filter{
            font-size: 16px;
            margin-right: 10px
        }
        #doctor{
            width:200px;
        }
        @media (max-width: 768px) {
            .card {
                margin: 20px;
                padding: 20px;
            }

            th, td {
                font-size: 16px;
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
            .footer {
                font-size: 10px;
                height: 30px;
                padding-top: 22px;
                position: relative;
                padding-left: 70px;
            }
            h1 {
                font-size: 24px;
                margin-top: 40px;
            }
            
            p.subtitle {
                font-size: 14px;
                padding: 0 15px;
            }
            
            .card {
                margin: 20px 15px;
                padding: 15px;
                margin-left: 0;
                padding-right: 15px;
                margin:20px;
            }
            .btn-container {
                display: flex;
                justify-content: flex-end;
                margin: 22px 0px 0 30px;
            }
            .btn-add{
                height:30px;
                font-size:14px;
                align-items:center;
                justify-content:center;
                padding-bottom: 30px;
                margin-left:15px;
            }
            
            /* Hide table on mobile */
            table {
                display: none;
            }
            
            /* Show cards on mobile */
            .appointments-list {
                display: block;
            }
            
            .appointment-card {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .appointment-field {
                font-size: 14px;
            }
            
            .field-label {
                min-width: 70px;
            }
            
            .btn-container {
                margin: 20px 15px 0 0;
            }
            
            form.filter-form {
                margin: 15px;
            }
            
            form input[type="text"] {
                width: calc(100% - 110px);
                font-size: 14px;
            }
            
            .btn-search {
                width: 80px;
                height: 40px;
                font-size: 14px;
            }
            
            select {
                width: 100%;
                font-size: 14px;
            }
            .btn{
                font-size:14px;
            }
            form input[type="text"]{
                height:30px;
                font-size:12px;
            }
            .btn-search{
                font-size:12px;
                height:30px;
                padding:0px;
                margin-bottom:0px;
            }
            .doctor-filter{
                font-size:14px;
            }
            #doctor{
                height:40px;
                width:200px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Manage Appointments</h1>
    <p class="subtitle">Here you can view all scheduled appointments, add new ones, edit existing appointments, or remove them from the system.</p>

    <!-- Form for search and filter -->
    <form method="GET" class="filter-form">
        <div>
            <input type="text" name="search" placeholder="Search by patient name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-delete clear-search">Clear Search</a>
            <?php endif; ?>
        </div>

        <div>
            <label for="doctor" class="doctor-filter">Filter by Doctor:</label>
            <select name="doctor" id="doctor" onchange="this.form.submit()">
                <option value="">All Doctors</option>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($doc['username']) ?>" <?= $doctor_filter == $doc['username'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($doc['username']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <div class="btn-container">
        <a href="add-appointment.php" class="btn btn-add">Add New Appointment</a>
    </div>

    <!-- Appointments List -->
    <div class="card">
        <h3>Scheduled Appointments</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($appointment = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= $appointment['id'] ?></td>
                    <td><?= htmlspecialchars($appointment['name'] . ' ' . $appointment['lastname']) ?></td>
                    <td><?= htmlspecialchars($appointment['email']) ?></td>
                    <td><?= htmlspecialchars($appointment['phone']) ?></td>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="edit-appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete-appointment.php?id=<?= $appointment['id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?')" class="btn btn-delete">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <!-- Mobile Cards -->
        <div class="appointments-list">
            <?php
            // Reset pointer to loop through results again
            $appointments->data_seek(0);
            
            if ($appointments->num_rows > 0) {
                while ($appointment = $appointments->fetch_assoc()): ?>
                    <div class="appointment-card">
                        <div class="appointment-field">
                            <span class="field-label">ID:</span>
                            <span class="field-value"><?= $appointment['id'] ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Patient:</span>
                            <span class="field-value"><?= htmlspecialchars($appointment['name'] . ' ' . $appointment['lastname']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Email:</span>
                            <span class="field-value"><?= htmlspecialchars($appointment['email']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Phone:</span>
                            <span class="field-value"><?= htmlspecialchars($appointment['phone']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Doctor:</span>
                            <span class="field-value"><?= htmlspecialchars($appointment['doctor_name']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Date:</span>
                            <span class="field-value"><?= htmlspecialchars($appointment['appointment_date']) ?></span>
                        </div>
                        <div class="appointment-content">
                            <div class="mobile-actions">
                                <a href="edit-appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="delete-appointment.php?id=<?= $appointment['id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?')" class="btn btn-delete">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            } else {
                echo '<div class="appointment-card" style="text-align:center;">No appointments found</div>';
            }
            ?>
        </div>
    </div>

    <?php include "includes/templates/footer.php"; ?>
</div>

<script src="main.js"></script>
</body>
</html>