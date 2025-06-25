<?php
session_start();

if (!isset($_SESSION['username'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: login.php?redirect=$current_page");
    exit;
}

$username = $_SESSION['username'] ?? null;
include 'includes/functions/connect.php';

// Variabël për të ruajtur mesazhin e statusit për SweetAlert
$swal_script = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST["doctor"];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    $checkDoctor = $conn->prepare("SELECT id FROM users WHERE id = ? AND role_id = 2");
    $checkDoctor->bind_param("i", $doctor_id);
    $checkDoctor->execute();
    $result = $checkDoctor->get_result();

    if ($result->num_rows === 0) {
        $swal_script = "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Doktori i zgjedhur nuk ekziston.',
                    willClose: () => {
                        window.location.href = 'feedback.php';
                    }
                });
            });
            </script>
        ";
    } else {
        $stmt = $conn->prepare("INSERT INTO doctor_feedback (user_id, doctor_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $doctor_id, $rating, $comment);
        if ($stmt->execute()) {
            $swal_script = "
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Thank you for your feedback!',
                        willClose: () => {
                            window.location.href = 'feedback.php';
                        }
                    });
                });
                </script>
            ";
        } else {
            $swal_script = "
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error while feedback was being sent!',
                        willClose: () => {
                            window.location.href = 'feedback.php';
                        }
                    });
                });
                </script>
            ";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f4f6f9;
        }
        h1{
            font-size:32px!important;
        }
        .footer{
            height:60px;
            align-items:center;
            justify-content:center;
            padding-top:30px;
        }
        .feedback-form {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .rating .star {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating .star.hover,
        .rating .star.selected {
            color: gold;
        }
        .btn-outline-primary{
            background-color:#5458c4;
        }
        .feedback-form h3{
            color:blue;
            font-size:22px;
        }
        .feedback-paragraph{
            color:#666;
            padding-bottom:50px;
            font-size:16px;
            margin-top:0px;
            text-align:center;
        }
        .form-label{
            font-size:16px;
        }
        .form-select{
            font-size:14px;
        }
        .btn-primary{
            height:40px;
            font-size:14px;
            padding:0;
        }
        @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:15px;
            }
            .content h1{
                font-size:24px;
            }
            .feedback-paragraph{
                font-size:14px;
            }
            .card{
                margin-left:0px;
            }
             .feedback-form {
                max-width: 320px;
            }
            .btn-outline-primary{
                height:30px;
                width:200px;
                font-size:12px;
                padding:0px;
            }
            .card-title{
                font-size:18px;
            }
            .card-body p{
                font-size:12px;
            }
            .feedback-form h3{
                font-size:18px;
            }
            .form-label{
                font-size:14px;
            }
            .btn-primary{
                 height:30px;
                width:200px;
                font-size:12px;
                padding:0px;
            }
        }
    </style>
</head>
<body>

<?php include "includes/templates/sidebar.php"; ?>

<div class="content">

    <div class="container" style="flex-direction:column;justify-content:center;align-items:center">
        <h1 class="ml-0 text-center">Doctor's ratings</h1>
        <p class="feedback-paragraph">See what others are saying and rate your doctor to help improve healthcare for everyone.</p>

        <div class="row" style="width:100%;margin:0;padding:0;justify-content:center;align-items:center">
                <?php
                $doctors = $conn->query("
                    SELECT u.id, u.username, 
                        AVG(df.rating) as avg_rating, 
                        COUNT(df.id) as total_feedbacks
                    FROM users u
                    LEFT JOIN doctor_feedback df ON u.id = df.doctor_id
                    WHERE u.role_id = 2
                    GROUP BY u.id
                ");

                while ($doc = $doctors->fetch_assoc()):
                    $avg = round($doc['avg_rating'], 1);
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($doc['username']) ?></h5>
                            <div class="mb-2">
                                <?php
                                $fullStars = floor($avg);
                                $halfStar = $avg - $fullStars >= 0.5;
                                for ($i = 0; $i < $fullStars; $i++) echo '<span style="color: gold; font-size: 20px;">★</span>';
                                if ($halfStar) echo '<span style="color: gold; font-size: 20px;">☆</span>';
                                for ($i = $fullStars + $halfStar; $i < 5; $i++) echo '<span style="color: #ccc; font-size: 20px;">☆</span>';
                                ?>
                            </div>
                            <p class="mb-1"><?= $avg ?> / 5</p>
                            <p><?= $doc['total_feedbacks'] ?> feedback(s)</p>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#comments<?= $doc['id'] ?>">View comments</button>
                            <div class="collapse mt-3" id="comments<?= $doc['id'] ?>">
                                <?php
                                $comments = $conn->prepare("SELECT comment FROM doctor_feedback WHERE doctor_id = ?");
                                $comments->bind_param("i", $doc['id']);
                                $comments->execute();
                                $res = $comments->get_result();
                                while ($row = $res->fetch_assoc()):
                                ?>
                                    <div class="text-start border p-2 rounded mb-2" style="font-size: 14px; background: #f8f9fa;">
                                        <?= htmlspecialchars($row['comment']) ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

    <hr class="my-5">
        <div class="feedback-form">
        <h3 class="mb-4 text-center">Leave a feedback for any doctor!</h3>

        <form method="post" action="">
            <div class="mb-3">
                <label for="doctor" class="form-label">Pick the doctor</label>
                <select class="form-select" name="doctor" id="doctor" required>
                    <option value="">-- Pick --</option>
                    <?php
                    $result = $conn->query("SELECT id, username FROM users WHERE role_id = 2");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['username']) . "</option>";
                    }
                    ?>
                </select>
            </div>

           <div class="mb-3">
                <label class="form-label">Rating</label>
                <div class="rating d-flex gap-1">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" hidden required>
                        <label class="star" for="star<?= $i ?>" data-value="<?= $i ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>


            <div class="mb-3">
                <label class="form-label">Your comment</label>
                <textarea class="form-control" name="comment" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Send feedback</button>
        </form>
    </div>
    <?php include "includes/templates/footer.php"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="main.js"></script>
<script>
    const stars = document.querySelectorAll('.rating .star');
    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = parseInt(star.getAttribute('data-value'));
            stars.forEach(s => {
                s.classList.remove('hover');
                if (parseInt(s.getAttribute('data-value')) <= value) {
                    s.classList.add('hover');
                }
            });
        });

        star.addEventListener('mouseout', () => {
            stars.forEach(s => s.classList.remove('hover'));
        });

        star.addEventListener('click', () => {
            const value = parseInt(star.getAttribute('data-value'));
            document.getElementById('star' + value).checked = true;
            stars.forEach(s => {
                s.classList.remove('selected');
                if (parseInt(s.getAttribute('data-value')) <= value) {
                    s.classList.add('selected');
                }
            });
        });
    });
</script>

<?php 
// Shfaq SweetAlert nëse ka mesazh
if (!empty($swal_script)) {
    echo $swal_script;
}
?>
</body>
</html>