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

$doctors = $conn->query("SELECT * FROM users WHERE role_id = 2");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
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
            margin-bottom: 30px;
            max-width: 1150px;
        }
        .footer{
            height:55px;
            padding-top:30px;
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
            padding: 10px 25px;
            margin-right:45px;
        }
        .btn-add:hover { background-color: #1976D2; }

        .btn-edit { background-color: #4CAF50; }
        .btn-edit:hover { background-color: #388E3C; }

        .btn-delete { background-color: #f44336; }
        .btn-delete:hover { background-color: #d32f2f; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px 60px 0 0;
        }

        p.subtitle {
            color: #666;
            font-size: 16px;
            margin-top: 0px;
            text-align: center;
        }
        
        /* Mobile Card Styles */
        .doctors-list {
            display: none; /* Hidden by default */
        }
        .doctor-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .doctor-field {
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
        
        @media (max-width: 580px) {
            .content {
                left: 0%;
            }
            .footer {
                position: relative;
                font-size: 10px;
                height: 30px;
                padding-top: 22px;
                padding-left: 70px;
            }
            h1 {
                font-size: 24px;
            }
            p.subtitle {
                font-size: 14px;
                padding: 0 20px;
            }
            .btn-add {
                height: 30px;
                font-size: 14px;
                align-items: center;
                justify-content: center;
            }
            .card {
                margin: 20px 15px;
                padding: 15px;
                margin-left: 0;
                padding-right: 15px;
                margin:20px;
            }
            
            /* Hide table on mobile */
            table {
                display: none;
            }
            
            /* Show cards on mobile */
            .doctors-list {
                display: block;
            }
            
            .doctor-card {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .doctor-field {
                font-size: 14px;
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
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Manage Doctors</h1>
    <p class="subtitle">Here you can add new doctors, edit their information, or remove them from the system. This dashboard gives you full control over doctor management.</p>

    <div class="btn-container">
        <a href="add-doctor.php" class="btn btn-add">Add New Doctor</a>
    </div>

    <!-- Doctor List -->
    <div class="card">
        <h3>Existing Doctors</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                <tr>
                    <td><?= $doctor['id'] ?></td>
                    <td><?= htmlspecialchars($doctor['username']) ?></td>
                    <td><?= htmlspecialchars($doctor['email']) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="edit-doctor.php?id=<?= $doctor['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete-doctor.php?id=<?= $doctor['id'] ?>" onclick="return confirm('Are you sure you want to delete this doctor?')" class="btn btn-delete">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <!-- Mobile Cards -->
        <div class="doctors-list">
            <?php 
            // Reset pointer to loop through results again
            $doctors->data_seek(0); 
            while ($doctor = $doctors->fetch_assoc()): 
            ?>
                <div class="doctor-card">
                    <div class="doctor-field">
                        <span class="field-label">ID:</span>
                        <span class="field-value"><?= $doctor['id'] ?></span>
                    </div>
                    <div class="doctor-field">
                        <span class="field-label">Username:</span>
                        <span class="field-value"><?= htmlspecialchars($doctor['username']) ?></span>
                    </div>
                    <div class="doctor-field">
                        <span class="field-label">Email:</span>
                        <span class="field-value"><?= htmlspecialchars($doctor['email']) ?></span>
                    </div>
                    <div class="mobile-actions">
                        <a href="edit-doctor.php?id=<?= $doctor['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete-doctor.php?id=<?= $doctor['id'] ?>" onclick="return confirm('Are you sure you want to delete this doctor?')" class="btn btn-delete">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

        <?php include "includes/templates/footer.php";?>
</div>

<script src="main.js"></script>
</body>
</html>