<?php
session_start();
$username = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <title>About Us - Kids Care Pediatrics</title>
    <link rel="stylesheet" href="menus.css">
    <style>
        p{
            font-size:14px;
        }
        section {
            margin-bottom: 60px;
        }
        .why-us, .hero-about, .pricing-heading {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            align-items: center;
        }
        .why-img img, .hero-info02 img {
            width: 100%;
            max-width: 388px;
            height:300px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top:30px;
            margin-left:30px;
        }
        .hero-info02 img{
            margin-right:30px;
        }
        .why-info, .hero-info01, .pricing-info2 {
            flex: 1;
        }
        .why-title, .pricing-info h1, .pricing-info2 h1, .hero-info01 h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #0c4e91;
        }
        .numbers-2 {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .numbers-2 div {
            flex: 1;
            min-width: 160px;
            margin: 10px;
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .prices {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .prices > div {
            flex: 1;
            min-width: 280px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .visit-button-white, .visit-button-blue, .hero-info01 button {
            background: #0c4e91;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin: 10px 0;
        }
        .visit-button-white:hover, .visit-button-blue:hover, .hero-info01 button:hover {
            background: #093a6b;
        }
        .advantages {
            margin-left: 20px;
            line-height: 1.5em;
        }
        .no-underline {
            text-decoration: none;
        }
        .why-title-paragraph{
            font-size:16px;
        }
        .success-numbers{
            font-size:32px
        }
        .transparent-paragraph{
                font-size:30px;
                font-family:'Papyrus'
        }
        .dedicated-paragraph{
                font-size:16px;
                color:#334155
        }
        @media (max-width: 768px) {
            .why-us, .hero-about, .pricing-heading {
                flex-direction: column;
                text-align: center;
            }
        }
           @media (max-width: 580px) {
            .content{
                left:0%
            }
            p{
            font-size:12px;
            }
            .why-img img, .hero-info02 img{
                width: 69%;
                height: 195px;
                margin: 0;
                margin-top:20px;
            }
            .why-title, .pricing-info h1, .pricing-info2 h1, .hero-info01 h1{
                font-size:20px;
                color:rgb(40, 40, 167);
            }
            .pricing-info2 p{
                font-size:18px;
            }
             .why-title-paragraph{
            font-size:12px;
            padding:10px
            }
            .why-info{
                display:flex;
                flex-direction:column;
                justify-content:center;
                align-items:center;
                margin-right:0;
            }
            .numbers{
                flex-direction:column;
            }
            .numbers p{
                 justify-content:center;
                align-items:center;
                font-size:14px;
            }
            .success-numbers{
                font-size:20px;
                align-items:baseline;
            }
            .numbers-1{
                padding-left:0;
                display:flex;
                flex-direction:column;
                justify-content:center;
                align-items:center;
                height:auto;
            }
            .numbers-2{
                margin-left:0px
            }
            .prices{
                flex-wrap:nowrap;
            }
            .prices-headings-3{
                font-size:20px;
                padding:0;
                margin:0;
            }
            .visit-button-white, .visit-button-blue, .hero-info01 button{
                width:200px;
                height:40px;
                font-size:12px;
            }
            .no-underline{
                display:flex;
                flex-direction:column;
                justify-content:center;
                align-items:center;
            }
            .advantages li{
                font-size:12px;
            }
            .transparent-paragraph{
                font-size:20px;
                font-family:'Papyrus'
            }
            .pricing-image2 img{
                width:60px;
                height:170px;
                padding-top:0px;
                margin:0;
                padding:0 0 20px 0!important;
            }
            .dedicated-paragraph{
                font-size:12px;
                color:#334155
            }
             .footer{
                font-size:10px;
                height:15px;
            }
        }
    </style>
</head>
<body>
    <?php include "includes/templates/sidebar.php"; ?>

    <main class="content">
        <!-- WHY US Section -->
        <section class="why-us">
            <div class="why-img">
                <img src="images/why.jpg" alt="Why Choose Us">
            </div>
            <div class="why-info">
                <h1 class="why-title" style="margin-left:0">Why Choose Our Pediatrics?</h1>
                <p class="why-title-paragraph">
                        At KIDS CARE, we are dedicated to providing the highest quality care for your 
                        child. One of the most important reasons to choose our clinic is our commitment 
                        to using the most advanced and up-to-date medical equipment available. Our cutting-edge
                        technology ensures accurate diagnoses, effective treatments, and a more comfortable experience
                        for your child.                
                </p>
            </div>
        </section>

        <!-- Success Stats Section -->
        <section class="numbers">
            <div class="numbers-1">
                <h2 class="success-numbers">Our Success in Numbers</h2>
                <p>Your child's health, our mission.</p>
            </div>
            <div class="numbers-2">
                <div>
                    <img src="images/icons8-people-50 (1).png" alt="Patients">
                    <h1>90+</h1>
                    <p>Patients per month</p>
                </div>
                <div>
                    <img src="images/icons8-babies-50 (1).png" alt="Children Treated">
                    <h1>88K</h1>
                    <p>Children treated</p>
                </div>
                <div>
                    <img src="images/icons8-certificate-50.png" alt="Experience">
                    <h1>20+</h1>
                    <p>Years of Experience</p>
                </div>
            </div>
        </section>

        <!-- Doctor Profiles via XSLT -->
        <section>
            <?php
                $xml = new DOMDocument;
                $xml->load("doctors.xml");

                $xsl = new DOMDocument;
                $xsl->load("doctors.xsl");

                $proc = new XSLTProcessor;
                $proc->importStyleSheet($xsl);

                echo $proc->transformToXML($xml);
            ?>
        </section>

        <!-- Pricing Section -->
        <section class="pricing">
            <div class="pricing-info">
                <h1>Transparent and Affordable Pricing</h1>
                <p>Clear pricing with no hidden costs. Choose what's best for your child.</p>
            </div>
            <div class="prices">
                <!-- Consultation -->
                <div>
                    <h1 class="prices-headings-3">Initial Consultation</h1>
                    <h3>$43 / Visit</h3>
                    <p class="pricing-paragraphs">Comprehensive health check-up for new patients.</p>
                    <a href="appointments.php" class="no-underline">
                        <button class="visit-button-white">Make Appointment</button>
                    </a>
                    <ul class="advantages">
                        <li>Full health assessment</li>
                        <li>One-on-one with pediatrician</li>
                        <li>Customized health plan</li>
                    </ul>
                </div>

                <!-- Sick Visit -->
                <div>
                    <h1 class="prices-headings-3">Sick Visit</h1>
                    <h3>$50 / Visit</h3>
                    <p class="pricing-paragraphs">Timely care for illness, fever, cold, and more.</p>
                    <a href="appointments.php" class="no-underline">
                        <button class="visit-button-blue">Make Appointment</button>
                    </a>
                    <ul class="advantages">
                        <li>Same-day appointments</li>
                        <li>Diagnosis & treatment</li>
                        <li>Follow-up support</li>
                    </ul>
                </div>

                <!-- Routine Check-Up -->
                <div>
                    <h1 class="prices-headings-3">Routine Check-Up</h1>
                    <h3>$38 / Visit</h3>
                    <p class="pricing-paragraphs">Regular exams for growth and vaccinations.</p>
                    <a href="appointments.php" class="no-underline">
                        <button class="visit-button-white">Make Appointment</button>
                    </a>
                    <ul class="advantages">
                        <li>Growth monitoring</li>
                        <li>Vaccinations included</li>
                        <li>Nutrition & care advice</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Pricing Philosophy -->
        <section class="pricing-heading">
            <div class="pricing-info2">
                <h1>Transparent Pricing</h1>
                <p class="transparent-paragraph">We believe in open communication and fair pricing so you can focus on what matters—your child’s health and happiness.</p>
            </div>
            <div class="pricing-image2">
                <img src="images/pricing1.png" alt="Pricing Philosophy">
            </div>
        </section>

        <!-- Our Team CTA -->
        <section class="hero-about" id="team">
            <div class="hero-info01">
                <h1>Meet Our Dedicated Team</h1>
                <p class="dedicated-paragraph">With diverse backgrounds and a shared vision for success, each member brings unique strengths and expertise that contribute to our collaborative spirit. Together, we work hard to create meaningful experiences, build lasting relationships, and deliver outstanding results for our clients and community.From doctors to nurses, our staff brings compassion and care every day.</p>
                <a href="doctors.php"><button style="margin-top:30px">Learn More</button></a>
            </div>
            <div class="hero-info02">
                <img src="images/about.avif" alt="Our Team">
            </div>
        </section>

        <?php include "includes/templates/footer.php"; ?>
    </main>
<script src="main.js"></script>
</body>
</html>