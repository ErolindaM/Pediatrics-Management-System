<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'];
$doctor_id = $_SESSION['user_id'];

// Fetch appointments for the current doctor
$stmt = $conn->prepare("SELECT id, name, lastname, email, phone, gender, city, appointment_date, message 
                        FROM appointments 
                        WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { 
            background: #f5f7fa; 
        }
        h1 { 
            font-size: 32px; 
            margin-top: 50px;
            margin-bottom: 0px;
            text-align: center;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 30px 50px;
            max-width: 1400px;
            padding-right:60px;
            margin-bottom: 100px;
        }
        
        /* Table styles for desktop */
        table {
            width: 100%; 
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        th { 
            background: #2196F3; 
            color: white; 
        }
        tr:hover { 
            background-color: #f1f1f1; 
        }
        
        
        .btn { padding: 10px 18px; border: none; border-radius: 4px; color: white; cursor: pointer; }
        .btn-add { background-color: #2196F3; }
        .btn-edit { background-color: #4CAF50; }
        .btn-delete { background-color: #f44336; }

        /* Card styles for mobile */
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
            min-width: 100px;
            color: #555;
        }
        .field-value {
            color: #333;
            flex: 1;
        }
        
        .paragraph-appointments {
            color: #666;
            padding: 0 20px;
            font-size: 16px;
            padding-bottom: 20px;
            text-align: center;
        }
        
        .no-appointments {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 18px;
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
        /* Responsive styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
                margin-top: 30px;
            }
            .paragraph-appointments {
                font-size: 16px;
                padding: 0 15px 15px;
            }
            .card {
                margin: 20px 15px;
                padding: 15px;
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
            
            .paragraph-appointments {
                font-size: 14px;
                padding: 0 10px 15px;
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
                min-width: 80px;
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

<?php include 'includes/templates/doctor-sidebar.php'; ?>

<div class="content">
    <h1 class="title-appointments">My Appointments</h1>
    <p class="paragraph-appointments">Here you can view and manage all scheduled appointments with your patients. Stay organized with up-to-date information on appointment dates, times, and patient details.</p>
    
    <div class="card">
        <?php if ($result->num_rows > 0): ?>
            <!-- Desktop Table -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>City</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name'] . ' ' . $row['lastname']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['city']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            
            <!-- Mobile Cards -->
            <div class="appointments-list">
                <?php 
                // Reset pointer to loop through results again
                $result->data_seek(0); 
                while ($row = $result->fetch_assoc()): 
                ?>
                    <div class="appointment-card">
                        <div class="appointment-field">
                            <span class="field-label">ID:</span>
                            <span class="field-value"><?= $row['id'] ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Name:</span>
                            <span class="field-value"><?= htmlspecialchars($row['name'] . ' ' . $row['lastname']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Email:</span>
                            <span class="field-value"><?= htmlspecialchars($row['email']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Phone:</span>
                            <span class="field-value"><?= htmlspecialchars($row['phone']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Gender:</span>
                            <span class="field-value"><?= htmlspecialchars($row['gender']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">City:</span>
                            <span class="field-value"><?= htmlspecialchars($row['city']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Date:</span>
                            <span class="field-value"><?= htmlspecialchars($row['appointment_date']) ?></span>
                        </div>
                        <div class="appointment-field">
                            <span class="field-label">Message:</span>
                            <span class="field-value"><?= htmlspecialchars($row['message']) ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-appointments">No appointments found for you.</p>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p>&copy; Copyright 2025 All rights reserved by KIDS CARE</p>
    </div>
</div>
<script src="main.js"></script>
</body>
</html>