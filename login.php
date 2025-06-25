<?php
include "includes/functions/connect.php";

error_reporting(0);
session_start();

$redirect = $_GET['redirect'] ?? 'index.php';

if(isset($_SESSION['username'])) {
    header("Location: $redirect");
    exit;
}


if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $row['username'];
        $_SESSION['role_id'] = $row['role_id'];
        $_SESSION['user_id'] = $row['id'];

        if ($row['role_id'] == 1) {
            header("Location: admin-dashboard.php");
        } else if ($row['role_id'] ==2){
             header("Location: doctor-dashboard.php");
        }
        else{
            header("Location: $redirect");
        }
        exit;

    } else {
        echo "<script>alert('The email or password is incorrect. Please try again!')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
        body {
            display: block !important;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-image: url(images/background.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            }

            .container {
            position: relative;
            width: 60%;
            margin: 0px auto;
            padding: 25px;
            background-color: #9bc9e4;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            top:160px;
            }

            .login-div{
                 display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                min-height: 80vh;
            }
            @media (max-width: 580px) {

                .login-div {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    padding-top: 0;
                }
                .container{
                    width:300px;
                    height:auto;
                    top:0;
                }

                .guest-explore {
                    margin: 20px auto;
                    text-align: center;
                }
                .container header {
                     font-size: 20px;
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
                .guest-button{
                   font-size: 12px;
                   margin-left: 160px;
                }
              
            }
     </style>
</head>
<body>
    <div class="login-div">
        <section class="container">
            <header>Please login to have access on our website!</header>
            <form action="?redirect=<?= htmlspecialchars($redirect) ?>" method="post" class="form">
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" placeholder="username@gmail.com" name="email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" placeholder="********" name="password" required>
                </div>
                <div class="input-group">
                    <button name="submit">Submit</button>
                </div>
                <p class="login-register-text">Don't have an account? <a href="register.php">Register now!</a></p>
            </form>
        </section>

        <section class="guest-explore">
            <a href="index.php" class="guest-button">Continue as Guest</a>
        </section>
    </div>
</body>
</html>


