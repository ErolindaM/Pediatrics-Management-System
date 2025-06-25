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
    <title>Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="menus.css">
    
    <style>
        .gallery img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .gallery img:hover {
            transform: scale(1.05);
        }
        .gallery-info p{
            text-align:center;
            font-size:16px
        }
        .footer{
            height:57px;
            padding-top:30px;
            justify-content:center;
            align-items:center
        }
        .gallery-info h1{
            font-size:32px;
        }
         @media (max-width: 580px) {
            .content{
                left:0%
            }
            .footer{
                font-size:10px;
                height:15px;
            }
            .gallery-info h1{
                font-size:24px;
            }
            .gallery-info p{
            font-size:14px;
        }
        }
    </style>
</head>
<body>

    <?php include "includes/templates/sidebar.php"; ?>

    <div class="content">
        <div class="container gallery">
            <div class="gallery-info"  style="text-align:center; margin: 0 auto;">
                <h1 style="text-align:center">Explore Our Pediatrics Through Images</h1>
                <p>DISCOVER MORE ABOUT US</p>
            </div>

            <!-- Responsive gallery using Bootstrap grid -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4" style="margin-bottom:40px">
                <?php
                $images = [
                    "images/image1.jpg",
                    "images/image9.webp",
                    "images/image6.jpg",
                    "images/image7.jpg",
                    "images/image16.jpg",
                    "images/image10.jpg",
                    "images/image11.jpg",
                    "images/image12.jpg",
                    "images/image13.jpg",
                    "images/image14.webp",
                    "images/image5.jfif",
                    "images/image17.jpg",
                    "images/image15.jpg",
                    "images/image4.jpg",
                    "images/image18.jpg",
                    "images/image19.jpg"
                ];

                foreach ($images as $img) {
                    echo '
                    <div class="col">
                        <img src="' . $img . '" class="img-thumbnail gallery-img" data-bs-toggle="modal" data-bs-target="#imageModal">
                    </div>';
                }
                ?>
            </div>
        </div>

        <div class="footer">
            <p>&copy; Copyright 2025 All rights reserved by KIDS CARE</p>
        </div>
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0 text-center">
                        <img src="" id="modalImage" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to load image in modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modalImage = document.getElementById('modalImage');
            const galleryImages = document.querySelectorAll('.gallery-img');

            galleryImages.forEach(img => {
                img.addEventListener('click', function () {
                    modalImage.src = this.src;
                });
            });
        });
    </script>
<script src="main.js"></script>
</body>
</html>
