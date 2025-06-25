<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$doctor_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role_id'] ?? null;

if (!$username || $role_id != 2) { 
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        .dashboard-box {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            flex: 1 1 calc(25% - 20px);
            text-align: center;
            padding: 30px 20px;
            transition: transform 0.2s ease;
            min-width: 200px;
        }

        .recent-box, .stats-box {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 30px 20px;
            min-width: 590px;
            font-size: 16px;
        }

        .dashboard-box:hover {
            transform: translateY(-5px);
        }

        .dashboard-box .icon {
            font-size: 30px;
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
        }

        .icon-appointments, .icon-today, .icon-upcoming, .icon-patients {
            background-color: #ffffff;
        }

        .dashboard-box.first { background-color: #4CAF50; }
        .dashboard-box.second { background-color: #2196F3; }
        .dashboard-box.third { background-color: #FF5722; }
        .dashboard-box.fourth { background-color: #9C27B0; }

        .dashboard-box .number {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .dashboard-box .label {
            font-size: 16px;
            color: white;
            margin-top: 5px;
        }

        .stats-actions {
            display: flex;
            padding: 0;
            margin: 0;
        }
        .quick-actions{
            margin: 0px 20px 40px 20px; 
            padding-left:300px;
        }
        .recent-appointments{
            color: #333;
            font-size:22px;
            margin:0;
            padding:0px;
            margin-top:10px;
            margin-bottom:20px;
        }
        .quick-actions-heading{
            color: #333;
            padding-left:230px;
            font-size:22px;
        }
        .quick-buttons{
            display: flex; 
            gap: 15px; 
            flex-wrap: wrap;
            padding-left:120px
        }
        .todays-schedule{
            color: #333;
            font-size:22px;
            margin:0;
            padding:0px;
            margin-top:10px;
            margin-bottom:20px;
        }
        .welcome-dr{
            margin-left: 40px;
            font-size: 32px;
        }
        .welcome-paragraph{
            margin-left: 40px; 
            color: #666;
            font-size:16px
        }
        .dr-notes{
            color: #333;
            font-size:20px;
            margin:0px;
            padding:0px;
            margin-bottom:20px;
        }
        .footer{
            height:55px;
            padding-top:30px;
        }
        .remember{
            background-color: #f9f9f9; 
            padding: 15px; 
            border-left: 4px solid #2196F3;
            font-size:14px;
        }
        .recent-box li{
            font-size:16px;
        }
        .stats-box{
            font-size:16px;
        }
        @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:30px;
                padding-top:22px;
            }
            .stats-actions {
                display: flex;
                flex-direction:column;
            }
            .quick-actions{
                margin: 0px; 
                padding:0
            }
            .dashboard-box {
                min-width:135px;
            }
            .recent-box{
                min-width:300px;
            }
            .recent-box li{
                font-size:14px;
            }
            .recent-appointments{
                font-size:20px;
            }
            .stats-box{
                min-width:300px;
            }
            .quick-actions-heading{
                padding-left:0;
                margin:0;
                font-size:18px;
                text-align:center;
            }
            .quick-buttons{
                padding:0;
                font-size:12px;
                padding-bottom:20px;
                justify-content:center;
            }
            .todays-schedule{
                font-size:18px;
            }
            .stats-box p{
                font-size:14px;
            }
            .welcome-dr{
                font-size: 24px;
                margin-left:0;
                text-align:center;
                padding:20px 50px;
            }
            .welcome-paragraph{
                font-size:14px
            }
            .dr-notes{
                font-size:18px;
            }
            .dashboard-box .label{
                font-size:14px;
            }
        }
    </style>
</head>

<body>

<?php include "includes/templates/doctor-sidebar.php"; ?>

<div class="content">
    <h1 class="welcome-dr">Welcome, Dr. <?= htmlspecialchars($username) ?></h1>
    <p class="welcome-paragraph">Here's your practice overview:</p>

    <?php
    include 'includes/functions/connect.php';

    $totalAppointments = $todayAppointments = $upcomingAppointments = $totalPatients = 0;

    $query = "SELECT COUNT(*) as count FROM appointments WHERE doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) $totalAppointments = $row['count'];


    $today = date('Y-m-d');
    $query = "SELECT COUNT(*) as count FROM appointments 
              WHERE doctor_id = '$doctor_id' AND DATE(appointment_date) = '$today'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) $todayAppointments = $row['count'];


    $nextWeek = date('Y-m-d', strtotime('+7 days'));
    $query = "SELECT COUNT(*) as count FROM appointments 
              WHERE doctor_id = '$doctor_id' 
              AND DATE(appointment_date) BETWEEN '$today' AND '$nextWeek'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) $upcomingAppointments = $row['count'];

    
    $query = "SELECT COUNT(DISTINCT email) as count FROM appointments WHERE doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) $totalPatients = $row['count'];
    ?>

    <div style="margin: 40px 40px;">
        <h2 class="dr-notes">Doctor Notes</h2>
        
        <p class="remember">
            💡 Remember to review your upcoming appointments and patient notes before each session.
        </p>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-box first">
            <div class="icon icon-appointments">📅</div>
            <div class="number"><?= $totalAppointments ?></div>
            <div class="label">Total Appointments</div>
        </div>
        <div class="dashboard-box second">
            <div class="icon icon-today">🩺</div>
            <div class="number"><?= $todayAppointments ?></div>
            <div class="label">Today's Appointments</div>
        </div>
        <div class="dashboard-box third">
            <div class="icon icon-upcoming">⏳</div>
            <div class="number"><?= $upcomingAppointments ?></div>
            <div class="label">Upcoming (7 days)</div>
        </div>
        <div class="dashboard-box fourth">
            <div class="icon icon-patients">👥</div>
            <div class="number"><?= $totalPatients ?></div>
            <div class="label">Unique Patients</div>
        </div>
    </div>

    <div class="stats-actions">
        <div class="recent-box" style="margin: 40px 20px;">
            <h2 class="recent-appointments">Recent Appointments</h2>
            <ul style="list-style: none; padding-left: 0; font-size:18px;">
                <?php


                $lastAppointment = 'No appointments yet';
                $query = "SELECT name, lastname, appointment_date FROM appointments 
                          WHERE doctor_id = '$doctor_id' AND appointment_date < NOW() 
                          ORDER BY appointment_date DESC LIMIT 1";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    $lastAppointment = htmlspecialchars($row['name'] . ' ' . $row['lastname']) . 
                                      ' on ' . htmlspecialchars($row['appointment_date']);
                }


                $nextAppointment = 'No upcoming appointments';
                $query = "SELECT name, lastname, appointment_date FROM appointments 
                          WHERE doctor_id = '$doctor_id' AND appointment_date > NOW() 
                          ORDER BY appointment_date ASC LIMIT 1";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    $nextAppointment = htmlspecialchars($row['name'] . ' ' . $row['lastname']) . 
                                      ' on ' . htmlspecialchars($row['appointment_date']);
                }

            
                $frequentPatient = 'No patient data';
                $query = "SELECT name, lastname, COUNT(*) as visits 
                          FROM appointments 
                          WHERE doctor_id = '$doctor_id'
                          GROUP BY email 
                          ORDER BY visits DESC LIMIT 1";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    $frequentPatient = htmlspecialchars($row['name'] . ' ' . $row['lastname']) . 
                                      ' (' . $row['visits'] . ' visits)';
                }
                ?>
                <li>⏪ Last appointment: <strong><?= $lastAppointment ?></strong></li>
                <li>⏩ Next appointment: <strong><?= $nextAppointment ?></strong></li>
                <li>⭐ Most frequent patient: <strong><?= $frequentPatient ?></strong></li>
            </ul>
        </div>

        <div class="stats-box" style="margin:40px 20px;">
            <h2 class="todays-schedule">Today's Schedule</h2>
            <?php
            $query = "SELECT name, lastname, appointment_date 
                      FROM appointments
                      WHERE doctor_id = '$doctor_id' AND DATE(appointment_date) = '$today'
                      ORDER BY appointment_date ASC";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo '<ul style="list-style: none; padding-left: 0;">';
                while ($row = mysqli_fetch_assoc($result)) {
                    $time = date('H:i', strtotime($row['appointment_date']));
                    echo '<li>🕒 ' . $time . ' - <strong>' . 
                         htmlspecialchars($row['name'] . ' ' . $row['lastname']) . '</strong></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No appointments scheduled for today.</p>';
            }
            ?>
        </div>
    </div>

    <div class="quick-actions">
        <h2 class="quick-actions-heading">Quick Actions</h2>
        <div class="quick-buttons">
            <a href="doctor-appointments.php" style="padding: 10px 20px; background: #9C27B0;; color: #fff; border-radius: 5px; text-decoration: none;">📅 View Appointments</a>
            <a href="patients.php" style="padding: 10px 20px; background: #4CAF50; color: #fff; border-radius: 5px; text-decoration: none;">👥 View Patients</a>
        </div>
    </div>

    <?php include "includes/templates/footer.php"; ?>
</div>
<script src="main.js"></script>
</body>
</html>