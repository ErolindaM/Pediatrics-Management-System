<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    $role_id = 3; // Assuming 3 is for regular users

    if (!empty($username) && !empty($email) && !empty($password)) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $password, $role_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage-users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background: #f5f7fa; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 100px auto;
            margin-top:0;
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
        h1 { font-size: 32px; 
            margin-top:50px;
            margin-bottom:0px;
            text-align:center;
        }
        .footer{
            position: fixed;
            bottom: 0; 
            height:55px;
            width: 100%;
            justify-content:flex-start;
            padding-left:490px;
            padding-top:30px;
        }
        .add-user-paragraph{
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
            .add-user-paragraph{
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
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Add New User</h1>
    <p class="add-user-paragraph">Use the form below to add a new user to the system. Please ensure that all required fields are filled out accurately, including the username, email and password. This information is essential for granting proper access and managing user permissions within the platform. Once submitted, the new user will be able to log in using their credentials.</p>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="add_user" class="btn btn-save">Save User</button>
                <a href="manage-users.php" class="btn btn-cancel">Cancel</a>
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