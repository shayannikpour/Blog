<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Platform</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Outfit", sans-serif;
        }

        #footer-social-section-header {
            margin-left: 135px;
        }

        #footer-social-section-links {
            margin-left: 135px;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php
    ?>
    <footer class="mt-auto bg-white border-top">
        <div class="container main-content">
            <div class="row py-4 justify-content-center align-items-center">
                <div class="col-md-4 mb-4 mb-md-0 text-center text-md-start">
                    <div class="d-flex flex-column h-100 justify-content-center">
                        <span class="text-body-secondary mb-3">Copyright © 2025 Blog</span>
                    </div>
                </div>


                <div class="col-md-4 mb-4 mb-md-0 text-center">
                    <h5 class="mb-3 text-body-secondary">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="mailto:Blog@my.bcit.ca" class="text-body-secondary text-decoration-none">
                                <i class="bi bi-envelope me-2"></i>Blog@my.bcit.ca
                            </a>
                        </li>
                        <li>
                            <a href="tel:+1234567890" class="text-body-secondary text-decoration-none">
                                <i class="bi bi-telephone me-2"></i>+1 (234) 567-890
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 text-center">
                    <h5 class="mb-3 text-body-secondary" id="footer-social-section-header">Follow Us</h5>
                    <div class="d-flex gap-4 justify-content-center" , id="footer-social-section-links">
                        <a href="#" class="text-body-secondary text-decoration-none">
                            <i class="bi bi-facebook fs-4"></i>
                        </a>
                        <a href="#" class="text-body-secondary text-decoration-none">
                            <i class="bi bi-instagram fs-4"></i>
                        </a>
                        <a href="#" class="text-body-secondary text-decoration-none">
                            <i class="bi bi-linkedin fs-4"></i>
                        </a>
                        <a href="#" class="text-body-secondary text-decoration-none">
                            <i class="bi bi-twitter-x fs-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-top py-3">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start text-body-secondary mb-2 mb-md-0">
                        © 2024 Blog Platform. All rights reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <a href="#" class="text-body-secondary text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-body-secondary text-decoration-none">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>