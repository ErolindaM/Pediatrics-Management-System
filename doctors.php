<?php

session_start();

$username = $_SESSION['username'] ?? null;

?>

<!DOCTYPE html>
<html>
    <head>     
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
        <title>Doctors</title>
        <link rel="stylesheet" href="menus.css">
        <style>
             @media (max-width: 580px) {
            .content{
                left:0%
            }
              .footer{
                font-size:10px;
                height:15px;
            }
            .doctors-paragraph {
                font-size:12px;
            }

        }
        </style>
    </head>

    <body>

    <?php include "includes/templates/sidebar.php";?>

        <div class="content">

            <div class="doctor-talk">
                <div class="doctor-talk-info">
                    <h1>Meet Our Passionate Pediatric Professionals</h1>
                </div>
            
                <div class="doctor">
                    <div class="doctor1">
                        <img src="images/doctori1.webp">
                        <h4>Dr. Arta Kryeziu</h4>
                        <p>Pediatric Cardiology</p>
                        <i class="quote">"Every child, everywhere, deserves a healthy start, and we are here to provide the care and support they need to thrive."</i>
                    </div>
                    <div class="doctor1">
                        <img src="images/doctor2.jpg">
                        <h4>Dr. Artan Gashi</h4>
                        <p>Pediatric Neurology</p>
                        <i class="quote">"Caring for your child as if they were our own, we prioritize their health and happiness in every decision we make."</i>
                    </div>
                    <div class="doctor1">
                        <img src="images/doctor3.jpg">
                        <h4>Dr. Drita Krasniqi</h4>
                        <p>Pediatric Endocrinology</p>
                        <i class="quote">"Growing healthy kids, one visit at a time. We are committed to supporting your child's development and well-being."</i>
                    </div>
                </div>
            </div>

            <div class="doctors-part2">
                <div class="image-doctors">
                    <img src="images/doctor-part.jpg">
                </div>
                <div class="doctors-text">
                    <i>"Children are our future, and their health today shapes the world they will inherit tomorrow. We're here to nurture their well-being, guiding them through each milestone with expertise, compassion, and a commitment to building a healthier tomorrow."</i>
                </div>
            </div>


            <div class="doctors">
                <div class="doctors-info">
                    <h1>Our Team</h1>
                </div>
                <p class="doctors-paragraph">At KIDS CARE, our team of dedicated pediatricians is committed to providing exceptional medical care to children of all ages. Our pediatricians are not only highly trained and experienced in their field but also deeply compassionate and attentive to the unique needs of each child. They work collaboratively to ensure that every child receives personalized, comprehensive care in a warm and friendly environment. Our team stays updated with the latest advancements in pediatric medicine, enabling us to offer cutting-edge treatments and preventive care. We believe in building strong relationships with families, guiding them through every stage of their child's growth and development. Trust in our pediatricians to prioritize your child's health and well-being with the highest standards of medical excellence and heartfelt compassion.</p>
                <img src="images/doctors.png">
            </div>


            <?php include "includes/templates/footer.php";?>


        </div> 
    <script src="main.js"></script>
    </body>
</html>