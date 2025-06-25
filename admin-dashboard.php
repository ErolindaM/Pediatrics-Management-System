<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
  include 'includes/functions/connect.php';
  
$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'] ?? null;

if (!$username || $role_id != 1) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
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
        .stats-box{
            font-size:16px;
        }
        .dashboard-box{
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

        .icon-users, .icon-doctors, .icon-appointments, .icon-messages {
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
        .recent-appointments{
            color: #333;
            font-size:22px;
            margin:0;
            padding:0px;
            margin-top:10px;
            margin-bottom:20px;
        }
        .todays-schedule{
            color: #333;
            font-size:22px;
            margin:0;
            padding:0px;
            margin-top:10px;
            margin-bottom:20px;
        }
        .admin-notes{
            color: #333;
            font-size:20px;
            margin:0px;
            padding:0px;
            margin-bottom:20px;
        }
        .stats-actions {
            display: flex;
            padding: 0;
            margin: 0;
        }
        .quick-actions{
            margin: 0px 20px 40px 20px;
            padding-left:190px;
        }
        .quick-actions-heading{
            color: #333;
            padding-left:330px;
            font-size:22px;
        }
        .quick-buttons{
            display: flex; 
            gap: 15px;
            flex-wrap: wrap;
        }
        .welcome-admin{
            margin-left: 20px;
            font-size: 32px;
        }
        .welcome-paragraph{
            margin-left: 40px; 
            color: #666;
            font-size:16px
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
                margin-bottom:20px;
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
            .welcome-admin{
                font-size: 24px;
                margin-left:0;
                text-align:center;
                padding:20px 50px;
            }
            .welcome-paragraph{
                font-size:14px
            }
            .admin-notes{
                font-size:18px;
            }
            .dashboard-box .label{
                font-size:14px;
            }
        }
    </style>
</head>

<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1 class="welcome-admin">Welcome back, <?= htmlspecialchars($username) ?></h1>
    <p class="welcome-paragraph">Here's a quick overview of your system:</p>

    <?php
    $userCount = $doctorCount = $appointmentCount = $emailCount = 0;

    $userQuery = "SELECT COUNT(*) as count FROM users WHERE role_id = 3";
    $result = mysqli_query($conn, $userQuery);
    if ($row = mysqli_fetch_assoc($result)) $userCount = $row['count'];

    $doctorQuery = "SELECT COUNT(*) as count FROM users WHERE role_id = 2";
    $result = mysqli_query($conn, $doctorQuery);
    if ($row = mysqli_fetch_assoc($result)) $doctorCount = $row['count'];

    $appointmentQuery = "SELECT COUNT(*) as count FROM appointments";
    $result = mysqli_query($conn, $appointmentQuery);
    if ($row = mysqli_fetch_assoc($result)) $appointmentCount = $row['count'];

    $messageQuery = "SELECT COUNT(*) as count FROM messages";
    $result = mysqli_query($conn, $messageQuery);
    if ($row = mysqli_fetch_assoc($result)) $emailCount = $row['count'];
    ?>

    <div style="margin: 40px 20px;">
        <h2 class="admin-notes">Admin Notes</h2>
        <p class="remember">
            📢 Don’t forget to check the messages sent by patients through the contact form this week.
        </p>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-box first">
            <div class="icon icon-users">👤</div>
            <div class="number"><?= $userCount ?></div>
            <div class="label">Users</div>
        </div>
        <div class="dashboard-box second">
            <div class="icon icon-doctors">🩺</div>
            <div class="number"><?= $doctorCount ?></div>
            <div class="label">Doctors</div>
        </div>
        <div class="dashboard-box third">
            <div class="icon icon-appointments">📅</div>
            <div class="number"><?= $appointmentCount ?></div>
            <div class="label">Appointments</div>
        </div>
        <div class="dashboard-box fourth">
            <div class="icon icon-messages">✉️</div>
            <div class="number"><?= $emailCount ?></div>
            <div class="label">Messages</div>
        </div>
    </div>

    <div class="stats-actions">
        <div class="recent-box" style="margin: 40px 20px;">
            <h2 class="recent-appointments">Recent Activities</h2>
            <ul style="list-style: none; padding-left: 0; font-size:18px;">
                <?php
                $latestUser = 'No users yet';
                $result = mysqli_query($conn, "SELECT username FROM users WHERE role_id = 3 ORDER BY id DESC LIMIT 1");
                if ($row = mysqli_fetch_assoc($result)) $latestUser = $row['username'];

                $latestDoctor = 'No doctors yet';
                $result = mysqli_query($conn, "SELECT username FROM users WHERE role_id = 2 ORDER BY id DESC LIMIT 1");
                if ($row = mysqli_fetch_assoc($result)) $latestDoctor = $row['username'];

                $appointmentInfo = 'No appointments yet';
                $result = mysqli_query($conn, "SELECT name, lastname, appointment_date FROM appointments ORDER BY id DESC LIMIT 1");
                if ($row = mysqli_fetch_assoc($result)) {
                    $appointmentInfo = htmlspecialchars($row['name'] . ' ' . $row['lastname']) . ' on ' . htmlspecialchars($row['appointment_date']);
                }

                $latestMessage = 'No messages yet';
                $result = mysqli_query($conn, "SELECT name FROM messages ORDER BY id DESC LIMIT 1");
                if ($row = mysqli_fetch_assoc($result)) $latestMessage = $row['name'];
                ?>
                <li>👤 Latest user registered: <strong><?= htmlspecialchars($latestUser) ?></strong></li>
                <li>🩺 Latest doctor added: <strong><?= htmlspecialchars($latestDoctor) ?></strong></li>
                <li>📅 Latest appointment: <strong><?= $appointmentInfo ?></strong></li>
                <li>✉️ Latest message from: <strong><?= htmlspecialchars($latestMessage) ?></strong></li>
            </ul>
        </div>

        <div class="stats-box" style="margin:40px 20px;">
            <h2 class="todays-schedule">Today's Stats</h2>
            <?php
            $today = date('Y-m-d');
            $appointmentsToday = 0;
            $result = mysqli_query($conn, "SELECT COUNT(*) as count_today FROM appointments WHERE DATE(appointment_date) = '$today'");
            if ($row = mysqli_fetch_assoc($result)) $appointmentsToday = $row['count_today'];
            ?>
            <p>📅 Appointments today: <strong><?= $appointmentsToday ?></strong></p>
            <p>📝 Pending approvals: <strong>0</strong></p>
        </div>
    </div>

    <div class="quick-actions">
        <h2 class="quick-actions-heading">Quick Actions</h2>
        <div class="quick-buttons">
            <a href="manage-doctors.php" style="padding: 10px 20px; background: #2196F3; color: #fff; border-radius: 5px; text-decoration: none;">👨‍⚕️Manage Doctors</a>
            <a href="manage-users.php" style="padding: 10px 20px; background: #4CAF50; color: #fff; border-radius: 5px; text-decoration: none;">👥 Manage Users</a>
            <a href="manage-appointments.php" style="padding: 10px 20px; background: #FF5722; color: #fff; border-radius: 5px; text-decoration: none;">📅 Manage Appointments</a>
            <a href="manage-messages.php" style="padding: 10px 20px; background: #9C27B0; color: #fff; border-radius: 5px; text-decoration: none;">✉️ Manage Messages</a>
        </div>
    </div>
    <?php include "includes/templates/footer.php"; ?>
</div>
<script src="main.js"></script>
</body>
</html>