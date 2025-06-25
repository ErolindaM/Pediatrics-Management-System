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
        <title>Home</title>
        <link rel="stylesheet" href="menus.css">
        <style>
          :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #f8fafc;
            --text-color: #334155;
            --light-gray: #e2e8f0;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
          }
          
          body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-color);
            background-color: #f9fafb;
            line-height: 1.6;
            margin: 0;
            padding: 0;
          }
          
         h1, h2 {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 2rem 0 1rem;
            position: relative;
            padding-bottom: 1rem;
          }
          .make-appointments-anytime{
            color: rgb(40, 40, 167); 
            font-size: 32px; 
            margin-bottom: 20px;
          }
          .anytime-paragraph{
            font-size: 16px; 
            color: #333; 
            margin-bottom: 25px;"
          }
           @media (max-width: 1313px) {
            .content {
              margin-left: 0;
            }
            .logo img{
                width:100px;
            }
            .title h1{
                font-size:70px;
                display:flex;
                flex-direction:column;
            }
             .hero-paragraph {
              font-size: 12px;
            }
          }

          @media (max-width: 768px) {
            .container, .services {
              grid-template-columns: 1fr;
              max-width: 500px;
            }
            
            .title h1{
                font-size:50px;
            }
            
            .hero-paragraph {
              font-size: 12px;
            }
          }

          @media (max-width: 580px) {
            .content{
                left:0%
            }
            .diagnose-item, .service-item {
              padding: 1.5rem;
            }
            
            .make-appointment {
              padding: 2rem 1rem;
            }
            .title h1{
                font-size:24px;
            }
            .hero-paragraph {
              font-size: 8px;
              padding: 10px;
              margin: 10px;
            }
            .logo img{
                width:100px;
            }
            .hero{
                height:400px
            }
            .home-paragraph{
                font-size:14px;
            }
            .diagnoses{
                padding:10px;
            }
            .home-title{
                font-size:20px;
            }
            .container, .services{
                display:flex;
                flex-direction:column;
                justify-content:center;
                align-items:center;
            }
            .diagnose-item h4{
                font-size:18px;
            }
            .service-item h4{
                font-size:18px;
            }
            .make-appointments-anytime{
                font-size:20px;
            }
            .anytime-paragraph{
                font-size:12px;
            }
            .make-appointments{
                width:150px;
                height:35px;
                font-size:12px;
                margin:0;
                padding:0px;
            }
            .footer{
                font-size:10px;
                height:15px;
            }
            .diagnose-item img{
                width:30px;
            } 
            .diagnose-item h4{
                font-size:14px;
            }
            .diagnose-item p{
                font-size:12px;
            }
            .service-item p{
                font-size:12px;
            }
          }
         
        </style>
    </head>

    <body>

    <?php include "includes/templates/sidebar.php";?>

        <div class="content">
            <div class="hero">
                <div class="title">
                    <h1>Welcome to </h1>
                    <h1 class="name"> KIDS CARE!</h1>
                </div>
                <div class="logo">
                    <img src="images/logo.png">
                </div>
                <div class="description">
                    <p class="hero-paragraph">At Kids Care, we are dedicated to providing exceptional medical care for children of all ages. 
                        Our team of experienced pediatricians and friendly staff are committed to ensuring your child’s health and well-being
                        in a warm and welcoming environment. From routine check-ups to specialized treatments, we offer a comprehensive range
                        of services designed to meet the unique needs of your child. Join us at Kids Care, where we prioritize your child’s health
                        and happiness, making every visit a positive experience.</p>
                </div>
            </div>

    
            <div class="diagnoses">
                <p class="home-paragraph">OUR TREATMENTS</p>
                <h1 class="home-title">Diagnoses that we treat</h1>
                <div class="container">
                    <div class="diagnose-item">
                        <img src="images/kid.png">
                        <h4>Well-Child Check-Ups</h4>
                        <p>Regular wellness exams to monitor growth and development.</p>
                    </div>
                    <div class="diagnose-item">
                        <img src="images/sick.png">
                        <h4>Sick Visits</h4>
                        <p>Prompt care for acute illnesses such as colds, flu, and infections.</p>
                    </div>
                    <div class="diagnose-item">
                        <img src="images/cough.png">
                        <h4>Chronic Disease Management</h4>
                        <p>Ongoing care and support for chronic conditions like asthma.</p>
                    </div>
                    <div class="diagnose-item">
                        <img src="images/visit.png">
                        <h4>Ear, Nose, and Throat Disorders</h4>
                        <p>Addressing issues such as ear infections, strep throat, and sinusitis.</p>
                    </div>
                    <div class="diagnose-item">
                        <img src="images/allergies.png">
                        <h4>Allergies and Asthma</h4>
                        <p>Management of allergic reactions and chronic asthma symptoms.</p>
                    </div>
                    <div class="diagnose-item">
                        <img src="images/baby.png">
                        <h4>Gastrointestinal Issues</h4>
                        <p>Care for conditions like stomach flu, constipation, and reflux.</p>
                    </div>
                </div>
            </div>
        
            <div class="services-content">
                <p class="home-paragraph">OUR SERVICES</p>
                <h1 class="home-title">Services that we offer</h1>
                <div class="services">
                    <div class="service-item">
                        <img src="images/icons8-vaccination-50.png" style="width:50px">
                        <h4>Vaccinations</h4>
                        <p>Provide vaccination schedules, ensuring children are protected against preventable diseases.</p>
                    </div>
                    <div class="service-item">
                        <img src="images/icons8-emergency-50.png" style="width:50px">
                        <h4>Urgent Care</h4>
                        <p>Offer immediate medical attention for minor injuries and urgent pediatric conditions.</p>
                    </div>
                    <div class="service-item">
                            <img src="images/icons8-assessment-50.png" style="width:50px">
                        <h4>Assessments</h4>
                        <p>Monitor and support children's health addressing any developmental delays early.</p>
                    </div>
                    <div class="service-item">
                            <img src="images/icons8-counseling-50.png" style="width:50px">
                        <h4>Counseling</h4>
                        <p>Provide personalized guidance and education on healthy habits for children with specific health conditions.</p>
                    </div>
                </div>
            </div>

        

            <div class="make-appointment" style="text-align: center; padding: 40px;border-radius: 12px;">
                <h1 class="make-appointments-anytime">Make appointments anytime you want!</h1>
                <p class="anytime-paragraph">
                    Whether it's a routine check-up or a specialist consultation, booking is just a click away.
                    Choose the time that works best for you.
                </p>
                <a href="appointments.php">
                    <button class="make-appointments" name="make-appointment">
                        Make an Appointment
                    </button>
                </a>
            </div>


             <?php include "includes/templates/footer.php";?>

        </div> 
    <script src="main.js"></script>
    </body>
</html>