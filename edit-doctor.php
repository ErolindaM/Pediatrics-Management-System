<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;
$doctor_id = intval($_GET['id']);

// Get doctor data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role_id = 2");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    header("Location: manage-doctors.php");
    exit();
}

if (isset($_POST['edit_doctor'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ? AND role_id = 2");
    $stmt->bind_param("ssi", $username, $email, $doctor_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-doctors.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background: #f5f7fa; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 30px auto;
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
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
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
         h1{ font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        .footer{
            height:55px;
            padding-top:30px;
            position:fixed;
            bottom:0;
            width:100%;
            padding-left:480px;
            justify-content:left;
        }
        .edit-paragraph{
            color:#666;
            padding:20px;
            font-size:16px;
            margin-top:0px;
            text-align:center;
        }
        .btn-cancel:hover{
                background-color: rgb(207, 12, 12);
        }
        .btn-save:hover {
                background-color:rgb(50, 130, 53);
        }
        @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:30px;
                padding-top:22px;
                position: relative;
                padding-left: 70px;
            }
            h1{
                font-size:24px;
            }
            .edit-paragraph{
                font-size:14px;
                text-align:center;
            }
            .card{
                width:320px;
                margin-bottom:50px;
            }
            label{
                font-size:14px;
            }
            input, select{
                height:40px;
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
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Edit Doctor</h1>
    <p class="edit-paragraph"> Use this form to update the doctor's information in the system. You can modify their name, specialty, contact details, and other relevant data to ensure all records remain accurate and up to date. Keeping doctor profiles current helps maintain smooth communication and efficient scheduling.</p>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($doctor['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($doctor['email']) ?>" required>
            </div>
            
            <div class="btn-container">
                <a href="manage-doctors.php" class="btn btn-cancel">Cancel</a>
                <button type="submit" name="edit_doctor" class="btn btn-save">Save Changes</button>
            </div>
        </form>
    </div>
    <div class="footer">
        <p>&copy; Copyright 2025 All rights reserved by KIDS CARE</p>
    </div>
</div>
<script src="main.js"></script>
</body>
</html>