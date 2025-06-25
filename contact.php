<?php
session_start();

if (!isset($_SESSION['username'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: login.php?redirect=$current_page");
    exit;
}

$username = $_SESSION['username'];
require 'includes/functions/connect.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/functions/PHPMailer/PHPMailer.php';
require 'includes/functions/PHPMailer/SMTP.php';
require 'includes/functions/PHPMailer/Exception.php';

// Variabël për të ruajtur mesazhin e statusit për SweetAlert
$swal_script = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emaili-yt@gmail.com';
        $mail->Password   = 'numri_i_gjeneruar'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('emaili-yt@gmail.com'); // your receiving email

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "Name: $name\nEmail: $email\n\nSubject: $subject\n\nMessage:\n$message";

        $mail->send();

        // Save to database
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $swal_script = "
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent',
                        text: 'Your message has been sent successfully!',
                        willClose: () => {
                            window.location.href = 'contact.php';
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
                        icon: 'warning',
                        title: 'Partial Success',
                        text: 'Message sent, but failed to save in the database.',
                        willClose: () => {
                            window.location.href = 'contact.php';
                        }
                    });
                });
                </script>
            ";
        }
        $stmt->close();
    } catch (Exception $e) {
        $swal_script = "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Sending Failed',
                    text: 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}',
                    willClose: () => {
                        window.location.href = 'contact.php';
                    }
                });
            });
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <title>Contact</title>
    <link rel="stylesheet" href="menus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .contact-title h1{
            font-size:33px;
        }
          @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:15px;
            }
            .contact-title h1{
                padding-top:10px;
                font-size:24px;
            }
            .contact-title p{
                font-size:14px;
            }
            .contact-form h2{
                font-size:24px;
            }
            .container2{
                margin-left:0px;
                padding-left:0px;
                align-items:baseline;
            }
            .contact-inffo h2{
                font-size:20px;
            }
            .contact-form button{
                font-size:14px;
                width:150px;
            }
            textarea, input[type="text"], input[type="email"]{
                font-size:14px;
            }
            .info-box p{
                font-size:12px;
            }
        }
    </style>
</head>
<body>
    <?php include "includes/templates/sidebar.php"; ?>
    <div class="content">
        <div class="contact-title">
            <h1>Don't hesitate to contact us for anything!</h1>
            <p class="contact-form-paragraph">
               We're here to help! Our dedicated pediatric care team is happy to answer your questions and provide the support you need.
                     Whether you're looking for more information about our services or need help scheduling an appointment, please don't hesitate to reach out. Your child's health and well-being are our top priority.
            </p>
        </div>

        <div class="container2">
            <div class="contact-form">
                <h2>Send us a message</h2>
                <form method="POST" action="contact.php">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Name" required />
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <input type="text" name="subject" placeholder="Subject" required />
                    <textarea name="message" placeholder="Message" rows="5" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>

            <div class="contact-info-wrapper">
                <div class="contact-inffo">
                    <h2 style="color:white">Contact us</h2>
                    <div class="info-box">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Address:</strong> 60 Liman Shala, Prizren</p>
                    </div>
                    <div class="info-box">
                        <i class="fas fa-phone-alt"></i>
                        <p><strong>Phone:</strong> +383 49 484 216</p>
                    </div>
                    <div class="info-box">
                        <i class="fas fa-paper-plane"></i>
                        <p><strong>Email:</strong> erolinda@gmail.com</p>
                    </div>
                    <div class="info-box">
                        <i class="fas fa-globe"></i>
                        <p><strong>Website:</strong> kidscare.com</p>
                    </div>
                </div>
            </div>
        </div>
        <?php include "includes/templates/footer.php"; ?>
    </div>
<script src="main.js"></script>
<?php 
// Shfaq SweetAlert nëse ka mesazh
if (!empty($swal_script)) {
    echo $swal_script;
}
?>
</body>
</html>