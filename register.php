<?php 
include "includes/functions/connect.php";

error_reporting(0);
session_start();

if(isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    if(strlen($_POST['password']) < 8 || strlen($username) < 3 || strlen($username) > 20 || 
        !preg_match('/^[a-zA-Z0-9_]+$/', $username) || 
        !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        $password != $cpassword) {
        
        if(strlen($_POST['password']) < 8) {
            echo "<script>alert('Password must be at least 8 characters long.')</script>";
        } else if(strlen($username) < 3 || strlen($username) > 20) {
            echo "<script>alert('Username must be between 3 and 20 characters long.')</script>";
        } else if(!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            echo "<script>alert('Username can only contain letters, numbers, and underscores.')</script>";
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format. Please enter a valid email address.')</script>";
        } else if($password != $cpassword) {
            echo "<script>alert('The passwords do not match. Please try again!')</script>";
        }

    } else {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        
        if(!$result->num_rows > 0) {
            $role_id = 3; 
            $sql = "INSERT INTO users (username, email, password, role_id) VALUES('$username', '$email', '$password', $role_id)";
            $result = mysqli_query($conn, $sql);

            if($result) {
                echo "<script>
                    alert('Congratulations! You have been registered successfully.');
                    window.location.href = 'login.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Something went wrong. Try again!')</script>";
            }
        } else {
            echo "<script>alert('Email already registered. Try logging in.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @media (max-width: 580px){
            body{
                background-repeat:round;
            }
            .container{
                width:300px;
                height:auto;
            }
            .container header{
                font-size:20px;
            }
            .input-group label{
                font-size:14px;
            }
            .form .input-group input{
                height:40px;
                font-size:14px;
            }
            .form button{
                height:30px;
                font-size:14px;
            }
            .login-register-text{
                font-size:14px;
            }
        }
    </style>
</head>
<body>
<section class="container">
    <header>Please register to have access on our website!</header>
    <form action="" method="post" class="form">
        <div class="input-group">
            <label>Username</label>
            <input type="text" placeholder="Enter username" name="username" required>
        </div>
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" placeholder="username@gmail.com" name="email" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" placeholder="********" name="password" required>
        </div>
        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" placeholder="********" name="cpassword" required>
        </div>
        <div class="input-group">
            <button name="submit">Submit</button>
        </div>
        <p class="login-register-text">Already have an account? <a href="login.php">Login now!</a></p>
    </form>
</section>
</body>
</html>
