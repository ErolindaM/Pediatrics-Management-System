<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit();
}
include "includes/functions/connect.php";

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
    <title>Manage Feedbacks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <style>
        body {
            background: #f5f7fa;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            margin-top: 50px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 1150px;
            margin: 0 auto 40px;
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
        
        /* Mobile Card Styles */
        .feedbacks-list {
            display: none; /* Hidden by default */
        }
        .feedback-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .feedback-field {
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
        .feedback-content {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 14px;
        }
        .feedbacks-paragraph {
            text-align: center; 
            font-size: 16px; 
            color: #666;
            margin-bottom: 20px;
        }
        .rating-stars {
            color: #FFD700; /* Gold color for stars */
            font-size: 16px;
        }
        .footer{
            height:55px;
            padding-top:30px;
            bottom:0;
            width:100%;
            padding-left:480px;
            justify-content:left;
        }
        @media (max-width: 768px) {
            .card {
                margin: 20px;
                padding: 20px;
            }

            th, td {
                font-size: 16px;
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
            
            .feedbacks-paragraph {
                font-size: 14px;
                padding: 0 15px;
            }
            
            .card {
                margin: 15px;
                padding: 15px;
            }
            
            /* Hide table on mobile */
            table {
                display: none;
            }
            
            /* Show cards on mobile */
            .feedbacks-list {
                display: block;
            }
            
            .feedback-card {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .feedback-field {
                font-size: 14px;
            }
            
            .field-label {
                min-width: 70px;
            }
            
            .rating-stars {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/admin-sidebar.php"; ?>

<div class="content">
    <h1>Manage Feedbacks</h1>
    <p class="feedbacks-paragraph">Below is a list of all doctor feedback entries submitted by users.</p>

    <div class="card">
        <h3>Feedback Table</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Submitted At</th>
            </tr>
            <?php
            $query = "
                SELECT df.id, 
                       u1.username AS patient_name, 
                       u2.username AS doctor_name, 
                       df.rating, 
                       df.comment, 
                       df.created_at
                FROM doctor_feedback df
                JOIN users u1 ON df.user_id = u1.id
                JOIN users u2 ON df.doctor_id = u2.id
                ORDER BY df.created_at DESC
            ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>" . htmlspecialchars($row['patient_name']) . "</td>
                            <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                            <td><span class='rating-stars'>" . str_repeat('⭐', $row['rating']) . "</span></td>
                            <td>" . nl2br(htmlspecialchars($row['comment'])) . "</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No feedback entries found</td></tr>";
            }
            ?>
        </table>
        
        <!-- Mobile Cards -->
        <div class="feedbacks-list">
            <?php
            // Reset pointer to loop through results again
            mysqli_data_seek($result, 0);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="feedback-card">
                            <div class="feedback-field">
                                <span class="field-label">ID:</span>
                                <span class="field-value">' . $row['id'] . '</span>
                            </div>
                            <div class="feedback-field">
                                <span class="field-label">Patient:</span>
                                <span class="field-value">' . htmlspecialchars($row['patient_name']) . '</span>
                            </div>
                            <div class="feedback-field">
                                <span class="field-label">Doctor:</span>
                                <span class="field-value">' . htmlspecialchars($row['doctor_name']) . '</span>
                            </div>
                            <div class="feedback-field">
                                <span class="field-label">Rating:</span>
                                <span class="field-value"><span class="rating-stars">' . str_repeat('⭐', $row['rating']) . '</span></span>
                            </div>
                            <div class="feedback-field">
                                <span class="field-label">Submitted:</span>
                                <span class="field-value">' . $row['created_at'] . '</span>
                            </div>
                            <div class="feedback-content">
                                <strong>Comment:</strong><br>
                                ' . nl2br(htmlspecialchars($row['comment'])) . '
                            </div>
                        </div>';
                }
            } else {
                echo '<div class="feedback-card" style="text-align:center;">No feedback entries found</div>';
            }
            ?>
        </div>
    </div>

    <?php include "includes/templates/footer.php"; ?>
</div>
<script src="main.js"></script>
</body>
</html>