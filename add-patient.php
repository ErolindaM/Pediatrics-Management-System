<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/functions/connect.php';

$username = $_SESSION['username'] ?? null;
$doctor_id = $_SESSION['user_id'];

if (isset($_POST['add_patient'])) {
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $dob = $_POST['date_of_birth'];

    if (!empty($name) && !empty($lastname)) {
        $stmt = $conn->prepare("INSERT INTO patients (doctor_id, name, lastname, phone, gender, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $doctor_id, $name, $lastname, $phone, $gender, $dob);
        $stmt->execute();
        $stmt->close();

        header("Location: patients.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body { background: #f5f7fa; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 50px;
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
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
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
            padding: 10px 20px;
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
        .gender{
            width:698px;
        }
        .patient-paragraph{
            color:#666;
            padding:20px 40px;
            font-size:16px;
            margin-top:0px;
            text-align:center;
        }
        .footer{
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:490px;
            justify-content:left;
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
            .gender{
                width:200px;
            }
            h1{
                font-size:24px;
                margin-top:40px;
            }
            .patient-paragraph{
                font-size:14px;
            }
            .card{
                width:300px;
            }
            input, select, textarea{
                height:35px;
                font-size:12px;
                padding:5px;
            }
            .footer{
                position: relative;
                font-size:10px;
                height:30px;
                padding-top:22px;
                padding-left:70px;
            }
            .btn{
                height:30px;
                font-size:12px;
                padding:0px;
                display:flex;
                justify-content:center;
                align-items:center;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/doctor-sidebar.php"; ?>

<div class="content">
    <h1>Add New Patient</h1>
    <p class="patient-paragraph">Fill out the form below to register a new patient into the system. Please ensure that all the patient's personal and medical information is accurate and complete. This data will help in managing appointments, tracking medical history, and providing quality care.</p>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth">
            </div>
            
            <div class="btn-container">
                <button type="submit" name="add_patient" class="btn btn-save">Save Patient</button>
                <a href="patients.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
    
<?php include "includes/templates/footer.php"; ?>
</div>
<script src="main.js"></script>
</body>
</html>